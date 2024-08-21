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

        // $student = Student::where('stud_id', $stud_id)->where('campus', $campus)->where('stud_id', 'LIKE', '%-G')->first();
        // if (!$student) {
        //     return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        // }

        $orstud = Student::where('stud_id', $stud_id)->select('fname', 'mname', 'lname')->get();
        $studfund = Funds::orderBy('id', 'DESC')->get();
        $studAccntap = AccountAppraisal::orderBy('account_name', 'ASC')->get();

        return view('cashier.officialreceipt.listsearch_or', compact('orstud', 'studfund', 'studAccntap'));
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
                StudPayment::create([
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
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $studor = StudPayment::select('studpayment.*')
                    ->where('studpayment.studID', $stud_id)
                    ->where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->get();

        $data = [
            'studor' => $studor
        ];
        
        $pdf = PDF::loadView('cashier.officialreceipt.pdf.ortemplate', $data)->setPaper([0, 0, 396, 612], 'portrait');
        return $pdf->stream();
    }

    public function listall_orRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $campus = Auth::guard('web')->user()->campus;
    
        $data = StudPayment::join('coasv2_db_enrollment.students', 'studpayment.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                ->where('studpayment.campus', '=', $campus)
                ->select('coasv2_db_enrollment.students.lname', 'coasv2_db_enrollment.students.fname', 'coasv2_db_enrollment.students.mname', 'studpayment.*')
                ->limit('10')
                ->get();

        return view('cashier.officialreceipt.listall_or', compact('sy', 'data'));
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
