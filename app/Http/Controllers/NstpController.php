<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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
use App\Models\SettingDB\ButtonAccess;

class NstpController extends Controller
{
    public function index()
    {
        return view('nstpcwtsltsrotc.index');
    }

    public function reports_nstp()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('nstpcwtsltsrotc.reports.listgen', compact('sy'));
    }

    public function reports_nstpresult(Request $request)
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
        if(Auth::guard('web')->user()->role == 0 || Auth::guard('web')->user()->lname == 'Arlos') {
            $campus = $request->query('campus');    
        } else {
            $campus = Auth::guard('web')->user()->campus;
        }

        $campusArray = array_map('trim', explode(',', $campus));

        $data = StudEnrolmentHistory::leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', $semester)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('program_en_history.campus', 'LIKE', "$campus");
                    }
                })
                ->where('program_en_history.status', 2)
                ->select(
                    'students.lname', 
                    'students.fname', 
                    'students.ext', 
                    'students.mname', 
                    'students.mname', 
                    'students.bday', 
                    'students.brgy', 
                    'students.city', 
                    'students.province', 
                    'program_en_history.studlevel', 
                    'students.course', 
                    'students.email', 
                    'students.contact', 
                    'program_en_history.id', 
                    'program_en_history.schlyear', 
                    'program_en_history.semester')
                ->get();

        return view('nstpcwtsltsrotc.reports.listgenresult', compact('sy', 'data'));
    }

    public function getreportsnstpresult(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        if(Auth::guard('web')->user()->role == 0) {
            $campus = $request->query('campus');    
        } else {
            $campus = Auth::guard('web')->user()->campus;
        }

        $campusArray = array_map('trim', explode(',', $campus));

        $data = StudEnrolmentHistory::leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', $semester)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('program_en_history.campus', 'LIKE', "$campus");
                    }
                })
                ->where('program_en_history.status', 2)
                ->select(
                    'students.lname', 
                    'students.fname', 
                    'students.ext', 
                    'students.mname', 
                    'students.mname', 
                    'students.bday', 
                    'students.brgy', 
                    'students.city', 
                    'students.province', 
                    'program_en_history.studlevel', 
                    'students.course', 
                    'students.email', 
                    'students.contact', 
                    'program_en_history.id', 
                    'program_en_history.schlyear', 
                    'program_en_history.semester')
                ->get();

        return response()->json(['data' => $data]);
    }
}
