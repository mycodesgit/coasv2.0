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
}
