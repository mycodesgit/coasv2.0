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

class EnStudEnrolledController extends Controller
{
    public function studenrollRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.studenrolled.list_studenroll', compact('sy'));
    }

    public function search_studenrollRead(Request $request)
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

        return view('enrollment.reports.studenrolled.listsearch_studenroll', compact('sy'));
    }

    public function getsearchstudenrollRead(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');
        $campusArray = array_map('trim', explode(',', $campus));
    
        $data = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->leftJoin('coasv2_db_admission.ad_applicant_admission', 'students.app_id', '=', 'coasv2_db_admission.ad_applicant_admission.id')
                ->select('students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.address', 'students.brgy', 'students.city', 'students.province', 'students.region', 'students.zcode', 'program_en_history.progCod', 'program_en_history.studentID', 'program_en_history.studYear', 'program_en_history.studSec', 'program_en_history.schlyear', 'program_en_history.semester', 'coasv2_db_schedule.programs.progAcronym', 'coasv2_db_schedule.programs.progName', 'coasv2_db_admission.ad_applicant_admission.lstsch_attended', 'coasv2_db_admission.ad_applicant_admission.suc_lst_attended')
                // ->where('program_en_history.campus', '=', $campus)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('program_en_history.campus', 'LIKE', "%$campus%");
                    }
                })
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('coasv2_db_admission.ad_applicant_admission.campus', 'LIKE', "%$campus%");
                    }
                })
                ->where('students.stud_id', 'NOT LIKE', '%-G')
                ->where('program_en_history.schlyear', '=', $schlyear)
                ->where('program_en_history.semester', '=', $semester)
                ->get();

        return response()->json(['data' => $data]);
    }
}
