<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\UniqueStudentID;
use Illuminate\Support\Facades\Log;

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
use App\Models\EnrollmentDB\DeleteEnrollmentLogs;
use App\Models\EnrollmentDB\StudHisLog;
use App\Models\EnrollmentDB\StudSubLog;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

use App\Models\ScholarshipDB\Scholar;

use App\Models\AssessmentDB\StudentFee;
use App\Models\AssessmentDB\StudentAppraisal;
use App\Models\AssessmentDB\StudPayment;

use App\Models\SettingDB\ConfigureCurrent;

class DeletedLogEnrollmentController extends Controller
{
    public function delenrlmntlogsRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.enrollogs.list_logs', compact('sy'));
    }

    public function search_delenrlmntlogsRead(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
            
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus'); 

        return view('enrollment.reports.enrollogs.listsearch_logs', compact('sy'));
    }

    public function getdelenrlmntlogsRead(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');
    
        $data = DeleteEnrollmentLogs::join('students', 'delete_enrollment_logs', '=', 'students.stud_id')
                ->select('students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.gender', 'delete_enrollment_logs.*', 'delete_enrollment_logs.created_at as delcrt')
                ->where('delete_enrollment_logs.delMC', '=', $campus)
                ->where('delete_enrollment_logs.delschlyear', '=', $schlyear)
                ->where('delete_enrollment_logs.delsemester', '=', $semester)
                ->orderBy('delete_enrollment_logs.created_at', 'DESC')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function updateEnrlmntlogsRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.enrollogs.listupdate_enrollogs', compact('sy'));
    }

    public function search_uptadeEnrlmntlogsRead(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
            
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus'); 

        return view('enrollment.reports.enrollogs.listupdatesearch_enrollogs', compact('sy'));
    }

    public function getuptadeenrlmntlogsRead(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');
    
        $data = StudHisLog::join('students', 'studhislog.studentID', '=', 'students.stud_id')
                ->select('students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.gender', 'students.stud_id', 'studhislog.encode', 'studhislog.*', 'studhislog.created_at as upcrt')
                ->where('studhislog.campus', '=', $campus)
                ->where('studhislog.schlyear', '=', $schlyear)
                ->where('studhislog.semester', '=', $semester)
                ->orderBy('studhislog.created_at', 'DESC')
                ->groupBy('students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.gender', 'studhislog.studentID')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function studrfprintlog(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');

        $student = StudHisLog::join('students', 'studhislog.studentID', '=', 'students.stud_id')
                    ->join('coasv2_db_scholarship.scholarship', 'studhislog.studSch', '=', 'coasv2_db_scholarship.scholarship.id')
                    ->leftJoin('coasv2_db_schedule.programs', 'studhislog.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->select('students.*', 'studhislog.*', 'coasv2_db_scholarship.scholarship.*', 'studhislog.updated_at as updated_ats', 'coasv2_db_schedule.programs.progAcronym')
                    ->where('studhislog.schlyear',  $schlyear)
                    ->where('studhislog.semester',  $semester)
                    ->where('studhislog.campus',  $campus)
                    ->where('students.campus',  $campus)
                    ->where('studhislog.studentID', $stud_id)
                    ->get()
                    ->groupBy('encode');


        $studsub = StudSubLog::leftJoin('coasv2_db_schedule.sub_offered', 'studsublog.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studsublog.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('coasv2_db_schedule.sub_offered.schlyear',  $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester',  $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus',  $campus)
                    ->where('studsublog.studID', $stud_id)
                    ->orderBy('coasv2_db_schedule.sub_offered.subCode', 'ASC')
                    ->get()
                    ->groupBy('encode');

        $studfees = StudentAppraisal::select('student_appraisal.*')
                    ->where('student_appraisal.schlyear',  $schlyear)
                    ->where('student_appraisal.semester',  $semester)
                    ->where('student_appraisal.campus',  $campus)
                    ->where('student_appraisal.studID', $stud_id)
                    ->orderBy('student_appraisal.account', 'ASC')
                    ->get();

        $studor = StudPayment::select('studpayment.*')
                    ->where('studpayment.studID', $stud_id)
                    ->where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->get();

        $data = [
            'student' => $student,
            'studsub' => $studsub,
            'studfees' => $studfees,
            'studor' => $studor,
        ];
        
        $pdf = PDF::loadView('enrollment.reports.enrollogs.pdflogrf', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
}
