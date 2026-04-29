<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacultyOtpMail;

use PDF;
use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\User;
use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\FacDesignation;
use App\Models\ScheduleDB\Room;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\FacultyLoad;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Stime;
use App\Models\ScheduleDB\Sday;
use App\Models\ScheduleDB\SetClassSchedule;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\SigPresVice;

use App\Models\EvaluationDB\QCEratingscale;
use App\Models\EvaluationDB\QCEinstruction;
use App\Models\EvaluationDB\QCEcategory;
use App\Models\EvaluationDB\QCEquestion;
use App\Models\EvaluationDB\QCEsubquestion;
use App\Models\EvaluationDB\QCEsemester;
use App\Models\EvaluationDB\QCEfevalrate;
use App\Models\EvaluationDB\QCEsetting;

class GradingFacultyServicesController extends Controller
{
    public function index()
    {
        $activeConfig = ConfigureCurrent::where('set_status', 2)->first();
        if (!$activeConfig) {
            return back()->with('error', 'No active school year found.');
        }
        $activeConfigId = $activeConfig->id;
        
        $previousConfig = ConfigureCurrent::where('id', '<', $activeConfigId) // Ensure it's before the current active one
            ->orderBy('id', 'desc') // Get the most recent one
            ->first();

        $schlyearactiveYear = $activeConfig->schlyear;
        $schlyearactive = $activeConfig->schlyear;
        $semesteractive = $activeConfig->semester;
        
        $authfacdesig = FacDesignation::where('fac_id', Auth::guard('faculty')->user()->id)
                ->where('schlyear', $schlyearactive)
                ->where('semester', $semesteractive)
                ->pluck('fac_id');

        return view('grading.gradesheet.faculty.services.index', compact('authfacdesig'));
    }

    public function schedulefac()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
            
