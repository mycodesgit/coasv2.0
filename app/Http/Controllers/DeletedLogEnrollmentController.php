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

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

use App\Models\ScholarshipDB\Scholar;

use App\Models\AssessmentDB\StudentFee;
use App\Models\AssessmentDB\StudentAppraisal;

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
    
        $data = DeleteEnrollmentLogs::join('students', 'delete_enrollment_log', '=', 'students.stud_id')
        ->select('students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.gender', 'delete_enrollment_logs.*', 'delete_enrollment_logs.created_at as delcrt')
                ->where('delMC', '=', $campus)
                ->where('delschlyear', '=', $schlyear)
                ->where('delsemester', '=', $semester)
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
        ->select('students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.gender', 'studhislog.*', 'studhislog.created_at as upcrt')
                ->where('studhislog.campus', '=', $campus)
                ->where('studhislog.schlyear', '=', $schlyear)
                ->where('studhislog.semester', '=', $semester)
                ->orderBy('studhislog.created_at', 'DESC')
                ->get();

        return response()->json(['data' => $data]);
    }
}
