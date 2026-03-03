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

class GradingFacultyServicesController extends Controller
{
    public function index()
    {
        return view('grading.gradesheet.faculty.services.index');
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
}
