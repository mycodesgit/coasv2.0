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
        $todayCollect = StudPayment::whereDate('created_at', now())->where('campus', Auth::guard('web')->user()->campus)->sum('amountpaid');

        $monthCollect = StudPayment::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->where('campus', Auth::guard('web')->user()->campus)
                        ->sum('amountpaid');

        $todayClients = StudPayment::whereDate('created_at', now())
                        ->distinct('studID')
                        ->where('campus', Auth::guard('web')->user()->campus)
                        ->count('studID');

        $monthClients = StudPayment::whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)
                        ->distinct('studID')
                        ->where('campus', Auth::guard('web')->user()->campus)
                        ->count('studID');

        return view('cashier.index', compact('todayCollect', 'monthCollect', 'todayClients', 'monthClients'));
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
        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
        $orno = $request->query('orno');
        $option = $request->query('r3');

        $existingStudFeeOR = StudPayment::where('orno', $orno)
                            ->first();

        if ($existingStudFeeOR) {
            return redirect()->back()->with('error', 'OR Number <strong>' . $orno . '</strong> already exist.');
        }

        if ($option === 'on') {
            $existingStudFeeORIDno = Student::where('stud_id', $stud_id)->first();

            if (!$existingStudFeeORIDno) {
                return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> doesn`t exist.');
            }
        }

        $orstud = Student::where('stud_id', $stud_id)->select('fname', 'mname', 'lname')->get();

        $studfund = Funds::orderBy('id', 'DESC')->get();

        $studAccntap = AccountAppraisal::whereIn('id', ['2', '7', '33', '42', '44', '49', '74', '76', '79', '85', '90', '91', '92', '93', '99', '118', '133', '134', '151', '152', '153', '154', '155', '156', '159', '161', '71', '144', '169', '24', '25', '170', '171', '172'])
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
    
        $data = StudPayment::where('orno', '=', $orno)
                ->get();

        return response()->json(['data' => $data]);
    }

    public function orCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'orno' => 'required',
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
            // $existingStudFeeOR = StudPayment::where('orno', $orno)
            //                 // ->where('campus', $campus)
            //                 // ->where('schlyear', $schlyear)
            //                 // ->where('semester', $semester)
            //                 ->first();

            // if ($existingStudFeeOR) {
            //     return response()->json(['error' => true, 'message' => 'OR Number already exists'], 404);
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

        $studor = StudPayment::leftJoin('coasv2_db_enrollment.students', 'studpayment.studID', '=', 'coasv2_db_enrollment.students.stud_id')
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
        $studAccntap = AccountAppraisal::whereIn('id', ['2', '7', '33', '42', '44', '49', '74', '76', '79', '85', '90', '91', '92', '93', '99', '118', '133', '134', '151', '152', '153', '154', '155', '156', '159', '161', '71', '144', '169', '24', '25', '170', '171', '172'])
                    ->orderBy('account_name', 'ASC')
                    ->get();

        $dataprimidOR = StudPayment::where('orno', '=', $orno)
                ->select('id as studorprimID')
                ->first();

        $dataprimidORdataget = StudPayment::where('orno', '=', $orno)
                ->select('id as studorprimID', 'semester', 'schlyear', 'studID')
                ->first();

        $datacommentOR = ORComments::where('orno', '=', $orno)
                ->select('id as studorcomentsprimID', 'comments')
                ->first();

        return view('cashier.officialreceipt.listsearch_oredit', compact('orstud', 'studfund', 'studAccntap', 'dataprimidOR', 'datacommentOR', 'dataprimidORdataget'));
    }

    public function orCommentsUpdate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'comments' => 'required',
            ]);

            $studpayID = $request->input('studpayID');
            $orno = $request->input('orno');
            $studID = $request->input('studID');
            $semester = $request->input('semester');
            $schlyear = $request->input('schlyear');
            $campus = $request->input('campus');
            $datepaid = $request->input('datepaid');
            $commentId = $request->input('id');

            try {
                $studaccountcomments = $request->input('comments'); 
                $existingStudFeeORcomment = ORComments::where('comments', $studaccountcomments)
                                ->where('studID', $studID)
                                ->where('campus', $campus)
                                ->where('schlyear', $schlyear)
                                ->where('semester', $semester)
                                ->where('studpayID', '!=', $commentId)  
                                ->first();

                if ($existingStudFeeORcomment) {
                    return response()->json(['error' => true, 'message' => 'Comments for this Account already exist'], 404);
                }

                $studorfeecomments = ORComments::find($commentId);

                if ($studorfeecomments) {
                    $studorfeecomments->update([
                        'comments' => $request->input('comments'),
                        'postedBy' => Auth::guard('web')->user()->id,
                    ]);

                    return response()->json(['success' => true, 'message' => 'Comments updated successfully'], 200);
                } else {
                    $neworcomment = ORComments::create([
                        'studpayID' =>  $studpayID,
                        'orno' => $orno,
                        'studID' => $studID,
                        'semester' => $semester,
                        'schlyear' => $schlyear,
                        'campus' => $campus,
                        'datepaid' => $datepaid,
                        'comments' => $request->input('comments'),
                        'postedBy' => Auth::guard('web')->user()->id,
                    ]);

                    return response()->json(['success' => true, 'id' => $neworcomment->id, 'message' => 'Comments created successfully'], 201);
                }
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to update Comments'], 500);
            }
        }
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
            //return redirect()->route('list_oredit')->with('success', 'Payment records deleted successfully.');
            return response()->json(['success' => true, 'message' => 'Payment records deleted successfully.'], 404);
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
                ->leftJoin('coasv2_db_admission.users', 'studpayment.postedBy', '=', 'coasv2_db_admission.users.id')
                ->where('studpayment.campus', '=', $campus)
                ->where('studpayment.datepaid', '=', $datepaid)
                ->select(
                    'coasv2_db_enrollment.students.lname as slname',
                    'coasv2_db_enrollment.students.fname as sfname',
                    'coasv2_db_enrollment.students.mname as smname',
                    'studpayment.orno',
                    'studpayment.studID',
                    'studpayment.datepaid',
                    'studpayment.campus',
                    'studpayment.semester',
                    'studpayment.schlyear',
                    'coasv2_db_admission.users.fname',
                    'coasv2_db_admission.users.lname',
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
                    'studpayment.schlyear',
                    'coasv2_db_admission.users.fname',
                    'coasv2_db_admission.users.lname',
                )
                ->orderBy('studpayment.orno', 'DESC')
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
