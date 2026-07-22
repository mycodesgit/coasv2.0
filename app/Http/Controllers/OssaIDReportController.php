<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
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
use App\Models\EnrollmentDB\StudentRFID;

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
use App\Models\SettingDB\Region;
use App\Models\SettingDB\Province;
use App\Models\SettingDB\City;
use App\Models\SettingDB\Barangay;
use App\Models\SettingDB\ButtonAccess;

class OssaIDReportController extends Controller
{
    public function index(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('ossas.report.idissuance', compact('sy'));
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

        return view('ossas.report.idissuance_searchresult', compact('sy'));
    }
    
    public function show(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');  
        
        $data = StudentRFID::join('students', 'studentrfidcard.stdntid', '=', 'students.stud_id')
            ->select(
                'studentrfidcard.stdntid',
                'students.lname',
                'students.fname',
                'students.mname',
                'students.ext',
                'studentrfidcard.stdntrfid',
                'studentrfidcard.contactperson',
                'studentrfidcard.contactpersonno',
                'studentrfidcard.created_at as issuance_date'
            )
            ->where('studentrfidcard.campus', Auth::guard('web')->user()->campus)
            ->where('studentrfidcard.schlyear', $schlyear)
            ->where('studentrfidcard.semester', $semester)
            ->orderBy('studentrfidcard.created_at', 'desc')
            ->get();

        return response()->json(['data' => $data]);
    }
}
