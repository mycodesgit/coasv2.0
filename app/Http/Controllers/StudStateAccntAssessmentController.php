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
use App\Models\AssessmentDB\ORComments;

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

        $studAccntap = AccountAppraisal::whereIn('id', ['2', '7', '33', '42', '44', '49', '74', '76', '79', '85', '90', '91', '92', '93', '99', '118', '133', '134', '151', '152', '153', '154', '155', '156', '159', '161', '168'])
                    ->orderBy('account_name', 'ASC')
                    ->get();

        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $category = $request->query('category');
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('stud_id', $stud_id)
            // ->where(function ($query) {
            //     $query->where('stud_id', 'LIKE', '%-G')
            //           ->orWhere('stud_id', 'LIKE', '%-N');
            // })
            ->where('campus', $campus)
            ->first();

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
                    } elseif ($category == '1') {
                        $query->where(function($q) {
                            $q->where('student_appraisal.studID', 'NOT LIKE', '%-G');
                        });
                    }

                    $studfees = $query->get();

        $query = StudPayment::leftJoin('orcomments', 'studpayment.id', '=', 'orcomments.studpayID')
                    ->where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->where('studpayment.campus',  $campus)
                    ->where('studpayment.studID', $stud_id)
                    ->select('studpayment.*', 'orcomments.comments')
                    ->orderBy('studpayment.account', 'ASC');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('studpayment.studID', 'LIKE', '%-G')
                              ->orWhere('studpayment.studID', 'LIKE', '%-N');
                        });
                    } elseif ($category == '1') {
                        $query->where(function($q) {
                            $q->where('studpayment.studID', 'NOT LIKE', '%-G');
                        });
                    }

                    $studpayment = $query->get();

        return view('assessment.assessreports.statementaccnt_search', compact('sy', 'studAccntap', 'studfees', 'studpayment'));
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
                        $query->where(function($q) {
                            $q->where('students.stud_id', 'LIKE', '%-G')
                              ->orWhere('students.stud_id', 'LIKE', '%-N');
                        });
                    } elseif ($category == '1') {
                        $query->where(function($q) {
                            $q->where('students.stud_id', 'NOT LIKE', '%-G');
                        });
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
                    } elseif ($category == '1') {
                        $query->where(function($q) {
                            $q->where('student_appraisal.studID', 'NOT LIKE', '%-G');
                        });
                    }

                    $studfees = $query->get();

        $query = StudPayment::leftJoin('orcomments', 'studpayment.id', '=', 'orcomments.studpayID')
                    ->where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->where('studpayment.campus',  $campus)
                    ->where('studpayment.studID', $stud_id)
                    ->select('studpayment.*', 'orcomments.comments')
                    ->orderBy('studpayment.account', 'ASC');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('studpayment.studID', 'LIKE', '%-G')
                              ->orWhere('studpayment.studID', 'LIKE', '%-N');
                        });
                    } elseif ($category == '1') {
                        $query->where(function($q) {
                            $q->where('studpayment.studID', 'NOT LIKE', '%-G');
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

    public function stateaccntpersem_getsearch(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $category = $request->query('category');
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('stud_id', $stud_id)
            ->where('campus', $campus)
            ->first();

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
                    ->where('student_appraisal.studID', $programEnHistory->studentID)
                    ->select('student_appraisal.*', 'coasv2_db_enrollment.students.lname', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.mname')
                    ->orderBy('student_appraisal.account', 'ASC');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('student_appraisal.studID', 'LIKE', '%-G')
                              ->orWhere('student_appraisal.studID', 'LIKE', '%-N');
                        });
                    } elseif ($category == '1') {
                        $query->where(function($q) {
                            $q->where('student_appraisal.studID', 'NOT LIKE', '%-G');
                        });
                    }

                    $data = $query->get();

        return response()->json(['data' => $data]);
    }

    public function stateaccntpersem_getsearchCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'studID' => 'required',
                'schlyear' => 'required',
                'semester' => 'required',
                'dateAssess' => 'required',
                'fundID' => 'required',
                'account' => 'required',
                'amount' => 'required',
            ]);

            $stud_idName = $request->input('studID'); 
            $schlyearName = $request->input('schlyear'); 
            $semesterName = $request->input('semester'); 
            $fundIDName = $request->input('fundID'); 
            $accountName = $request->input('account'); 
            $amountName = $request->input('amount'); 
            
            $existingStudFees = StudentAppraisal::where('studID', $stud_idName)
                    ->where('schlyear', $schlyearName)
                    ->where('semester', $semesterName)
                    ->where('fundID', $fundIDName)
                    ->where('account', $accountName)
                    ->where('amount', $amountName)
                    ->first();

            if ($existingStudFees) {
                return response()->json(['error' => true, 'message' => 'Student Fees' .$accountName. 'already exists'], 404);
            }

            try {
                StudentAppraisal::create([
                    'studID' => $stud_idName,
                    'schlyear' => $schlyearName,
                    'semester' => $semesterName,
                    'dateAssess' => $request->input('dateAssess'),
                    'fundID' => $fundIDName,
                    'account' => $accountName,
                    'amount' => $amountName,
                    'campus' => Auth::guard('web')->user()->campus,
                    'postedBy' => Auth::guard('web')->user()->id,
                ]);

                return response()->json(['success' => true, 'message' => 'Student Fees stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Student Fees'], 404);
            }
        }
    }

    public function stateaccntperstudent()
    {
        return view('assessment.assessreports.statementaccntstudent');
    }

    public function stateaccntperstudentid_search(Request $request)
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

    public function stateaccntperstudentname_search(Request $request)
    {
        $lname = $request->query('lname');
        $fname = $request->query('fname');
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('lname', $lname)->where('fname', $fname)->where('campus', $campus)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student <strong>' . $lname . ' ' . $fname . '</strong> does not exist.');
        }

        $data = Student::join('program_en_history', 'students.stud_id', '=', 'program_en_history.studentID')
                ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->where('students.lname', $lname)
                ->where('students.fname', $fname)
                ->select('students.*', 'program_en_history.progCod', 'coasv2_db_schedule.programs.progAcronym')
                ->groupBy('program_en_history.studentID', 'program_en_history.progCod', 'coasv2_db_schedule.programs.progAcronym')
                ->get();

        return view('assessment.assessreports.statementaccntstudentSearch',compact('data'));
    }

    public function stateaccntperstudent_searchpdf(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $category = $request->query('category');
        $campus = Auth::guard('web')->user()->campus;

        $query = Student::leftJoin('program_en_history', 'students.stud_id', '=', 'program_en_history.studentID')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->where('students.stud_id', $stud_id)
                    ->where('students.campus',  $campus)
                    ->select('students.lname', 'students.fname', 'students.mname', 'coasv2_db_schedule.programs.progAcronym');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('students.stud_id', 'LIKE', '%-G')
                              ->orWhere('students.stud_id', 'LIKE', '%-N');
                        });
                    }

                    $studinfo = $query->get();

        $query = StudentAppraisal::join('coasv2_db_enrollment.students', 'student_appraisal.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                    ->where('student_appraisal.campus',  $campus)
                    ->where('student_appraisal.studID', $stud_id)
                    ->select('student_appraisal.*', 'coasv2_db_enrollment.students.lname', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.mname');
                    //->orderBy('student_appraisal.account', 'ASC');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('student_appraisal.studID', 'LIKE', '%-G')
                              ->orWhere('student_appraisal.studID', 'LIKE', '%-N');
                        });
                    }

                    $studfees = $query->get();

        $query = StudPayment::where('studpayment.campus',  $campus)
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

    public function stateaccntperstudentname_searchpdf(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $category = $request->query('category');
        $campus = Auth::guard('web')->user()->campus;

        $query = Student::leftJoin('program_en_history', 'students.stud_id', '=', 'program_en_history.studentID')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->where('students.stud_id', $stud_id)
                    ->where('students.campus',  $campus)
                    ->select('students.lname', 'students.fname', 'students.mname', 'coasv2_db_schedule.programs.progAcronym');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('students.stud_id', 'LIKE', '%-G')
                              ->orWhere('students.stud_id', 'LIKE', '%-N');
                        });
                    }

                    $studinfo = $query->get();

        $query = StudentAppraisal::join('coasv2_db_enrollment.students', 'student_appraisal.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                    ->where('student_appraisal.campus',  $campus)
                    ->where('student_appraisal.studID', $stud_id)
                    ->select('student_appraisal.*', 'coasv2_db_enrollment.students.lname', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.mname');
                    //->orderBy('student_appraisal.account', 'ASC');

                    if ($category == '2') {
                        $query->where(function($q) {
                            $q->where('student_appraisal.studID', 'LIKE', '%-G')
                              ->orWhere('student_appraisal.studID', 'LIKE', '%-N');
                        });
                    }

                    $studfees = $query->get();

        $query = StudPayment::where('studpayment.campus',  $campus)
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

