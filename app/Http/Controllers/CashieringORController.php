<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use PDF;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\DeleteEnrollmentLogs;

use App\Models\AssessmentDB\Funds;
use App\Models\AssessmentDB\AccountCoa;
use App\Models\AssessmentDB\AccountAppraisal;
use App\Models\AssessmentDB\StudPayment;
use App\Models\AssessmentDB\ORComments;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\SettingDB\ConfigureCurrent;

class CashieringORController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->year;
        $userCampus = Auth::guard('web')->user()->campus;

        $schlyearactive = ConfigureCurrent::where('set_status', 2)->first()->schlyear;
        $schlyearactiveYear = explode('-', $schlyearactive)[0];
        $semesteractive = ConfigureCurrent::where('set_status', 2)->first()->semester;

        $collegesFirstSemester = College::join('coasv2_db_enrollment.program_en_history', function($join) {
                            $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
                        })
                        ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
                        ->where(function ($query) use ($userCampus) {
                            $campuses = explode(', ', $userCampus);
                            foreach ($campuses as $campus) {
                                $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                            }
                        })
                        ->where('coasv2_db_enrollment.program_en_history.semester', '=', 1)
                        ->where(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.schlyear, '-', 1)"), $schlyearactiveYear)
                        ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
                        ->orderBy('college_name', 'ASC')
                        ->select('college.*', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
                        ->groupBy('college.id')
                        ->get();

        $collegesSecondSemester = College::join('coasv2_db_enrollment.program_en_history', function($join) {
                            $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
                        })
                        ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
                        ->where(function ($query) use ($userCampus) {
                            $campuses = explode(', ', $userCampus);
                            foreach ($campuses as $campus) {
                                $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                            }
                        })
                        ->where('coasv2_db_enrollment.program_en_history.semester', '=', 2)
                        ->where(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.schlyear, '-', 1)"), $schlyearactiveYear)
                        ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
                        ->orderBy('college_name', 'ASC')
                        ->select('college.*', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
                        ->groupBy('college.id')
                        ->get();

        return view('cashier.index', compact('collegesFirstSemester', 'collegesSecondSemester', 'currentYear', 'previousYear'));
    }

    public function list_orRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('cashier.officialreceipt.list_or', compact('sy'));
    }

    public function listsearch_orRead(Request $request)
    {
        $stud_id = $request->stud_id;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
        $orno = $request->query('orno');

        // $student = Student::where('stud_id', $stud_id)->where('campus', $campus)->where('stud_id', 'LIKE', '%-G')->first();
        // if (!$student) {
        //     return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        // }

        $orstud = Student::where('stud_id', $stud_id)->select('fname', 'mname', 'lname')->get();
        $studfund = Funds::orderBy('id', 'DESC')->get();
        $studAccntap = AccountAppraisal::whereIn('id', ['2', '7', '33', '42', '44', '49', '74', '76', '79', '85', '90', '91', '92', '93', '99', '118', '133', '134', '151', '152', '153', '154', '155', '156', '159', '161'])
                    ->orderBy('account_name', 'ASC')
                    ->get();
        $dataprimidOR = StudPayment::where('orno', '=', $orno)
                ->where('schlyear', '=', $schlyear)
                ->where('semester', '=', $semester)
                ->select('id as studorprimID')
                ->first();

        return view('cashier.officialreceipt.listsearch_or', compact('orstud', 'studfund', 'studAccntap', 'dataprimidOR'));
    }

    public function getorpaymentRead(Request $request) 
    {
        $orno = $request->query('orno');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
    
        $data = StudPayment::where('orno', '=', $orno)
                ->where('schlyear', '=', $schlyear)
                ->where('semester', '=', $semester)
                ->get();

        return response()->json(['data' => $data]);
    }

    public function orCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'orno' => 'required',
                'studID' => 'required',
                'semester' => 'required',
                'schlyear' => 'required',
                'campus' => 'required',
                'datepaid' => 'required',
                'fund' => 'required',
                'account' => 'required',
                'amountpaid' => 'required',
            ]);

            $orno = $request->input('orno');
            $studID = $request->input('studID');
            $semester = $request->input('semester');
            $schlyear = $request->input('schlyear');
            $campus = $request->input('campus');
            $datepaid = $request->input('datepaid');

            // $studaccount = $request->input('account'); 
            // $existingStudFeeOR = StudPayment::where('account', $studaccount)
            //                 ->where('campus', $campus)
            //                 ->where('schlyear', $schlyear)
            //                 ->where('semester', $semester)
            //                 ->first();

            // if ($existingStudFeeOR) {
            //     return response()->json(['error' => true, 'message' => 'Account Name in Student Fee already exists'], 404);
            // }

            try {
                $studpayor = StudPayment::create([
                    'orno' => $request->input('orno'),
                    'studID' => $request->input('studID'),
                    'semester' => $request->input('semester'),
                    'schlyear' => $request->input('schlyear'),
                    'campus' => $request->input('campus'),
                    'fund' => $request->input('fund'),
                    'account' => $request->input('account'),
                    'amountpaid' => $request->input('amountpaid'),
                    'datepaid' => $request->input('datepaid'),
                    'postedBy' => Auth::guard('web')->user()->id,
                ]);

                return response()->json(['success' => true, 'message' => 'Payment stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Fund'], 404);
            }
        }
    }

    public function orCommentsCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'orno' => 'required',
                'studID' => 'required',
                'semester' => 'required',
                'schlyear' => 'required',
                'campus' => 'required',
                'datepaid' => 'required',
                'comments' => 'required',
            ]);

            $orno = $request->input('orno');
            $studID = $request->input('studID');
            $semester = $request->input('semester');
            $schlyear = $request->input('schlyear');
            $campus = $request->input('campus');
            $datepaid = $request->input('datepaid');

            $studaccountcomments = $request->input('comments'); 
            $existingStudFeeORcomment = ORComments::where('comments', $studaccountcomments)
                            ->where('studID', $studID)
                            ->where('campus', $campus)
                            ->where('schlyear', $schlyear)
                            ->where('semester', $semester)
                            ->first();

            if ($existingStudFeeORcomment) {
                return response()->json(['error' => true, 'message' => 'Comments for this Account is already exists'], 404);
            }

            try {
                ORComments::create([
                    'studpayID' => $request->input('studpayID'),
                    'orno' => $request->input('orno'),
                    'studID' => $request->input('studID'),
                    'semester' => $request->input('semester'),
                    'schlyear' => $request->input('schlyear'),
                    'campus' => $request->input('campus'),
                    'datepaid' => $request->input('datepaid'),
                    'comments' => $request->input('comments'),
                    'postedBy' => Auth::guard('web')->user()->id,
                ]);

                return response()->json(['success' => true, 'message' => 'Comments stored successfully'], 200);
           } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Comments'], 404);
            }
        }
    }

    public function orUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'fund' => 'required',
            'account' => 'required',
            'amountpaid' => 'required',
        ]);

        $orno = $request->input('orno');
        $studID = $request->input('studID');
        $semester = $request->input('semester');
        $schlyear = $request->input('schlyear');
        $campus = $request->input('campus');
        $datepaid = $request->input('datepaid');

        try {
            $studaccount = $request->input('account');
            $existingorStudFee = StudPayment::where('account', $studaccount)
                            ->where('campus', $campus)
                            ->where('schlyear', $schlyear)
                            ->where('semester', $semester)
                            ->where('id', '!=', $request->input('id'))->first();

            if ($existingorStudFee) {
                return response()->json(['error' => true, 'message' => 'Student Fee already exists'], 404);
            }

            $studorfee = StudPayment::findOrFail($request->input('id'));
            $studorfee->update([
                'fund' => $request->input('fund'),
                'account' => $request->input('account'),
                'amountpaid' => $request->input('amountpaid'),
        ]);
            return response()->json(['success' => true, 'message' => 'Student Payment updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to store Student Payment'], 404);
        }
    }

    public function orDelete($id) 
    {
        $studorfee = StudPayment::find($id);
        $studorfee->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

    public function orprint(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $orno = $request->query('orno');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $studor = StudPayment::join('coasv2_db_enrollment.students', 'studpayment.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                    ->where('studpayment.studID', $stud_id)
                    ->where('studpayment.orno', $orno)
                    ->where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->select('studpayment.*', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.lname')
                    ->get();

        $data = [
            'studor' => $studor
        ];
        
        $pdf = PDF::loadView('cashier.officialreceipt.pdf.ortemplate', $data)->setPaper('A5', 'portrait');
        return $pdf->stream();
    }

    public function orprintedit(Request $request)
    {
        $stud_id = $request->stud_id;
        $orno = $request->query('orno');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $studor = StudPayment::join('coasv2_db_enrollment.students', 'studpayment.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                    ->where('studpayment.studID', $stud_id)
                    ->where('studpayment.orno', $orno)
                    ->where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->select('studpayment.*', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.lname')
                    ->get();

        $data = [
            'studor' => $studor
        ];
        
        $pdf = PDF::loadView('cashier.officialreceipt.pdf.ortemplate', $data)->setPaper('A5', 'portrait');
        return $pdf->stream();
    }

    public function listedit_orRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('cashier.officialreceipt.list_oredit', compact('sy'));
    }

    public function listsearchedit_orRead(Request $request)
    {
        $orno = $request->query('orno');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $student = StudPayment::where('orno', $orno)->where('campus', $campus)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Official Receipt Number <strong>' . $orno . '</strong> does not exist.');
        }

        $orstud = Student::join('coasv2_db_assessment.studpayment', 'students.stud_id', '=', 'coasv2_db_assessment.studpayment.studID')
                ->where('coasv2_db_assessment.studpayment.orno', $orno)
                ->select('fname', 'mname', 'lname', 'studID')
                ->get();
        $studfund = Funds::orderBy('id', 'DESC')->get();
        $studAccntap = AccountAppraisal::whereIn('id', ['2', '7', '33', '42', '44', '49', '74', '76', '79', '85', '90', '91', '92', '93', '99', '118', '133', '134', '151', '152', '153', '154', '155', '156', '159', '161'])
                    ->orderBy('account_name', 'ASC')
                    ->get();

        $dataprimidOR = StudPayment::where('orno', '=', $orno)
                ->where('schlyear', '=', $schlyear)
                ->where('semester', '=', $semester)
                ->select('id as studorprimID')
                ->first();

        return view('cashier.officialreceipt.listsearch_oredit', compact('orstud', 'studfund', 'studAccntap', 'dataprimidOR'));
    }

    public function deletePayment(Request $request)
    {
        $orno = $request->input('orno');
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');

        if (!$orno || !$schlyear || !$semester) {
            return response()->json(['success' => false, 'message' => 'Invalid parameters.'], 400);
        }

        $deletedRows = StudPayment::where('orno', $orno)
            ->where('schlyear', $schlyear)
            ->where('semester', $semester)
            ->delete();

        if ($deletedRows) {
            return redirect()->route('listedit_orRead')->with('success', 'Payment records deleted successfully.');
        } else {
            return response()->json(['error' => true, 'message' => 'No records found to delete.'], 404);
        }
    }

    public function listorperdayRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('cashier.officialreceipt.reports.listor_perday', compact('sy'));
    }

    public function listsearch_orperdayRead(Request $request)
    {
        $datepaid = $request->query('datepaid');
        $campus = Auth::guard('web')->user()->campus;
    
        $data = StudPayment::join('coasv2_db_enrollment.students', 'studpayment.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                ->where('studpayment.campus', '=', $campus)
                ->where('studpayment.datepaid', '=', $datepaid)
                ->select(
                    'coasv2_db_enrollment.students.lname',
                    'coasv2_db_enrollment.students.fname',
                    'coasv2_db_enrollment.students.mname',
                    'studpayment.orno',
                    'studpayment.studID',
                    'studpayment.datepaid',
                    'studpayment.campus',
                    'studpayment.semester',
                    'studpayment.schlyear',
                    DB::raw('SUM(studpayment.amountpaid) as total_amount')
                )
                ->groupBy(
                    'coasv2_db_enrollment.students.lname',
                    'coasv2_db_enrollment.students.fname',
                    'coasv2_db_enrollment.students.mname',
                    'studpayment.orno',
                    'studpayment.studID',
                    'studpayment.datepaid',
                    'studpayment.campus',
                    'studpayment.semester',
                    'studpayment.schlyear'
                )
                ->get();


        return view('cashier.officialreceipt.reports.listor_searchperday', compact('data'));
    }

    public function listorpermonthRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('cashier.officialreceipt.reports.listor_permonth', compact('sy'));
    }

    public function listsearch_orpermonthRead(Request $request)
    {
        $datepaid = $request->query('datepaid');
        $campus = Auth::guard('web')->user()->campus;

        return view('cashier.officialreceipt.reports.listor_searchpermonth');
    }


    public function getlistsearch_orpermonthRead(Request $request) 
    {
        $datepaid = $request->query('datepaid');
        $campus = Auth::guard('web')->user()->campus;

        [$startDate, $endDate] = explode(' - ', $datepaid);
    
        // Parse the dates to Carbon instances
        $startDate = Carbon::parse($startDate)->startOfDay();
        $endDate = Carbon::parse($endDate)->endOfDay();

        $data = StudPayment::join('coasv2_db_enrollment.students', 'studpayment.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                ->where('studpayment.campus', '=', $campus)
                ->whereBetween('studpayment.datepaid', [$startDate, $endDate])
                ->select(
                    'coasv2_db_enrollment.students.lname',
                    'coasv2_db_enrollment.students.fname',
                    'coasv2_db_enrollment.students.mname',
                    'studpayment.orno',
                    'studpayment.studID',
                    'studpayment.datepaid',
                    'studpayment.campus',
                    'studpayment.semester',
                    'studpayment.schlyear',
                    DB::raw('SUM(studpayment.amountpaid) as total_amount')
                )
                ->groupBy(
                    'coasv2_db_enrollment.students.lname',
                    'coasv2_db_enrollment.students.fname',
                    'coasv2_db_enrollment.students.mname',
                    'studpayment.orno',
                    'studpayment.studID',
                    'studpayment.datepaid',
                    'studpayment.campus',
                    'studpayment.semester',
                    'studpayment.schlyear'
                )
            ->get();

        return response()->json(['data' => $data]);
    }

    public function getlistallorRead() 
    {
        $campus = Auth::guard('web')->user()->campus;
    
        $data = StudPayment::join('coasv2_db_enrollment.students', 'studpayment.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                ->where('studpayment.campus', '=', $campus)
                ->select(
                'coasv2_db_enrollment.students.lname', 
                'coasv2_db_enrollment.students.fname', 
                'coasv2_db_enrollment.students.mname', 
                'studpayment.orno', 
                'studpayment.studID', 
                DB::raw('SUM(studpayment.amountpaid) as total_amount')
            )
            ->groupBy(
                'coasv2_db_enrollment.students.lname', 
                'coasv2_db_enrollment.students.fname', 
                'coasv2_db_enrollment.students.mname', 
                'studpayment.orno', 
                'studpayment.studID'
            )
                ->limit('100')
                ->get();

        return response()->json(['data' => $data]);
    }
}