        return view('grading.gradesheet.faculty.services.facschedule.facschedulenow', compact('sy'));
    }

    public function schedulefac_searchview(Request $request) 
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $days = Sday::whereIn('id', [1, 2, 3, 4, 5])->pluck('dayDesc')->toArray();
        $times = Stime::whereIn('id', range(1, 26))->pluck('timeDesc')->toArray();
            
        return view('grading.gradesheet.faculty.services.facschedule.facschedulenowSearchPDF', compact('sy', 'days', 'times'));
    }

    public function fetchMyTeachingSchedule(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $faculty_id = Auth::guard('faculty')->user()->id;
        $campus = Auth::guard('faculty')->user()->campus;

        $schedule = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                        ->where('scheduleclass.schlyear', '=', $schlyear)
                        ->where('scheduleclass.semester', '=', $semester)
                        ->where('scheduleclass.faculty_id', $faculty_id)
                        ->where('scheduleclass.campus', $campus)
                        ->select('sub_offered.subSec', 'scheduleclass.*', 'subjects.sub_name', 'faculty.lname', 'faculty.fname', 'rooms.room_name')
                        ->get();

        return response()->json($schedule);
    }

    public function printMyTeachingSchedule(Request $request)
    {
        $scheduleHtml = $request->input('scheduleHtml');
        $schlyear = $request->input('schlyear', 'Not Available');
        $semester = $request->input('semester', 'Unknown Semester');
        $faculty_id = Auth::guard('faculty')->user()->id;
        $campus = Auth::guard('faculty')->user()->campus;
        

        $faculty = Faculty::where('faculty.id', '=', $faculty_id)->first();
        if ($faculty) {
            $facultyName = $faculty->lname . ', ' . $faculty->fname . ' ' . substr($faculty->mname, 0, 1);
            $facultysigName = $faculty->fname . ' ' . substr($faculty->mname, 0, 1) . '. ' . $faculty->lname;
        } else {
            $facultyName = 'Faculty not found';
            $facultysigName = 'Faculty not found';
        }

        $facDesignateId = FacDesignation::join('college', 'fac_designation.facdept', '=', 'college.college_abbr')
            ->join('faculty', 'fac_designation.fac_id', '=', 'faculty.id')
            ->where('fac_designation.schlyear', $schlyear)
            ->where('fac_designation.semester', $semester)
            ->where('fac_designation.facdept', $faculty->dept)
            ->first();

        $facloadsched = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                        ->leftJoin('coasv2_db_enrollment.studgrades', 'sub_offered.id', '=', 'coasv2_db_enrollment.studgrades.subjID')
                        ->where('scheduleclass.schlyear', '=', $schlyear)
                        ->where('scheduleclass.semester', '=', $semester)
                        ->where('scheduleclass.faculty_id', $faculty_id)
                        ->where('scheduleclass.campus', $campus)
                        ->select('sub_offered.subSec', 
                                'sub_offered.subCode', 
                                'scheduleclass.*', 
                                'subjects.sub_name', 
                                'subjects.sub_title',
                                'subjects.sublecredit',  
                                'subjects.sublabcredit', 
                                'subjects.sub_unit', 
                                'faculty.lname', 
                                'faculty.fname', 
                                'rooms.room_name',
                                DB::raw('COUNT(DISTINCT coasv2_db_enrollment.studgrades.studID) as studentCount'))
                        ->groupBy(
                            'sub_offered.subSec',
                            'sub_offered.subCode',
                        )
                        ->orderBy('sub_offered.subSec')
                        ->get();
        $groupedFacloadsched = $facloadsched->groupBy('sub_name');

        $totalUnits = $facloadsched->sum('sub_unit');
        $totalLeCredits = $facloadsched->sum('sublecredit');
        $totalLabCredits = $facloadsched->sum('sublabcredit');
        $totalContactHours = $facloadsched->sum(function($s) {
            return $s->sublecredit + $s->sublabcredit;
        });

        $vice = SigPresVice::where('position', 'Vice President')
            ->first();

        $data = [
            'scheduleHtml' => $scheduleHtml,
            'schlyear' => $schlyear,
            'semester' => $semester,
            'facultyName' => $facultyName,
            'facultysigName' => $facultysigName,
            'groupedFacloadsched' => $groupedFacloadsched,
            'totalUnits' => $totalUnits,
            'totalLeCredits' => $totalLeCredits,
            'totalLabCredits' => $totalLabCredits,
            'totalContactHours' => $totalContactHours,
            'facDesignateId' => $facDesignateId,
            'vice' => $vice,
        ];

        $pdf = PDF::loadView('grading.gradesheet.faculty.services.facschedule.facschedulenowViewPDF', $data);
        $pdf->setPaper('Legal', 'portrait');

        return $pdf->stream('schedule.pdf');
    }

    public function semesterfac()
    {
        $progen = ConfigureCurrent::where('schlyear', '>=', '2024-2025')
                    ->orderBy('schlyear', 'desc')
                    ->orderBy('semester', 'desc')
                    ->get();
        return view('grading.gradesheet.faculty.services.onlinegradsub.semester', compact('progen'));
    }

    public function virtualfaculty_class(Request $request)
    {
        $semester = $request->query('semester');
        $schlyear = $request->query('schlyear');
        $facID = Auth::guard('faculty')->user()->id;

        $facsubprogen = Grade::leftJoin('coasv2_db_schedule.scheduleclass', 'studgrades.subjID', '=', 'coasv2_db_schedule.scheduleclass.subject_id')
                    ->leftJoin('coasv2_db_schedule.faculty', 'coasv2_db_schedule.scheduleclass.faculty_id', '=', 'coasv2_db_schedule.faculty.id')
                    ->join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select(
                        'studgrades.*',
                        'studgrades.id as stugdeID',
                        'coasv2_db_schedule.subjects.sub_name',
                        'coasv2_db_schedule.sub_offered.subSec',
                        'coasv2_db_schedule.sub_offered.schlyear',
                        'coasv2_db_schedule.sub_offered.semester',
                        'coasv2_db_schedule.sub_offered.campus',
                        'coasv2_db_schedule.scheduleclass.faculty_id',
                        'coasv2_db_schedule.scheduleclass.subject_id',
                        'coasv2_db_schedule.faculty.fname',
                        'coasv2_db_schedule.faculty.lname',
                    )
            ->where('coasv2_db_schedule.sub_offered.semester', $semester)
            ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyear)
            ->where('coasv2_db_schedule.sub_offered.campus', Auth::guard('faculty')->user()->campus)
            ->where('coasv2_db_schedule.scheduleclass.faculty_id', $facID)
            ->groupBy('studgrades.subjID')
            ->get();

        return view('grading.gradesheet.faculty.services.onlinegradsub.virtualroom', compact('facsubprogen', 'semester', 'schlyear'));
    }

    public function virtual_facultysubjectclass(Request $request, $id)
    {
        $semester = $request->query('semester');
        $schlyear = $request->query('schlyear');
        $faculty = Auth::guard('faculty')->user();
        $campus = $faculty->campus;
        $facID = $faculty->id;

        $sub = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                ->join('coasv2_db_enrollment.studgrades', 'scheduleclass.subject_id', '=', 'coasv2_db_enrollment.studgrades.subjID')
                ->join('coasv2_db_enrollment.students', 'coasv2_db_enrollment.studgrades.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                ->where('sub_offered.schlyear', $schlyear)
                ->where('sub_offered.semester', $semester)
                ->where('scheduleclass.faculty_id', $facID)
                ->where('coasv2_db_enrollment.studgrades.subjID', $id)
                ->select('scheduleclass.*', 'sub_offered.*', 'subjects.*', 'coasv2_db_enrollment.studgrades.*', 'coasv2_db_enrollment.studgrades.status as gstat', 'coasv2_db_enrollment.students.*', 'coasv2_db_enrollment.studgrades.id as sgid' )
                ->orderBy('coasv2_db_enrollment.students.lname', 'ASC')
                ->groupBy('studgrades.studID')
                ->get();

        $substudcount = $sub->count();

        $grdpercentage = array_merge(range(44, 78), [81, 82, 83, 84, 85, 86, 87, 88, 89, 90, 91]);

        $grdCode = GradeCode::whereIn('id', $grdpercentage)
                ->orderByRaw('CASE WHEN id BETWEEN 44 AND 74 THEN id END DESC, id DESC')
                ->get();

        $grdpercentageComp = range(44, 91);

        $grdCodeComp = GradeCode::whereIn('id', $grdpercentageComp)
                ->orderByRaw('CASE WHEN id BETWEEN 44 AND 91 THEN id END DESC, id DESC')
                ->get();

        $grade = Grade::where('subjID', $id)
                        ->where('status', '!=', '')
                        ->count();

        return view('grading.gradesheet.faculty.services.onlinegradsub.virtualsubroom', compact('sub', 'substudcount', 'grdCode', 'grdCodeComp', 'grade'));
    }

    public function supfaceval()
    {
        $currsem = QCEsemester::where('qcesemstat', 2)->get();
        $currsemnow = QCEsemester::where('qcesemstat', 2)->first();

        $sy = ConfigureCurrent::where('set_status', 2)->first(['schlyear', 'semester']);
        $setevalmode = QCEsetting::first();

        $collegedean = FacDesignation::where('fac_id', Auth::guard('faculty')->user()->id)
                    ->where('facCollege', '=', Auth::guard('faculty')->user()->faccollege)
                    ->where('designation', '=', 'Dean')
                    ->where('schlyear', $currsemnow->qceschlyear)
                    ->where('semester', $currsemnow->qcesemester)
                    ->where('campus', Auth::guard('faculty')->user()->campus)
                    ->first();
        
        $collegeprogramhead = FacDesignation::where('fac_id', Auth::guard('faculty')->user()->id)
                    ->where('facCollege', '=', Auth::guard('faculty')->user()->faccollege)
                    ->where('designation', '=', 'Program Head')
                    ->where('schlyear', $currsemnow->qceschlyear)
                    ->where('semester', $currsemnow->qcesemester)
                    ->where('campus', Auth::guard('faculty')->user()->campus)
                    ->first();

        $casdivisionchair = FacDesignation::where('fac_id', Auth::guard('faculty')->user()->id)
                    ->where('facCollege', '=', Auth::guard('faculty')->user()->faccollege)
                    ->where('designation', '=', 'Division Chair')
                    ->where('schlyear', $currsemnow->qceschlyear)
                    ->where('semester', $currsemnow->qcesemester)
                    ->where('campus', Auth::guard('faculty')->user()->campus)
                    ->first();

        $facdivisionchair = Faculty::leftJoin('fac_designation', 'faculty.id', '=', 'fac_designation.fac_id')
                    ->where('faculty.faccollege', '=', Auth::guard('faculty')->user()->faccollege)
                    ->where('faculty.campus', '=', Auth::guard('faculty')->user()->campus)
                    ->where('fac_designation.designation', '=', 'Division Chair')
                    ->select(
                            'faculty.id', 
                            'faculty.fname', 
                            'faculty.mname', 
                            'faculty.lname', 
                            'faculty.rank', 
                            'faculty.campus', 
                            'faculty.id as facID', 
                            'fac_designation.designation'
                        )
                    ->get();

        $facollegedean = Faculty::join('fac_designation', 'faculty.id', '=', 'fac_designation.fac_id')
                    ->where('faculty.faccollege', '=', Auth::guard('faculty')->user()->faccollege)
                    ->where('faculty.campus', '=', Auth::guard('faculty')->user()->campus)
                    ->where('fac_designation.designation', '=', 'Program Head')
                    ->select(
                        'faculty.id', 
                        'faculty.fname', 
                        'faculty.mname', 
                        'faculty.lname', 
                        'faculty.rank', 
                        'faculty.campus', 
                        'faculty.id as facID', 
                        'fac_designation.designation', 
                        'fac_designation.facCollege'
                    )
                    ->get();
        
        $facollegeprogramhead = Faculty::leftJoin('fac_designation', 'faculty.id', '=', 'fac_designation.fac_id')
                    ->where('faculty.faccollege', '=', Auth::guard('faculty')->user()->faccollege)
                    ->where('faculty.facdept', '=', Auth::guard('faculty')->user()->facdept)
                    ->where('faculty.campus', Auth::guard('faculty')->user()->campus)
                    ->where('faculty.role', '=', 943)
                    ->whereNull('fac_designation.fac_id')
                    ->select('faculty.id', 'faculty.fname', 'faculty.mname', 'faculty.lname', 'faculty.rank', 'faculty.campus', 'faculty.id as facID', 'fac_designation.designation', 'fac_designation.facCollege')
                    ->get();
        
        $disabledsubj = QCEfevalrate::where('qceformevalrate.evaluatorID', Auth::guard('faculty')->user()->id)
                    ->whereIn('qceformevalrate.statprint', [1,2])
                    ->where('qceformevalrate.schlyear', $currsemnow->qceschlyear)
                    ->where('qceformevalrate.semester', $currsemnow->qcesemester)
                    ->where('qceformevalrate.qceevaluator', '=', 'Program Head')
                    ->pluck('qcefacID');

        $disabledsubjdean = QCEfevalrate::where('evaluatorID', Auth::guard('faculty')->user()->id)
                    ->whereIn('statprint', [1,2])
                    ->where('schlyear', $currsemnow->qceschlyear)
                    ->where('semester', $currsemnow->qcesemester)
                    ->where('qceevaluator', 'Dean')
                    ->pluck('qcefacID');
                        
        return view('grading.gradesheet.faculty.services.viewfaceval.subslisteval', compact('currsem', 'sy', 'collegedean', 'collegeprogramhead', 'casdivisionchair', 'facdivisionchair', 'facollegedean', 'facollegeprogramhead', 'setevalmode', 'disabledsubj', 'disabledsubjdean'));
    }

    public function supfacevalrate(Request $request)
    {
        $subjsIDselected = $request->query('id');
        $qcefacID = $request->query('qcefacID');

        $ratingscale = QCEratingscale::orderBy('inst_scale', 'DESC')->where('instratingscalestat', 1)->get();
        $inst = QCEinstruction::where('instructcat', 1)->get();
        $sy = ConfigureCurrent::where('set_status', 2)->first(['schlyear', 'semester']);
        $currsemnow = QCEsemester::where('qcesemstat', 2)->first();
        $currsem = QCEsemester::where('qcesemstat', 2)
            ->get([
                'qceschlyear',
                'qcesemester',
                'qceratingfrom',
                'qceratingto',
                'id'
            ]);

        $question = QCEquestion::join('qcecategory', 'qcequestion.catName_id', '=', 'qcecategory.id')
                ->select('qcecategory.catName', 'qcequestion.id', 'qcequestion.questiontext')
                ->where('qcecategory.catstatus', 2)
                ->where('qcequestion.questcat', 2)
                ->orderBy('qcecategory.catName') 
                ->orderBy('qcequestion.id') 
                ->get()
                ->groupBy('catName');
        
        $facdetail = Faculty::where('id', $qcefacID)->first();

        $facDesignateRole = FacDesignation::where('fac_id', Auth::guard('faculty')->user()->id)
                ->where('schlyear', $currsemnow->qceschlyear)
                ->where('semester', $currsemnow->qcesemester)
                ->first();
    
        return view('grading.gradesheet.faculty.services.viewfaceval.subslistevalrate', compact('inst', 'ratingscale',  'currsem', 'question', 'facdetail', 'facDesignateRole'));
    }

    public function deanfacevalrateformCreate(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'question_rate' => 'required|array',
            ]);
            
            try {
                $existingSurvey = QCEfevalrate::where('campus', $request->input('campus'))
                        ->where('semester', $request->input('semester'))
                        ->where('schlyear', $request->input('schlyear'))
                        ->where('qcefacname', $request->input('qcefacname'))
                        ->where('studidno', $request->input('studidno'))
                        ->where('evaluatorname', $request->input('evaluatorname'))
                        ->first();

                if ($existingSurvey) {
                    return redirect()->route('formRead')->with('error', 'You already submitted a survey for this subject and faculty');
                }

                $latestRateCount = QCEfevalrate::where('campus', $request->input('campus'))
                    ->where('semester', $request->input('semester'))
                    ->where('schlyear', $request->input('schlyear'))
                    ->where('qcefacname', $request->input('qcefacname'))
                    // ->where('subjidrate', $request->input('subjidrate'))
                    ->max('ratecount');

                // Increment the latest count or start from 1 if no previous record exists
                $newRateCount = $latestRateCount ? $latestRateCount + 1 : 1;

                QCEfevalrate::create([
                    'ratecount' => $newRateCount,
                    'campus' => $request->input('campus'),
                    'qceschlyearsemID' => $request->input('qceschlyearsemID'),
                    'schlyear' => $request->input('schlyear'),
                    'semester' => $request->input('semester'),
                    'ratingfromto' => $request->input('ratingfromto'),
                    'qcefacID' => $request->input('qcefacID'),
                    'qcefacname' => $request->input('qcefacname'),
                    'qceevaluator' => $request->input('qceevaluator'),
                    'question' => json_encode($request->input('question')),
                    'question_rate' => json_encode($request->input('question_rate')),
                    'qcecomments' => $request->input('qcecomments'),
                    'evaluatorname' => $request->input('evaluatorname'),
                    'evaluatorID' => $request->input('evaluatorID'),
                    'studidno' => $request->input('studidno'),
                    'prog' => $request->input('prog'),
                ]);

                return redirect()->route('supfaceval')->with('success', 'Survey Submitted Successfully');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to Submit Survey');
            }
        }
    }
}
