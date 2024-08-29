<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
use App\Models\EnrollmentDB\EncodedGrade;

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

class EnStudEncodeGradesLogController extends Controller
{
    public function searchEncode_grade()
    {   
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.enrollogs.listgrade_encode', compact('sy'));
    }

    public function searchEncode_gradeRead(Request $request)
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

        // $data = EncodedGrade::join('students', 'studgrades_logs.studsID', '=', 'students.stud_id')
        //         ->join('studgrades', 'studgrades_logs.grdeprimID', '=', 'studgrades.id')
        //         ->join('coasv2_db_schedule.sub_offered', 'studgrades_logs.subjctsID', '=', 'coasv2_db_schedule.sub_offered.id')
        //         ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
        //         ->select('studgrades_logs.*', 'studgrades.*', 'students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.gender', 'coasv2_db_schedule.subjects.sub_name', 'coasv2_db_schedule.sub_offered.subSec', 'coasv2_db_schedule.sub_offered.semester', 'coasv2_db_schedule.sub_offered.schlyear')
        //         ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyear)
        //         ->where('coasv2_db_schedule.sub_offered.semester', $semester)
        //         ->where('studgrades_logs.campus', $campus)
        //         ->get();

        return view('enrollment.reports.enrollogs.listgradesearch_encode', compact('sy'));
    }

    public function getsearchEncode_gradeRead(Request $request)
    {
            
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');

        $data = EncodedGrade::join('students', 'studgrades_logs.studsID', '=', 'students.stud_id')
                ->join('studgrades', 'studgrades_logs.grdeprimID', '=', 'studgrades.id')
                ->join('coasv2_db_schedule.sub_offered', 'studgrades_logs.subjctsID', '=', 'coasv2_db_schedule.sub_offered.id')
                ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                ->select('studgrades_logs.*', 'studgrades.*', 'students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.gender', 'coasv2_db_schedule.subjects.sub_name', 'coasv2_db_schedule.sub_offered.subSec', 'coasv2_db_schedule.sub_offered.semester', 'coasv2_db_schedule.sub_offered.schlyear')
                ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyear)
                ->where('coasv2_db_schedule.sub_offered.semester', $semester)
                ->where('studgrades_logs.campus', $campus)
                ->get();

        return response()->json(['data' => $data]);
    }
}
