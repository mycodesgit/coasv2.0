<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\SettingDB\ConfigureCurrent;

class StudStateAccntAssessmentController extends Controller
{
    public function stateaccntpersem()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('assessment.assessreports.statementaccnt', compact('sy'));
    }

    public function stateaccntpersum()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('assessment.assessreports.statementaccntsum', compact('sy'));
    }

    public function stateaccntpersum_search(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('assessment.assessreports.statementaccntsum_search', compact('sy'));
    }

    public function getstateaccntpersum_search(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $category = $request->query('category');
        $campus = Auth::guard('web')->user()->campus;

        $query = StudEnrolmentHistory::join('students', 'program_en_history.studentID', 'students.stud_id')
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
                        ->groupBy('program_en_history.studentID');

                        if ($category == '1') {
                            $query->where('program_en_history.studentID', 'NOT LIKE', '%-G');
                            $query->where('coasv2_db_assessment.student_appraisal.studID', 'NOT LIKE', '%-G');
                        } elseif ($category == '2') {
                            $query->where('program_en_history.studentID', 'LIKE', '%-G');
                            $query->where('coasv2_db_assessment.student_appraisal.studID', 'LIKE', '%-G');
                        } elseif ($category == '3') {
                            $query->where('program_en_history.studentID', '!=', '');
                            $query->where('coasv2_db_assessment.student_appraisal.studID', '!=', '');
                        }

                        $data = $query->get();

        return response()->json(['data' => $data]);
    }
}

