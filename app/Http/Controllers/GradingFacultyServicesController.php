<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\FacultyOtpMail;
use App\Helpers\EncryptionHelper;

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
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

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
        
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];
            
        return view('grading.gradesheet.faculty.services.facschedule.facschedulenow', compact('sy' ,'authfacdesig'));
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

        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];
            
        return view('grading.gradesheet.faculty.services.facschedule.facschedulenowSearchPDF', compact('sy', 'days', 'times', 'authfacdesig'));
    }

    public function fetchMyTeachingSchedule(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $faculty_id = Auth::guard('faculty')->user()->id;
        $campus = Auth::guard('faculty')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $schedule = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                        ->where('scheduleclass.schlyear', '=', $schlyear)
                        ->where('scheduleclass.semester', '=', $semester)
                        ->where('scheduleclass.faculty_id', $faculty_id)
                        // ->where('scheduleclass.campus', $campus)
                        ->where(function ($q) use ($campusArray) {
                            foreach ($campusArray as $campus) {
                                $q->orWhere('scheduleclass.campus', 'LIKE', "$campus");
                            }
                        })
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
        $campusArray = array_map('trim', explode(',', $campus));
        

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
                        ->where(function ($q) use ($campusArray) {
                            foreach ($campusArray as $campus) {
                                $q->orWhere('scheduleclass.campus', 'LIKE', "$campus");
                            }
                        })
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

        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        return view('grading.gradesheet.faculty.services.onlinegradsub.semester', compact('progen', 'authfacdesig'));
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

        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        return view('grading.gradesheet.faculty.services.onlinegradsub.virtualroom', compact('facsubprogen', 'semester', 'schlyear', 'authfacdesig'));
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
        
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        return view('grading.gradesheet.faculty.services.onlinegradsub.virtualsubroom', compact('sub', 'substudcount', 'grdCode', 'grdCodeComp', 'grade', 'authfacdesig'));
    }

    public function supfaceval()
    {
        $user = Auth::guard('faculty')->user();
        $currsemnow = QCEsemester::where('qcesemstat', 2)->first();
        
        if (!$currsemnow) {
            return redirect()->back()->with('error', 'No active semester found.');
        }

        $sy = ConfigureCurrent::where('set_status', 4)->first(['schlyear', 'semester']);
        $setevalmode = QCEsetting::first();

        // Get user's designations for the current semester
        $userDesignations = FacDesignation::where('fac_id', $user->id)
            ->where('schlyear', $currsemnow->qceschlyear)
            ->where('semester', $currsemnow->qcesemester)
            ->where('campus', $user->campus)
            ->get();

        // Check if user has specific designations
        $hasVicePresident = $userDesignations->contains('designation', 'Vice President');
        $hasCampusAdmin = $userDesignations->contains('designation', 'CampusAdmin');
        $hasDeanInstruction = $userDesignations->contains('designation', 'Dean of Instruction');
        $hasDean = $userDesignations->contains('designation', 'Dean');
        $hasProgramHead = $userDesignations->contains('designation', 'Program Head');
        $hasDivisionChair = $userDesignations->contains('designation', 'Division Chair');

        // Get the actual designation objects
        $collegedean = $userDesignations->where('designation', 'Dean')->first();
        $collegeprogramhead = $userDesignations->where('designation', 'Program Head')->first();
        $casdivisionchair = $userDesignations->where('designation', 'Division Chair')->first();
        $campusdeaninstruction = $userDesignations->where('designation', 'Dean of Instruction')->first();

        // Determine user's college and department
        $userCollege = $user->faccollege;
        $userDept = $user->facdept;
        $userDeptMajor = $user->deptmajor;

        // Get ALL faculty with their designations in the user's campus (excluding current user)
        $allFacultyInCampus = Faculty::join('fac_designation', 'faculty.id', '=', 'fac_designation.fac_id')
            ->where('faculty.campus', $user->campus)
            ->where('faculty.id', '!=', $user->id)
            ->whereIn('fac_designation.designation', ['Dean', 'Program Head', 'Division Chair', 'Dean of Instruction'])
            ->where('fac_designation.schlyear', $currsemnow->qceschlyear)
            ->where('fac_designation.semester', $currsemnow->qcesemester)
            ->select(
                'faculty.id', 
                'faculty.fname', 
                'faculty.mname', 
                'faculty.lname', 
                'faculty.rank', 
                'faculty.campus', 
                'faculty.faccollege',
                'faculty.facdept',
                'faculty.id as facID', 
                'fac_designation.designation', 
                'fac_designation.facCollege',
                'fac_designation.id as desigID'
            )
            ->get();

        // Group by faculty ID to handle multiple designations
        $facultyGrouped = $allFacultyInCampus->groupBy('id');

        // Get unique faculty with their designations (excluding current user)
        $allFacultyWithDesignations = $facultyGrouped->map(function($facultyGroup) {
            $faculty = $facultyGroup->first();
            $faculty->designations = $facultyGroup->pluck('designation')->toArray();
            $faculty->facColleges = $facultyGroup->pluck('facCollege')->filter()->toArray();
            $faculty->subjIDs = $facultyGroup->pluck('subjID')->filter()->toArray();
            return $faculty;
        })->values();

        // Get faculties without designation (role 943) - excluding current user
        $regularFaculties = SetClassSchedule::leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
            ->leftJoin('fac_designation', 'faculty.id', '=', 'fac_designation.fac_id')
            ->where('scheduleclass.campus', $user->campus)
            ->where('scheduleclass.faculty_id', '!=', $user->id)
            ->where('faculty.role', 943)
            ->where('scheduleclass.schlyear', $currsemnow->qceschlyear)
            ->where('scheduleclass.semester', $currsemnow->qcesemester)
            //->whereNull('fac_designation.fac_id')
            ->select(
                'faculty.id', 
                'faculty.fname', 
                'faculty.mname', 
                'faculty.lname', 
                'faculty.rank', 
                'faculty.campus', 
                'faculty.faccollege',
                'faculty.facdept',
                'faculty.deptmajor',
                'faculty.id as facID', 
                'fac_designation.designation', 
                'fac_designation.facCollege',
                'scheduleclass.progcodename'
            )
            ->distinct()
            ->get()
            ->map(function($faculty) {
                $faculty->designations = ['Faculty'];
                $faculty->facColleges = [$faculty->faccollege];
                $faculty->subjIDs = [];
                return $faculty;
            });

        // Get disabled subjects for different evaluator types
        $disabledsubj = QCEfevalrate::where('evaluatorID', $user->id)
            ->whereIn('statprint', [1,2])
            ->where('schlyear', $currsemnow->qceschlyear)
            ->where('semester', $currsemnow->qcesemester)
            ->where('qceevaluator', 'Program Head')
            ->pluck('qcefacID');

        $disabledsubjdean = QCEfevalrate::where('evaluatorID', $user->id)
            ->whereIn('statprint', [1,2])
            ->where('schlyear', $currsemnow->qceschlyear)
            ->where('semester', $currsemnow->qcesemester)
            ->where('qceevaluator', 'Dean')
            ->pluck('qcefacID');

        $disabledsubjdivchair = QCEfevalrate::where('evaluatorID', $user->id)
            ->whereIn('statprint', [1,2])
            ->where('schlyear', $currsemnow->qceschlyear)
            ->where('semester', $currsemnow->qcesemester)
            ->where('qceevaluator', 'Division Chair')
            ->pluck('qcefacID');

        $disabledsubjcampusdeaninstruction = QCEfevalrate::where('evaluatorID', $user->id)
            ->whereIn('statprint', [1,2])
            ->where('schlyear', $currsemnow->qceschlyear)
            ->where('semester', $currsemnow->qcesemester)
            ->where('qceevaluator', 'Dean of Instruction')
            ->pluck('qcefacID');
        $disabledsubjcampusadmin = QCEfevalrate::where('evaluatorID', $user->id)
            ->whereIn('statprint', [1,2])
            ->where('schlyear', $currsemnow->qceschlyear)
            ->where('semester', $currsemnow->qcesemester)
            ->where('qceevaluator', 'CampusAdmin')
            ->pluck('qcefacID');

        // Get active faculty designation data from parent Controller
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        // Get all active semesters
        $currsem = QCEsemester::where('qcesemstat', 2)->get();

        // Build evaluation sections based on user roles
        $sections = $this->getEvaluationSections(
            $user,
            $hasVicePresident,
            $hasCampusAdmin,
            $hasDeanInstruction,
            $hasDean,
            $hasProgramHead,
            $hasDivisionChair,
            $campusdeaninstruction,
            $casdivisionchair,
            $collegedean,
            $collegeprogramhead,
            $allFacultyWithDesignations,
            $regularFaculties,
            $userCollege,
            $userDept,
            $userDeptMajor,
            $disabledsubj,
            $disabledsubjdean,
            $disabledsubjdivchair,
            $disabledsubjcampusdeaninstruction,
            $disabledsubjcampusadmin

        );

        return view('grading.gradesheet.faculty.services.viewfaceval.subslisteval', compact(
            'currsem', 
            'sy', 
            'setevalmode', 
            'sections',
            'campusdeaninstruction',
            'collegedean',
            'collegeprogramhead',
            'casdivisionchair',
            'authfacdesig'
        ));
    }

    private function getEvaluationSections(
        $user,
        $hasVicePresident,
        $hasCampusAdmin,
        $hasDeanInstruction,
        $hasDean,
        $hasProgramHead,
        $hasDivisionChair,
        $campusdeaninstruction,
        $casdivisionchair,
        $collegedean,
        $collegeprogramhead,
        $allFacultyWithDesignations,
        $regularFaculties,
        $userCollege,
        $userDept,
        $userMajor,
        $disabledsubj,
        $disabledsubjdean,
        $disabledsubjdivchair,
        $disabledsubjcampusdeaninstruction,
        $disabledsubjcampusadmin
    ) 
    {
        $sections = [];

        // ============================================================
        // SCENARIO 1: Dean of Instruction ONLY (No Program Head)
        // Display all Deans and Program Heads in the campus
        // ============================================================
        if ($hasDeanInstruction && !$hasProgramHead) {
            // Get all Program Heads in the campus (excluding self)
            $deansInCampus = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
                return in_array('Dean', $faculty->designations) &&
                       $faculty->id != $user->id;
            });

            $programHeadsInCampus = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
                return in_array('Program Head', $faculty->designations) &&
                    $faculty->id != $user->id;
            });

            if ($deansInCampus->isNotEmpty()) {
                $sections[] = [
                    'title' => 'Deans',
                    'data' => $deansInCampus,
                    'evaluator' => 'Dean of Instruction',
                    'disabled' => $disabledsubjcampusdeaninstruction,
                    'icon' => 'ti ti-user',
                    'role' => 'Dean of Instruction'
                ];
            }

            if ($programHeadsInCampus->isNotEmpty()) {
                $sections[] = [
                    'title' => 'Program Heads',
                    'data' => $programHeadsInCampus,
                    'evaluator' => 'Dean of Instruction',
                    'disabled' => $disabledsubjcampusdeaninstruction,
                    'icon' => 'ti ti-user',
                    'role' => 'Dean of Instruction'
                ];
            }
        }

        // ============================================================
        // SCENARIO 2: Dean of Instruction + No Program Head
        // Display all Deans and Program Heads in the campus
        // ============================================================
        if ($hasDeanInstruction && $hasProgramHead) {
            // Get all Program Heads in the campus (excluding self)
            $deansInCampus = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
                return in_array('Dean', $faculty->designations) &&
                       $faculty->id != $user->id;
            });

            $programHeadsInCampus = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
                return in_array('Program Head', $faculty->designations) &&
                    $faculty->id != $user->id;
            });

            if ($deansInCampus->isNotEmpty()) {
                $sections[] = [
                    'title' => 'Deans',
                    'data' => $deansInCampus,
                    'evaluator' => 'Dean of Instruction',
                    'disabled' => $disabledsubjcampusdeaninstruction,
                    'icon' => 'ti ti-user',
                    'role' => 'Dean of Instruction'
                ];
            }

            if ($programHeadsInCampus->isNotEmpty()) {
                $sections[] = [
                    'title' => 'Program Heads',
                    'data' => $programHeadsInCampus,
                    'evaluator' => 'Dean of Instruction',
                    'disabled' => $disabledsubjcampusdeaninstruction,
                    'icon' => 'ti ti-user',
                    'role' => 'Dean of Instruction'
                ];
            }

            $filteredFaculties = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $userMajor, $user) {
                // Split progCode by '-' to get parts
                $progParts = explode('-', $faculty->progcodename);
                
                // Get college, dept, major from progCode
                $progCollege = $progParts[0] ?? '';
                $progDept = $progParts[1] ?? '';
                $progMajor = $progParts[2] ?? '';
                
                // Base condition: match college and department
                $isMatch = $progCollege == $userCollege && $progDept == $userDept;
                
                // If user has a major specified, also match the major
                if (!empty($userMajor)) {
                    $isMatch = $isMatch && $progMajor == $userMajor;
                }
                
                // Exclude the user themselves
                return $isMatch && $faculty->id != $user->id;
            });
            
            // Remove duplicate faculty entries (one faculty might have multiple progCodes)
            $uniqueFaculties = $filteredFaculties->unique('id');
            
            if ($uniqueFaculties->isNotEmpty()) {
                // Create title based on available filters
                $title = 'Faculties';
                if (!empty($userMajor)) {
                    $title .= ' (' . $userMajor . ' Major)';
                } else {
                    $title .= ' (' . $userDept . ' Department - ' . $userCollege . ' College)';
                }
                
                $sections[] = [
                    'title' => $title,
                    'data' => $uniqueFaculties,
                    'evaluator' => 'Program Head',
                    'disabled' => $disabledsubj,
                    'icon' => 'ti ti-users',
                    'role' => 'Program Head',
                    'college' => $userCollege,
                    'department' => $userDept,
                    'major' => $userMajor
                ];
            }   
        }

        // ============================================================
        // SCENARIO 2: CAMPUS ADMIN + PROGRAM HEAD
        // Display all Program Heads AND Faculties in their college
        // ============================================================
        if ($hasCampusAdmin) {

            $programHeadsInCampus = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
                return in_array('Program Head', $faculty->designations)
                    && $faculty->id != $user->id;
            });

            if ($programHeadsInCampus->isNotEmpty()) {
                $sections[] = [
                    'title' => 'Program Heads',
                    'data' => $programHeadsInCampus,
                    'evaluator' => 'CampusAdmin',
                    'disabled' => $disabledsubjcampusadmin,
                    'icon' => 'ti ti-user',
                    'role' => 'CampusAdmin'
                ];
            }
        }

        // ============================================================
        // SCENARIO 2: CAMPUS ADMIN + PROGRAM HEAD
        // Display all Program Heads AND Faculties in their college
        // ============================================================
        // if ($hasCampusAdmin) {

        //     $programHeadsInCampus = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
        //         return in_array('Program Head', $faculty->designations)
        //             && $faculty->id != $user->id;
        //     });

        //     if ($programHeadsInCampus->isNotEmpty()) {
        //         $sections[] = [
        //             'title' => 'Program Heads',
        //             'data' => $programHeadsInCampus,
        //             'evaluator' => 'CampusAdmin',
        //             'disabled' => $disabledsubjcampusadmin,
        //             'icon' => 'ti ti-user',
        //             'role' => 'CampusAdmin'
        //         ];
        //     }
        // }

        // if ($hasCampusAdmin && $hasProgramHead) {
        //     // Get all Program Heads in the campus (excluding self)
        //     $programHeadsInCampus = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
        //         return in_array('Program Head', $faculty->designations) &&
        //                $faculty->id != $user->id;
        //     });

        //     // Get faculties in the user's college and department (excluding self)
        //     $facultiesInCollege = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $user) {
        //         return $faculty->faccollege == $userCollege &&
        //                $faculty->facdept == $userDept &&
        //                $faculty->id != $user->id;
        //     });

        //     if ($programHeadsInCampus->isNotEmpty()) {
        //         $sections[] = [
        //             'title' => 'Program Heads (Campus-wide)',
        //             'data' => $programHeadsInCampus,
        //             'evaluator' => 'CampusAdmin',
        //             'disabled' => $disabledsubjcampusadmin,
        //             'icon' => 'ti ti-user',
        //             'role' => 'CampusAdmin'
        //         ];
        //     }

        //     if ($facultiesInCollege->isNotEmpty()) {
        //         $sections[] = [
        //             'title' => 'Faculties (College)',
        //             'data' => $facultiesInCollege,
        //             'evaluator' => 'Program Head',
        //             'disabled' => $disabledsubj,
        //             'icon' => 'ti ti-users',
        //             'role' => 'Program Head'
        //         ];
        //     }
        // }

        // ============================================================
        // SCENARIO 3: DEAN ONLY (No Program Head)
        // Modified to handle CAS Division Chairs
        // ============================================================
        if ($hasDean && !$hasProgramHead) {
            // Check if Dean is from CAS
            $deanCollege = $collegedean->facCollege ?? $userCollege;
            $isCAS = ($deanCollege == 'CAS');
            
            if ($isCAS) {
                // For CAS: Show Division Chairs (Dean evaluates them)
                $divisionChairsInCollege = $allFacultyWithDesignations->filter(function($faculty) use ($deanCollege, $user) {
                    return in_array('Division Chair', $faculty->designations) &&
                        in_array($deanCollege, $faculty->facColleges) &&
                        $faculty->id != $user->id;
                });

                if ($divisionChairsInCollege->isNotEmpty()) {
                    $sections[] = [
                        'title' => 'Division Chairs (CAS)',
                        'data' => $divisionChairsInCollege,
                        'evaluator' => 'Dean',
                        'disabled' => $disabledsubjdean,
                        'icon' => 'ti ti-briefcase',
                        'role' => 'Dean'
                    ];
                }
                
                // Also show Program Heads (if any) - but Dean doesn't evaluate them directly
                // $programHeadsInCAS = $allFacultyWithDesignations->filter(function($faculty) use ($deanCollege, $user) {
                //     return in_array('Program Head', $faculty->designations) &&
                //            in_array($deanCollege, $faculty->facColleges) &&
                //            $faculty->id != $user->id;
                // });

                // if ($programHeadsInCAS->isNotEmpty()) {
                //     $sections[] = [
                //         'title' => 'Program Heads (CAS)',
                //         'data' => $programHeadsInCAS,
                //         'evaluator' => 'Division Chair',  // Division Chair evaluates them
                //         'disabled' => $disabledsubjdivchair,
                //         'icon' => 'ti ti-user',
                //         'role' => 'Division Chair'
                //     ];
                // }
            } else {
                // Non-CAS: Check if there are Program Heads in the college
                $hasProgramHeadsInCollege = $allFacultyWithDesignations->filter(function($faculty) use ($userCollege) {
                    return in_array('Program Head', $faculty->designations) &&
                        in_array($userCollege, $faculty->facColleges);
                })->isNotEmpty();

                if ($hasProgramHeadsInCollege) {
                    // If there are Program Heads, display them
                    $programHeadsInCollege = $allFacultyWithDesignations->filter(function($faculty) use ($userCollege, $user) {
                        return in_array('Program Head', $faculty->designations) &&
                            in_array($userCollege, $faculty->facColleges) &&
                            $faculty->id != $user->id;
                    });

                    if ($programHeadsInCollege->isNotEmpty()) {
                        $sections[] = [
                            'title' => 'Program Heads',
                            'data' => $programHeadsInCollege,
                            'evaluator' => 'Dean',
                            'disabled' => $disabledsubjdean,
                            'icon' => 'ti ti-user',
                            'role' => 'Dean'
                        ];
                    }
                } else {
                    // If NO Program Heads, display all Faculty directly
                    $filteredFaculties = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $userMajor, $user) {
                        // Split progCode by '-' to get parts
                        $progParts = explode('-', $faculty->progcodename);
                        
                        // Get college, dept, major from progCode
                        $progCollege = $progParts[0] ?? '';
                        $progDept = $progParts[1] ?? '';
                        $progMajor = $progParts[2] ?? '';
                        
                        // Base condition: match college and department
                        $isMatch = $progCollege == $userCollege && $progDept == $userDept;
                        
                        // If user has a major specified, also match the major
                        if (!empty($userMajor)) {
                            $isMatch = $isMatch && $progMajor == $userMajor;
                        }
                        
                        // Exclude the user themselves
                        return $isMatch && $faculty->id != $user->id;
                    });
                    
                    // Remove duplicate faculty entries (one faculty might have multiple progCodes)
                    $uniqueFaculties = $filteredFaculties->unique('id');
                    
                    if ($uniqueFaculties->isNotEmpty()) {
                        // Create title based on available filters
                        $title = 'Faculties';
                        if (!empty($userMajor)) {
                            $title .= ' (' . $userMajor . ' Major)';
                        } else {
                            $title .= ' (' . $userDept . ' Department - ' . $userCollege . ' College)';
                        }
                        
                        $sections[] = [
                            'title' => $title,
                            'data' => $uniqueFaculties,
                            'evaluator' => 'Dean',
                            'disabled' => $disabledsubjdean,
                            'icon' => 'ti ti-users',
                            'role' => 'Dean',
                            'college' => $userCollege,
                            'department' => $userDept,
                            'major' => $userMajor
                        ];
                    }   
                    // $facultiesInCollege = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $user) {
                    //     return $faculty->faccollege == $userCollege &&
                    //         $faculty->facdept == $userDept &&
                    //         $faculty->id != $user->id;
                    // });

                    // if ($facultiesInCollege->isNotEmpty()) {
                    //     $sections[] = [
                    //         'title' => 'Faculties',
                    //         'data' => $facultiesInCollege,
                    //         'evaluator' => 'Dean',
                    //         'disabled' => $disabledsubjdean,
                    //         'icon' => 'ti ti-users',
                    //         'role' => 'Dean'
                    //     ];
                    // }
                }
            }
        }

        // ============================================================
        // SCENARIO 4: DEAN + PROGRAM HEAD
        // Display Program Heads AND Faculties in their college
        // ============================================================
        if ($hasDean && $hasProgramHead) {
            // Get user's college
            $deanCollege = $collegedean->facCollege ?? $userCollege;

            // Special handling for CAS
            $isCAS = ($deanCollege == 'CAS');

            if ($isCAS) {
                // CAS: Dean evaluates Division Chair
                $divisionChairsInCollege = $allFacultyWithDesignations->filter(function($faculty) use ($deanCollege, $user) {
                    return in_array('Division Chair', $faculty->designations) &&
                           in_array($deanCollege, $faculty->facColleges) &&
                           $faculty->id != $user->id;
                });

                if ($divisionChairsInCollege->isNotEmpty()) {
                    $sections[] = [
                        'title' => 'Division Chairs (CAS)',
                        'data' => $divisionChairsInCollege,
                        'evaluator' => 'Dean',
                        'disabled' => $disabledsubjdean,
                        'icon' => 'ti ti-briefcase',
                        'role' => 'Dean'
                    ];
                }

                // Program Heads (excluding self) in CAS
                $programHeadsInCAS = $allFacultyWithDesignations->filter(function($faculty) use ($deanCollege, $user) {
                    return in_array('Program Head', $faculty->designations) &&
                           in_array($deanCollege, $faculty->facColleges) &&
                           $faculty->id != $user->id;
                });

                if ($programHeadsInCAS->isNotEmpty()) {
                    $sections[] = [
                        'title' => 'Program Heads (CAS)',
                        'data' => $programHeadsInCAS,
                        'evaluator' => 'Division Chair',
                        'disabled' => $disabledsubjdivchair,
                        'icon' => 'ti ti-user',
                        'role' => 'Division Chair'
                    ];
                }

            } else {
                // Non-CAS: Dean evaluates Program Heads
                $programHeadsInCollege = $allFacultyWithDesignations->filter(function($faculty) use ($deanCollege, $user) {
                    return in_array('Program Head', $faculty->designations) &&
                           in_array($deanCollege, $faculty->facColleges) &&
                           $faculty->id != $user->id;
                });

                if ($programHeadsInCollege->isNotEmpty()) {
                    $sections[] = [
                        'title' => 'Program Heads',
                        'data' => $programHeadsInCollege,
                        'evaluator' => 'Dean',
                        'disabled' => $disabledsubjdean,
                        'icon' => 'ti ti-user',
                        'role' => 'Dean'
                    ];
                }
            }

            // As Program Head: Evaluate Faculties in their college/department
            $filteredFaculties = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $userMajor, $user) {
                // Split progCode by '-' to get parts
                $progParts = explode('-', $faculty->progcodename);
                
                // Get college, dept, major from progCode
                $progCollege = $progParts[0] ?? '';
                $progDept = $progParts[1] ?? '';
                $progMajor = $progParts[2] ?? '';
                
                // Base condition: match college and department
                $isMatch = $progCollege == $userCollege && $progDept == $userDept;
                
                // If user has a major specified, also match the major
                if (!empty($userMajor)) {
                    $isMatch = $isMatch && $progMajor == $userMajor;
                }
                
                // Exclude the user themselves
                return $isMatch && $faculty->id != $user->id;
            });
            
            // Remove duplicate faculty entries (one faculty might have multiple progCodes)
            $uniqueFaculties = $filteredFaculties->unique('id');
            
            if ($uniqueFaculties->isNotEmpty()) {
                // Create title based on available filters
                $title = 'Faculties';
                if (!empty($userMajor)) {
                    $title .= ' (' . $userMajor . ' Major)';
                } else {
                    $title .= ' (' . $userDept . ' Department - ' . $userCollege . ' College)';
                }
                
                $sections[] = [
                    'title' => $title,
                    'data' => $uniqueFaculties,
                    'evaluator' => 'Program Head',
                    'disabled' => $disabledsubj,
                    'icon' => 'ti ti-users',
                    'role' => 'Program Head',
                    'college' => $userCollege,
                    'department' => $userDept,
                    'major' => $userMajor
                ];
            }   
            // $facultiesInCollege = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $user) {
            //     return $faculty->faccollege == $userCollege &&
            //            $faculty->facdept == $userDept &&
            //            $faculty->id != $user->id;
            // });

            // if ($facultiesInCollege->isNotEmpty()) {
            //     $sections[] = [
            //         'title' => 'Faculties',
            //         'data' => $facultiesInCollege,
            //         'evaluator' => 'Program Head',
            //         'disabled' => $disabledsubj,
            //         'icon' => 'ti ti-users',
            //         'role' => 'Program Head'
            //     ];
            // }
        }

        // ============================================================
        // SCENARIO 5: DIVISION CHAIR (CAS only) + PROGRAM HEAD
        // Display Program Heads AND Faculties
        // ============================================================
        if ($hasDivisionChair && $hasProgramHead && optional($casdivisionchair)->facCollege == "CAS") {
            // Get Program Heads in CAS (excluding self)
            $programHeadsInCAS = $allFacultyWithDesignations->filter(function($faculty) use ($userDept, $user) {
                return in_array('Program Head', $faculty->designations) &&
                       //in_array('CAS', $faculty->facColleges) &&
                       $faculty->facdept == $userDept &&
                       $faculty->id != $user->id;
            });

            if ($programHeadsInCAS->isNotEmpty()) {
                $sections[] = [
                    'title' => 'Program Heads (CAS)',
                    'data' => $programHeadsInCAS,
                    'evaluator' => 'Division Chair',
                    'disabled' => $disabledsubjdivchair,
                    'icon' => 'ti ti-user',
                    'role' => 'Division Chair'
                ];
            }

            $filteredFaculties = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $userMajor, $user) {
                // Split progCode by '-' to get parts
                $progParts = explode('-', $faculty->progcodename);
                
                // Get college, dept, major from progCode
                $progCollege = $progParts[0] ?? '';
                $progDept = $progParts[1] ?? '';
                $progMajor = $progParts[2] ?? '';
                
                // Base condition: match college and department
                $isMatch = $progCollege == $userCollege && $progDept == $userDept;
                
                // If user has a major specified, also match the major
                if (!empty($userMajor)) {
                    $isMatch = $isMatch && $progMajor == $userMajor;
                }
                
                // Exclude the user themselves
                return $isMatch && $faculty->id != $user->id;
            });
            
            // Remove duplicate faculty entries (one faculty might have multiple progCodes)
            $uniqueFaculties = $filteredFaculties->unique('id');
            
            if ($uniqueFaculties->isNotEmpty()) {
                // Create title based on available filters
                $title = 'Faculties';
                if (!empty($userMajor)) {
                    $title .= ' (' . $userMajor . ' Major)';
                } else {
                    $title .= ' (' . $userDept . ' Department - ' . $userCollege . ' College)';
                }
                
                $sections[] = [
                    'title' => $title,
                    'data' => $uniqueFaculties,
                    'evaluator' => 'Program Head',
                    'disabled' => $disabledsubj,
                    'icon' => 'ti ti-users',
                    'role' => 'Program Head',
                    'college' => $userCollege,
                    'department' => $userDept,
                    'major' => $userMajor
                ];
            }   

            // As Program Head: Evaluate Faculties
            // $facultiesInCollege = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $user) {
            //     return $faculty->faccollege == $userCollege &&
            //            $faculty->facdept == $userDept &&
            //            $faculty->id != $user->id;
            // });

            // if ($facultiesInCollege->isNotEmpty()) {
            //     $sections[] = [
            //         'title' => 'Faculties',
            //         'data' => $facultiesInCollege,
            //         'evaluator' => 'Program Head',
            //         'disabled' => $disabledsubj,
            //         'icon' => 'ti ti-users',
            //         'role' => 'Program Head'
            //     ];
            // }
        }

        // ============================================================
        // SCENARIO 6: DIVISION CHAIR ONLY (CAS)
        // Display Program Heads only
        // ============================================================
        if ($hasDivisionChair && !$hasProgramHead && optional($casdivisionchair)->facCollege == "CAS") {
            // Get Program Heads in CAS (excluding self)
            $programHeadsInCAS = $allFacultyWithDesignations->filter(function($faculty) use ($userDept, $user) {
                return in_array('Program Head', $faculty->designations) &&
                       //in_array('CAS', $faculty->facColleges) &&
                       $faculty->facdept == $userDept &&
                       $faculty->id != $user->id;
            });

            if ($programHeadsInCAS->isNotEmpty()) {
                $sections[] = [
                    'title' => 'Program Heads (CAS)',
                    'data' => $programHeadsInCAS,
                    'evaluator' => 'Division Chair',
                    'disabled' => $disabledsubjdivchair,
                    'icon' => 'ti ti-user',
                    'role' => 'Division Chair'
                ];
            } else {
                // No Program Heads found, display all faculty under this department
                $facultiesInCollege = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $user) {
                    $match = $faculty->faccollege == $userCollege &&
                            $faculty->facdept == $userDept &&
                            $faculty->id != $user->id;
                    return $match;
                });  

                if ($facultiesInCollege->isNotEmpty()) {
                    $sections[] = [
                        'title' => 'Faculty Members (' . $userDept . ')',
                        'data' => $facultiesInCollege,
                        'evaluator' => 'Division Chair',
                        'disabled' => $disabledsubjdivchair,
                        'icon' => 'ti ti-users',
                        'role' => 'Division Chair'
                    ];
                }
            }   
        }

        // ============================================================
        // SCENARIO 7: PROGRAM HEAD ONLY
        // Display all Faculty in their college/department
        // ============================================================
        if ($hasProgramHead && !$hasDeanInstruction && !$hasDean && !$hasDivisionChair && empty($user->deptmajor)) {

            $filteredFaculties = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $userMajor, $user) {
                // Split progCode by '-' to get parts
                $progParts = explode('-', $faculty->progcodename);
                
                // Get college, dept, major from progCode
                $progCollege = $progParts[0] ?? '';
                $progDept = $progParts[1] ?? '';
                $progMajor = $progParts[2] ?? '';
                
                // Base condition: match college and department
                $isMatch = $progCollege == $userCollege && $progDept == $userDept;
                
                // If user has a major specified, also match the major
                if (!empty($userMajor)) {
                    $isMatch = $isMatch && $progMajor == $userMajor;
                }
                
                // Exclude the user themselves
                return $isMatch && $faculty->id != $user->id;
            });
            
            // Remove duplicate faculty entries (one faculty might have multiple progCodes)
            $uniqueFaculties = $filteredFaculties->unique('id');
            
            if ($uniqueFaculties->isNotEmpty()) {
                // Create title based on available filters
                $title = 'Faculties';
                if (!empty($userMajor)) {
                    $title .= ' (' . $userMajor . ' Major)';
                } else {
                    $title .= ' (' . $userDept . ' Department - ' . $userCollege . ' College)';
                }
                
                $sections[] = [
                    'title' => $title,
                    'data' => $uniqueFaculties,
                    'evaluator' => 'Program Head',
                    'disabled' => $disabledsubj,
                    'icon' => 'ti ti-users',
                    'role' => 'Program Head',
                    'college' => $userCollege,
                    'department' => $userDept,
                    'major' => $userMajor
                ];
            }   
            // Get faculties in the Program Head's college and department (excluding self)
            // $facultiesInCollege = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $user) {
            //     return $faculty->faccollege == $userCollege &&
            //            $faculty->facdept == $userDept &&
            //            $faculty->id != $user->id;
            // });

            // Get other Program Heads (excluding self) in the college
            // $otherProgramHeads = $allFacultyWithDesignations->filter(function($faculty) use ($user, $userCollege) {
            //     return $faculty->id != $user->id && 
            //            in_array('Program Head', $faculty->designations) &&
            //            in_array($userCollege, $faculty->facColleges);
            // });

            // if ($otherProgramHeads->isNotEmpty()) {
            //     $sections[] = [
            //         'title' => 'Program Heads',
            //         'data' => $otherProgramHeads,
            //         'evaluator' => 'Program Head',
            //         'disabled' => $disabledsubj,
            //         'icon' => 'ti ti-user',
            //         'role' => 'Program Head'
            //     ];
            // }

            // if ($facultiesInCollege->isNotEmpty()) {
            //     $sections[] = [
            //         'title' => 'Faculties',
            //         'data' => $facultiesInCollege,
            //         'evaluator' => 'Program Head',
            //         'disabled' => $disabledsubj,
            //         'icon' => 'ti ti-users',
            //         'role' => 'Program Head'
            //     ];
            // }
        }

        // ============================================================
        // SCENARIO 8: PROGRAM HEAD WITH DEPTMAJOR
        // Display all Faculty in their college/department and same major
        // ============================================================
        if ($hasProgramHead && !$hasDeanInstruction && !$hasDean && !$hasDivisionChair && !empty($user->deptmajor)) {
            
            // Get faculties in the Program Head's college, department, and major (excluding self)
            $facultiesMajorInCollege = $regularFaculties->filter(function($faculty) use ($userCollege, $userDept, $userMajor, $user) {
                return $faculty->faccollege == $userCollege &&
                    $faculty->facdept == $userDept &&
                    $faculty->deptmajor == $userMajor &&
                    $faculty->id != $user->id;
            });

            if ($facultiesMajorInCollege->isNotEmpty()) {
                $sections[] = [
                    'title' => 'Faculties (' . $userMajor . ' Major)',
                    'data' => $facultiesMajorInCollege,
                    'evaluator' => 'Program Head',
                    'disabled' => $disabledsubj,
                    'icon' => 'ti ti-users',
                    'role' => 'Program Head'
                ];
            }
        }

        if ($hasVicePresident) {
            // Get all Deans who have no Program Head in their college
            $deansWithoutProgramHead = $allFacultyWithDesignations->filter(function($faculty) use ($allFacultyWithDesignations, $user) {
                if (!in_array('Dean', $faculty->designations)) {
                    return false;
                }
                
                // Check if this Dean's college has any Program Head
                $collegeHasProgramHead = $allFacultyWithDesignations->filter(function($f) use ($faculty) {
                    return in_array('Program Head', $f->designations) &&
                        in_array($faculty->facCollege, $f->facColleges) &&
                        $f->id != $faculty->id; // Exclude self
                })->isNotEmpty();
                
                return !$collegeHasProgramHead && $faculty->id != $user->id;
            });

            if ($deansWithoutProgramHead->isNotEmpty()) {
                $sections[] = [
                    'title' => 'Deans (No Program Head - VP Evaluates)',
                    'data' => $deansWithoutProgramHead,
                    'evaluator' => 'Vice President',
                    'disabled' => $disabledsubjcampusadmin,
                    'icon' => 'ti ti-user-check',
                    'role' => 'Vice President',
                    'reason' => 'No Program Head to evaluate Dean - Vice President evaluates',
                    'is_escalated' => true
                ];
            }
        }

        // ============================================================
        // SCENARIO F: Self-evaluation conflict for all admin roles
        // ONLY display this section when the user is a VICE PRESIDENT
        // ============================================================
        if ($hasVicePresident) {
            // Get all faculty with admin roles who are also Program Heads/Deans
            $adminWithTeachingConflict = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
                $hasAdminRole = in_array('Dean', $faculty->designations) || 
                            in_array('Dean of Instruction', $faculty->designations) ||
                            in_array('Vice President', $faculty->designations) ||
                            in_array('Chancellor', $faculty->designations);
                
                $hasTeachingRole = in_array('Program Head', $faculty->designations) ||
                                in_array('Faculty', $faculty->designations) ||
                                in_array('Professor', $faculty->designations);
                
                return $hasAdminRole && $hasTeachingRole && $faculty->id != $user->id;
            });

            if ($adminWithTeachingConflict->isNotEmpty()) {
                // Group by campus to show separately
                $groupedByCampus = $adminWithTeachingConflict->groupBy('campus');
                
                foreach ($groupedByCampus as $campus => $facultyList) {
                    $sections[] = [
                        'title' => 'Faculty with Teaching Roles (' . $campus . ' Campus - VP Evaluates)',
                        'data' => $facultyList,
                        'evaluator' => 'Vice President',
                        'disabled' => $disabledsubjcampusadmin,
                        'icon' => 'ti ti-user-cog',
                        'role' => 'Vice President',
                        'reason' => 'Admin also serves as evaluator - Vice President evaluates teaching',
                        'is_escalated' => true,
                        'is_teaching_evaluation' => true,
                        'campus' => $campus
                    ];
                }
            }

            // ============================================================
            // SCENARIO G: CROSS-CAMPUS CONFLICT
            // ONLY display this section when the user is a VICE PRESIDENT
            // ============================================================
            // Get all faculty who have designations across different campuses
            $facultyWithCrossCampusDesignations = $allFacultyWithDesignations->groupBy('id')->filter(function($facultyGroup) {
                // Check if this faculty has designations in different campuses
                $campuses = $facultyGroup->pluck('campus')->unique();
                return $campuses->count() > 1;
            })->keys();

            if ($facultyWithCrossCampusDesignations->isNotEmpty()) {
                // Get the full details of these faculty
                $crossCampusFaculty = $allFacultyWithDesignations->filter(function($faculty) use ($facultyWithCrossCampusDesignations) {
                    return in_array($faculty->id, $facultyWithCrossCampusDesignations->toArray()) &&
                        $faculty->id != $user->id;
                })->groupBy('id');

                foreach ($crossCampusFaculty as $facultyId => $designations) {
                    $first = $designations->first();
                    $campuses = $designations->pluck('campus')->unique()->implode(' → ');
                    $roles = $designations->pluck('designation')->unique()->implode(' / ');
                    
                    // Check if this person is a Dean of Instruction in one campus
                    $isDeanInstruction = $designations->contains('designation', 'Dean of Instruction');
                    $isProgramHead = $designations->contains('designation', 'Program Head');
                    $isDean = $designations->contains('designation', 'Dean');
                    
                    // If they have Dean of Instruction + Program Head across campuses
                    if ($isDeanInstruction && $isProgramHead) {
                        $sections[] = [
                            'title' => 'Cross-Campus Conflict: ' . $first->fname . ' ' . $first->lname,
                            'data' => collect([$first]),
                            'evaluator' => 'Vice President',
                            'disabled' => $disabledsubjcampusadmin,
                            'icon' => 'ti ti-user-exclamation',
                            'role' => 'Vice President',
                            'reason' => 'Dean of Instruction in one campus AND Program Head in another - VP evaluates (Campuses: ' . $campuses . ')',
                            'is_escalated' => true,
                            'is_cross_campus' => true,
                            'campuses' => $campuses,
                            'roles' => $roles
                        ];
                    }
                    
                    // If they have Dean + Program Head across campuses
                    if ($isDean && $isProgramHead) {
                        $sections[] = [
                            'title' => 'Cross-Campus Conflict: ' . $first->fname . ' ' . $first->lname,
                            'data' => collect([$first]),
                            'evaluator' => 'Vice President',
                            'disabled' => $disabledsubjcampusadmin,
                            'icon' => 'ti ti-user-exclamation',
                            'role' => 'Vice President',
                            'reason' => 'Dean in one campus AND Program Head in another - VP evaluates (Campuses: ' . $campuses . ')',
                            'is_escalated' => true,
                            'is_cross_campus' => true,
                            'campuses' => $campuses,
                            'roles' => $roles
                        ];
                    }
                }
            }

            // ============================================================
            // SCENARIO H: DEAN OF INSTRUCTION with teaching role in ANY campus
            // ONLY display this section when the user is a VICE PRESIDENT
            // ============================================================
            // Get all Dean of Instruction who also have teaching roles
            $deansOfInstructionTeaching = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
                $isDeanInstruction = in_array('Dean of Instruction', $faculty->designations);
                $hasTeachingRole = in_array('Program Head', $faculty->designations) ||
                                in_array('Faculty', $faculty->designations) ||
                                in_array('Professor', $faculty->designations);
                
                return $isDeanInstruction && $hasTeachingRole && $faculty->id != $user->id;
            });

            if ($deansOfInstructionTeaching->isNotEmpty()) {
                $groupedByCampus = $deansOfInstructionTeaching->groupBy('campus');
                
                foreach ($groupedByCampus as $campus => $facultyList) {
                    $sections[] = [
                        'title' => 'Dean of Instruction (Teaching Role - ' . $campus . ' Campus)',
                        'data' => $facultyList,
                        'evaluator' => 'Vice President',
                        'disabled' => $disabledsubjcampusadmin,
                        'icon' => 'ti ti-user-graduate',
                        'role' => 'Vice President',
                        'reason' => 'Dean of Instruction also has teaching role - VP evaluates (teaching effectiveness only)',
                        'is_escalated' => true,
                        'is_teaching_evaluation' => true,
                        'campus' => $campus
                    ];
                }
            }

            // ============================================================
            // SCENARIO I: SPECIFIC - User has Dean of Instruction + Program Head
            // ONLY display this section when the user is a VICE PRESIDENT
            // ============================================================
            if ($hasDeanInstruction && $hasProgramHead) {
                // Check if the user's Dean of Instruction is in a different campus
                // than their Program Head designation
                $userDeanInstructionCampus = $userDesignations->where('designation', 'Dean of Instruction')->first()->campus ?? $user->campus;
                $userProgramHeadCampus = $userDesignations->where('designation', 'Program Head')->first()->campus ?? $user->campus;
                
                // Check if user's campuses are different
                $isCrossCampus = $userDeanInstructionCampus != $userProgramHeadCampus;
                
                // Get the user as a faculty member to be evaluated
                $userAsFaculty = $allFacultyWithDesignations->filter(function($faculty) use ($user) {
                    return $faculty->id == $user->id;
                });
                
                if ($userAsFaculty->isNotEmpty()) {
                    $title = 'Your Evaluation (Dean of Instruction + Program Head)';
                    if ($isCrossCampus) {
                        $title .= ' - Cross-Campus Conflict';
                    } else {
                        $title .= ' - Self-Conflict';
                    }
                    
                    $sections[] = [
                        'title' => $title,
                        'data' => $userAsFaculty,
                        'evaluator' => 'Vice President',
                        'disabled' => $disabledsubjcampusadmin,
                        'icon' => 'ti ti-user-check',
                        'role' => 'Vice President',
                        'reason' => $isCrossCampus 
                            ? 'Dean of Instruction (' . $userDeanInstructionCampus . ') AND Program Head (' . $userProgramHeadCampus . ') - VP evaluates'
                            : 'Dean of Instruction AND Program Head in same campus - VP evaluates (self-conflict)',
                        'is_escalated' => true,
                        'is_self' => true,
                        'is_cross_campus' => $isCrossCampus,
                        'campus_doi' => $userDeanInstructionCampus,
                        'campus_ph' => $userProgramHeadCampus
                    ];
                }
            }
        }

        // Filter out empty sections
        return array_filter($sections, function($section) {
            return $section['data']->isNotEmpty();
        });
    }

    public function supfacevalrate(Request $request)
    {
        $encryptedId = $request->query('id');
        $encryptedFacID = $request->query('qcefacID');
        $qcefacname = $request->query('qcefacname'); // NOT encrypted - plain text
        $encryptedEvaluator = $request->query('qceevaluator');
        
        // DECRYPT all encrypted values
        $subjsIDselected = EncryptionHelper::decryptUrl($encryptedId);
        $qcefacID = EncryptionHelper::decryptUrl($encryptedFacID);
        $qceevaluator = EncryptionHelper::decryptUrl($encryptedEvaluator);
        
        // If decryption fails, use the encrypted value as fallback or redirect with error
        if (!$subjsIDselected || !$qcefacID || !$qceevaluator) {
            return redirect()->route('supfaceval')->with('error', 'Invalid or tampered URL parameters.');
        }

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

        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        // Get all user designations for display
        $userDesignations = FacDesignation::where('fac_id', Auth::guard('faculty')->user()->id)
            ->where('schlyear', $currsemnow->qceschlyear)
            ->where('semester', $currsemnow->qcesemester)
            ->where('campus', Auth::guard('faculty')->user()->campus)
            ->pluck('designation')
            ->toArray();
    
        return view('grading.gradesheet.faculty.services.viewfaceval.subslistevalrate', compact('inst', 'ratingscale',  'currsem', 'question', 'facdetail', 'facDesignateRole', 'authfacdesig', 'qceevaluator', 'userDesignations', 'subjsIDselected'));
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
