<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\EnrollmentDB\StudEnrolmentHistory;

class StudStateAccntAssessmentController extends Controller
{
    public function stateaccntpersem()
    {
        return view('assessment.assessreports.statementaccnt');
    }

    public function stateaccntpersum()
    {
        return view('assessment.assessreports.statementaccntsum');
    }

    public function stateaccntpersum_search(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $sumstudfees = StudEnrolmentHistory::join('students', 'program_en_history.studentID', 'students.stud_id')
                        ->join('coasv2_db_assessment.student_appraisal', 'program_en_history.studentID', 'coasv2_db_assessment.student_appraisal.studID')
                        ->select(
                            'program_en_history.studentID',
                            'students.lname',
                            'students.fname',
                            'program_en_history.schlyear',
                            'program_en_history.semester',
                            DB::raw('SUM(coasv2_db_assessment.student_appraisal.amount) as totalamount')
                        )
                        ->where('program_en_history.schlyear', $schlyear)
                        ->where('program_en_history.semester', $semester)
                        ->where('program_en_history.campus', $campus)
                        ->where('coasv2_db_assessment.student_appraisal.schlyear', $schlyear)
                        ->where('coasv2_db_assessment.student_appraisal.semester', $semester)
                        ->where('coasv2_db_assessment.student_appraisal.campus', $campus)
                        ->groupBy('program_en_history.studentID')
                        ->limit(10)
                        ->get();


        return view('assessment.assessreports.statementaccntsum_search', compact('sumstudfees'));
    }
}

