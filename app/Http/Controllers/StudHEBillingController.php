<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\Student;

use App\Models\AssessmentDB\AccountAppraisal;
use App\Models\AssessmentDB\StudentAppraisal;
use App\Models\AssessmentDB\StudPayment;

use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\SubjectOffered;

use App\Models\SettingDB\ConfigureCurrent;

class StudHEBillingController extends Controller
{
    public function hebillingRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('assessment.assessreports.hebilling', compact('sy'));
    }

    public function hebillingRead_search(Request $request)
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
        $campus = Auth::guard('web')->user()->campus;

        $campusArray = array_map('trim', explode(',', $campus));

        // $studfeesbill = StudentAppraisal::join('coasv2_db_enrollment.students', 'student_appraisal.studID', '=', 'coasv2_db_enrollment.students.stud_id')
        //         ->join('coasv2_db_enrollment.program_en_history', 'student_appraisal.studID', '=', 'coasv2_db_enrollment.program_en_history.studentID')
        //         ->join('coasv2_db_schedule.programs', 'coasv2_db_enrollment.program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
        //         ->where('student_appraisal.schlyear',  $schlyear)
        //         ->where('student_appraisal.semester',  $semester)
        //         ->where('student_appraisal.campus',  $campus)
        //         ->where('coasv2_db_enrollment.program_en_history.schlyear',  $schlyear)
        //         ->where('coasv2_db_enrollment.program_en_history.semester',  $semester)
        //         ->where('coasv2_db_enrollment.program_en_history.campus',  $campus)
        //         ->where('student_appraisal.studID', 'NOT LIKE', '%-G')
        //         ->select('student_appraisal.*', 'coasv2_db_schedule.programs.progAcronym', 'coasv2_db_enrollment.students.lname', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.mname')
        //         ->orderBy('student_appraisal.account', 'ASC')
        //         ->get();

        $studfeesbill = StudEnrolmentHistory::join('students', 'program_en_history.studentID', 'students.stud_id')
                        ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                        ->leftJoin('coasv2_db_assessment.student_appraisal', 'program_en_history.studentID', '=', 'coasv2_db_assessment.student_appraisal.studID')
                        ->where('program_en_history.schlyear',  $schlyear)
                        ->where('program_en_history.semester',  $semester)
                        ->whereIn('program_en_history.status',  [2, 3])
                        // ->where('program_en_history.campus',  $campus)
                        ->where(function ($q) use ($campusArray) {
                            foreach ($campusArray as $campus) {
                                $q->orWhere('program_en_history.campus', 'LIKE', "$campus");
                            }
                        })
                        ->where('coasv2_db_assessment.student_appraisal.schlyear',  $schlyear)
                        ->where('coasv2_db_assessment.student_appraisal.semester',  $semester)
                        // ->where('coasv2_db_assessment.student_appraisal.campus',  $campus)
                        ->where(function ($q) use ($campusArray) {
                            foreach ($campusArray as $campus) {
                                $q->orWhere('coasv2_db_assessment.student_appraisal.campus', 'LIKE', "$campus");
                            }
                        })
                        ->where('program_en_history.studentID', 'NOT LIKE', '%-G')
                        //->where('program_en_history.studentID', 'NOT LIKE', '%-N')
                        ->select(
                            'program_en_history.studentID',
                            'students.lname',
                            'students.fname',
                            'students.mname',
                            'program_en_history.schlyear',
                            'program_en_history.semester',
                            'coasv2_db_schedule.programs.progAcronym',
                            'program_en_history.studYear',
                            'students.gender',
                            'students.email',
                            'students.contact',
                            'program_en_history.studUnit',
                            'coasv2_db_assessment.student_appraisal.account',
                            'coasv2_db_assessment.student_appraisal.amount'
                            //'coasv2_db_assessment.studpayment.amountpaid',
                            //DB::raw('SUM(coasv2_db_assessment.student_appraisal.amount) as totalamount')
                        )
                        ->orderBy('students.lname', 'ASC')
                        ->get();

        return view('assessment.assessreports.hebilling_listsearch', compact('sy', 'studfeesbill'));
    }
}
