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


class GradingFacultyStudAttendanceController extends Controller
{
    public function index()
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
            
        return view('grading.gradesheet.faculty.attendance', compact('sy', 'authfacdesig'));
    }

    public function store(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $semester = $request->query('semester');
        $schlyear = $request->query('schlyear');
        $facID = Auth::guard('faculty')->user()->id;
        $campus = Auth::guard('faculty')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $datafacsubprogen = SetClassSchedule::leftJoin('coasv2_db_schedule.faculty', 'coasv2_db_schedule.scheduleclass.faculty_id', '=', 'coasv2_db_schedule.faculty.id')
                    ->leftJoin('coasv2_db_schedule.sub_offered', 'coasv2_db_schedule.scheduleclass.subject_id', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select(
                        'coasv2_db_schedule.subjects.sub_name',
                        'coasv2_db_schedule.sub_offered.id as subofferedid',
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
            ->where('coasv2_db_schedule.scheduleclass.semester', $semester)
            ->where('coasv2_db_schedule.scheduleclass.schlyear', $schlyear)
            ->where('coasv2_db_schedule.scheduleclass.faculty_id', Auth::guard('faculty')->user()->id)
            ->groupBy('coasv2_db_schedule.scheduleclass.subject_id')
            ->get();

        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];
            
        return view('grading.gradesheet.faculty.attendance_search', compact('sy', 'datafacsubprogen', 'authfacdesig'));
    }

    public function attendance_searchfacpdfpage(Request $request)
    {
        $semester = $request->query('semester');
        $schlyear = $request->query('schlyear');
        $facID = Auth::guard('faculty')->user()->id;
        $campus = Auth::guard('faculty')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $datafacsubprogen = SetClassSchedule::leftJoin('coasv2_db_schedule.faculty', 'coasv2_db_schedule.scheduleclass.faculty_id', '=', 'coasv2_db_schedule.faculty.id')
                    ->leftJoin('coasv2_db_schedule.sub_offered', 'coasv2_db_schedule.scheduleclass.subject_id', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select(
                        'coasv2_db_schedule.subjects.sub_name',
                        'coasv2_db_schedule.sub_offered.id as subofferedid',
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
            ->where('coasv2_db_schedule.scheduleclass.semester', $semester)
            ->where('coasv2_db_schedule.scheduleclass.schlyear', $schlyear)
            ->where('coasv2_db_schedule.scheduleclass.faculty_id', Auth::guard('faculty')->user()->id)
            ->groupBy('coasv2_db_schedule.scheduleclass.subject_id')
            ->get();

        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];
            
        return view('grading.gradesheet.faculty.attendance_searchpdf', compact('datafacsubprogen', 'authfacdesig'));
    }

    public function studsubjectsReadPDFfacattendance(Request $request)
    {
        $id = $request->id;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('faculty')->user()->campus;


        $substudnowviewpdf = Grade::select('so.*', 'studgrades.*', 'studgrades.id as sgid', 'studgrades.status as gstat', 'students.*', 's.*')
                ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
                ->join('students', 'studgrades.studID', '=', 'students.stud_id')
                ->leftJoin('coasv2_db_schedule.sub_offered as so2', 'studgrades.subjID', '=', 'so2.id')
                ->leftJoin('coasv2_db_schedule.subjects as s', 'so2.subCode', '=', 's.sub_code')
                ->where('so.schlyear', $schlyear)
                ->where('so.semester', $semester)
                ->where('studgrades.subjID', $id)
                ->orderBy('students.lname', 'ASC')
                ->get();
        $data = [
            'substudnowviewpdf' => $substudnowviewpdf,
        ];

        $pdf = PDF::loadView('enrollment.reports.studentsub.pdf.attendancestud', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
}
