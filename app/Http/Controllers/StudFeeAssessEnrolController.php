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
use Illuminate\Support\Facades\Cache;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Traits\PendingAppraisalAssessmentCountTrait;

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
use App\Models\SettingDB\QueueCounter;
use App\Models\SettingDB\QueueCustomer;
use App\Models\SettingDB\QueueMode;

class StudFeeAssessEnrolController extends Controller
{
    use PendingAppraisalAssessmentCountTrait;

    public function index()
    {
        $pendCount = $this->getPendingAllCount();

        $data = [
            'pendCount' => $pendCount, 
        ];

        if (request()->ajax()) {
            return response()->json([
                'pendCount' => $pendCount, 
            ]);
        }
        
        return view('assessment.enrlmentappcheck.checkassess', compact('data'));
    }
    
    public function show()
    {
        $campus = Auth::guard('web')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $sy = ConfigureCurrent::where('set_status', 3)
            ->first(['schlyear', 'semester']);

        $student = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->select(
                'students.fname', 
                'students.lname', 
                'students.mname', 
                'students.ext', 
                'program_en_history.*', 
                'program_en_history.created_at as created_ats', 
                'coasv2_db_schedule.programs.progAcronym'
            )
            ->where('program_en_history.schlyear', $sy->schlyear ?? '')
            ->where('program_en_history.semester', $sy->semester ?? '')
            //->whereRaw("SUBSTRING_INDEX(program_en_history.progCod, '-', 1) = ?", [$dept])
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('program_en_history.campus', 'LIKE', "$campus");
                }
            })
            ->where('program_en_history.status', 4)
            ->orderBy('program_en_history.created_at', 'asc')
            ->get();

        return response()->json(['data' => $student], 200);
    }
}
