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

        return view('enrollment.reports.enrollogs.listgradesearch_encode', compact('sy'));
    }

    public function getsearchEncode_gradeRead(Request $request)
    {
            
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');

        $data = EncodedGrade::join('studgrades', 'studgrades_logs.grdeprimID', '=', 'studgrades.id')
                ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
                ->join('students', 'studgrades_logs.studsID', '=', 'students.stud_id')
                ->leftJoin('coasv2_db_schedule.subjects as s', 'so.subCode', '=', 's.sub_code')
                ->select('so.*', 'studgrades_logs.*', 'studgrades.*', 'studgrades.id as sgid', 'studgrades.status as gstat', 'students.*', 's.*')
                ->where('so.schlyear', $schlyear)
                ->where('so.semester', $semester)
                ->where('so.campus', $campus)
                ->get();

        return response()->json(['data' => $data]);
    }
}
