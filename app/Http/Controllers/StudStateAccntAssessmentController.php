<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use PDF;
use Carbon\Carbon;

use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\Student;

use App\Models\AssessmentDB\AccountAppraisal;
use App\Models\AssessmentDB\StudentAppraisal;
use App\Models\AssessmentDB\StudPayment;

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

    public function stateaccntpersem_search(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $category = $request->query('category');
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('stud_id', $stud_id)->where('campus', $campus)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }
        $programEnHistory = StudEnrolmentHistory::join('coasv2_db_admission.users', 'program_en_history.postedBy', '=', 'coasv2_db_admission.users.id')
                ->where('program_en_history.studentID', $stud_id)
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', '=', $semester)
                ->where('program_en_history.campus', '=', $campus)
                ->select('program_en_history.*', 'coasv2_db_admission.users.lname', 'coasv2_db_admission.users.fname', 'coasv2_db_admission.users.id as uid')
                ->first(); 

        if (!$programEnHistory) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> not enrolled at this term or school year.');
        }

        $query = StudentAppraisal::join('coasv2_db_enrollment.students', 'student_appraisal.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                    ->where('student_appraisal.schlyear',  $schlyear)
                    ->where('student_appraisal.semester',  $semester)
                    ->where('student_appraisal.campus',  $campus)
                    ->where('student_appraisal.studID', $stud_id)
                    ->where('student_appraisal.studID', '=', $programEnHistory->studentID)
                    ->select('student_appraisal.*', 'coasv2_db_enrollment.students.lname', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.mname')
                    ->orderBy('student_appraisal.account', 'ASC');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('student_appraisal.studID', 'LIKE', '%-G')
                              ->orWhere('student_appraisal.studID', 'LIKE', '%-N');
                        });
                    }

                    $studfees = $query->get();

        $query = StudPayment::where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->where('studpayment.campus',  $campus)
                    ->where('studpayment.studID', $stud_id)
                    ->select('studpayment.*')
                    ->orderBy('studpayment.account', 'ASC');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('studpayment.studID', 'LIKE', '%-G')
                              ->orWhere('studpayment.studID', 'LIKE', '%-N');
                        });
                    }

                    $studpayment = $query->get();

        return view('assessment.assessreports.statementaccnt_search', compact('sy', 'studfees', 'studpayment'));
    }

    public function stateaccntpersem_searchpdf(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $category = $request->query('category');
        $campus = Auth::guard('web')->user()->campus;

        $query = Student::leftJoin('program_en_history', 'students.stud_id', '=', 'program_en_history.studentID')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->where('students.stud_id', $stud_id)
                    ->where('students.campus',  $campus)
                    ->select('students.lname', 'students.fname', 'students.mname', 'coasv2_db_schedule.programs.progAcronym');

                    if ($category == '2') {
                        $query->where('students.stud_id', 'LIKE', '%-G');
                    }

                    $studinfo = $query->get();

        $query = StudentAppraisal::join('coasv2_db_enrollment.students', 'student_appraisal.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                    ->where('student_appraisal.schlyear',  $schlyear)
                    ->where('student_appraisal.semester',  $semester)
                    ->where('student_appraisal.campus',  $campus)
                    ->where('student_appraisal.studID', $stud_id)
                    ->select('student_appraisal.*', 'coasv2_db_enrollment.students.lname', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.mname')
                    ->orderBy('student_appraisal.account', 'ASC');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('student_appraisal.studID', 'LIKE', '%-G')
                              ->orWhere('student_appraisal.studID', 'LIKE', '%-N');
                        });
                    }

                    $studfees = $query->get();

        $query = StudPayment::where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->where('studpayment.campus',  $campus)
                    ->where('studpayment.studID', $stud_id)
                    ->select('studpayment.*')
                    ->orderBy('studpayment.account', 'ASC');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('studpayment.studID', 'LIKE', '%-G')
                              ->orWhere('studpayment.studID', 'LIKE', '%-N');
                        });
                    }

                    $studpayment = $query->get();

        $data = [
            'studinfo' => $studinfo,
            'studfees' => $studfees,
            'studpayment' => $studpayment,
        ];

        $pdf = PDF::loadView('assessment.assessreports.reports.pdfpersemtemplate', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function stateaccntperstudent()
    {
        return view('assessment.assessreports.statementaccntstudent');
    }

    public function stateaccntperstudent_search(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('stud_id', $stud_id)->where('campus', $campus)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }

        $data = Student::join('program_en_history', 'students.stud_id', '=', 'program_en_history.studentID')
                ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->where('students.stud_id', $stud_id)
                ->select('students.*', 'program_en_history.progCod', 'coasv2_db_schedule.programs.progAcronym')
                ->groupBy('program_en_history.studentID', 'program_en_history.progCod', 'coasv2_db_schedule.programs.progAcronym')
                ->get();

        return view('assessment.assessreports.statementaccntstudentSearch',compact('data'));
    }

    public function stateaccntperstudent_searchpdf(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $campus = Auth::guard('web')->user()->campus;

        $query = Student::leftJoin('program_en_history', 'students.stud_id', '=', 'program_en_history.studentID')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->where('students.stud_id', $stud_id)
                    ->where('students.campus',  $campus)
                    ->select('students.lname', 'students.fname', 'students.mname', 'coasv2_db_schedule.programs.progAcronym');

                    if ($category == '2') {
                        $query->where('students.stud_id', 'LIKE', '%-G');
                    }

                    $studinfo = $query->get();

        $query = StudentAppraisal::join('coasv2_db_enrollment.students', 'student_appraisal.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                    ->where('student_appraisal.campus',  $campus)
                    ->where('student_appraisal.studID', $stud_id)
                    ->select('student_appraisal.*', 'coasv2_db_enrollment.students.lname', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.mname')
                    ->orderBy('student_appraisal.account', 'ASC');

                    if ($category == '2') {
                        $query->where('student_appraisal.studID', 'LIKE', '%-G');
                    }

                    $studfees = $query->get();

        $query = StudPayment::where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->where('studpayment.campus',  $campus)
                    ->where('studpayment.studID', $stud_id)
                    ->select('studpayment.*')
                    ->orderBy('studpayment.account', 'ASC');

                    if ($category == '2') {
                        $query->where('studpayment.studID', 'LIKE', '%-G');
                    }

                    $studpayment = $query->get();

        $data = [
            'studinfo' => $studinfo,
            'studfees' => $studfees,
            'studpayment' => $studpayment,
        ];

        $pdf = PDF::loadView('assessment.assessreports.reports.pdfpersemtemplate', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
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
                        ->rightJoin('coasv2_db_assessment.studpayment', 'program_en_history.studentID', '=', 'coasv2_db_assessment.studpayment.studID')
                        ->select(
                            'program_en_history.studentID',
                            'students.lname',
                            'students.fname',
                            'program_en_history.schlyear',
                            'program_en_history.semester',
                            'coasv2_db_assessment.studpayment.amountpaid',
                            DB::raw('SUM(coasv2_db_assessment.student_appraisal.amount) as totalamount')
                        )
                        ->where('program_en_history.schlyear', $schlyear)
                        ->where('program_en_history.semester', $semester)
                        ->where('program_en_history.campus', $campus)
                        ->where('coasv2_db_assessment.student_appraisal.schlyear', $schlyear)
                        ->where('coasv2_db_assessment.student_appraisal.semester', $semester)
                        ->where('coasv2_db_assessment.student_appraisal.campus', $campus)
                        ->where('coasv2_db_assessment.studpayment.schlyear', $schlyear)
                        ->where('coasv2_db_assessment.studpayment.semester', $semester)
                        ->where('coasv2_db_assessment.studpayment.campus', $campus)
                        ->groupBy('program_en_history.studentID');

                        // if ($category == '1') {
                        //     $query->where('program_en_history.studentID', 'NOT LIKE', '%-G');
                        //     $query->where('coasv2_db_assessment.student_appraisal.studID', 'NOT LIKE', '%-G');
                        // } elseif ($category == '2') {
                        //     $query->where('program_en_history.studentID', 'LIKE', '%-G');
                        //     $query->where('coasv2_db_assessment.student_appraisal.studID', 'LIKE', '%-G');
                        // } elseif ($category == '3') {
                        //     $query->where('program_en_history.studentID', '!=', '');
                        //     $query->where('coasv2_db_assessment.student_appraisal.studID', '!=', '');
                        // }

                        if ($category == '2') {
                            $query->where('program_en_history.studentID', 'LIKE', '%-G');
                            $query->where('coasv2_db_assessment.student_appraisal.studID', 'LIKE', '%-G');
                        }

                        $data = $query->get();

        return response()->json(['data' => $data]);
    }
}

