<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\AssessmentDB\Funds;
use App\Models\AssessmentDB\AccountCoa;
use App\Models\AssessmentDB\AccountAppraisal;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\EnrollmentDB\StudEnrolmentHistory;

class StudFundAssessmentController extends Controller
{
    public function index() 
    {
        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->subYears(1)->year;
        $userCampus = Auth::guard('web')->user()->campus;

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
                    ->where(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.schlyear, '-', -1)"), $currentYear)
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
                    ->where(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.schlyear, '-', -1)"), $currentYear)
                    ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
                    ->orderBy('college_name', 'ASC')
                    ->select('college.*', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
                    ->groupBy('college.id')
                    ->get();

        return view('assessment.index', compact('collegesFirstSemester', 'collegesSecondSemester', 'currentYear', 'previousYear'));
    }

    public function fundsRead()
    {   
        return view('assessment.studentfund.list_fund');
    }

    public function getfundsRead() 
    {
        $data = Funds::orderBy('id', 'ASC')->get();

        return response()->json(['data' => $data]);
    }

    public function fundCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'fund_name' => 'required',
            ]);

            $fundsName = $request->input('fund_name'); 
            $existingFunds = Funds::where('fund_name', $fundsName)->first();

            if ($existingFunds) {
                return response()->json(['error' => true, 'message' => 'Fund already exists'], 404);
            }

            try {
                Funds::create([
                    'fund_name' => $request->input('fund_name'),
                    'remember_token' => Str::random(60),
                ]);

                return response()->json(['success' => true, 'message' => 'Fund stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Fund'], 404);
            }
        }
    }

    public function fundUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'fund_name' => 'required',
        ]);

        try {
            $fundsName = $request->input('fund_name');
            $existingFunds = Funds::where('fund_name', $fundsName)->where('id', '!=', $request->input('id'))->first();

            if ($existingFunds) {
                return response()->json(['error' => true, 'message' => 'Funds already exists'], 404);
            }

            $fund = Funds::findOrFail($request->input('id'));
            $fund->update([
                'fund_name' => $fundsName,
        ]);
            return response()->json(['success' => true, 'message' => 'Fund update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Fund'], 404);
        }
    }

    public function fundDelete($id) 
    {
        $fund = Funds::find($id);
        $fund->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

    public function accountCOARead()
    {   
        return view('assessment.studentfund.list_accountcoa');
    }

    public function getaccountCOARead() 
    {
        $data = AccountCoa::orderBy('id', 'ASC')->get();

        return response()->json(['data' => $data]);
    }

    public function accountCOACreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'accountcoa_code' => 'required',
                'accountcoa_name' => 'required',
            ]);

            $coaCode = $request->input('accountcoa_code'); 
            $coaName = $request->input('accountcoa_name'); 
            $existingCOA = AccountCoa::where('accountcoa_code', $coaCode)->where('accountcoa_name', $coaName)->first();

            if ($existingCOA) {
                return response()->json(['error' => true, 'message' => 'COA Account already exists'], 404);
            }

            try {
                AccountCoa::create([
                    'accountcoa_code' => $request->input('accountcoa_code'),
                    'accountcoa_name' => $request->input('accountcoa_name'),
                    'remember_token' => Str::random(60),
                ]);

                return response()->json(['success' => true, 'message' => 'COA Account stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store COA Account'], 404);
            }
        }
    }

    public function accountCOAUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'accountcoa_code' => 'required',
            'accountcoa_name' => 'required',
        ]);

        try {
            $coaCode = $request->input('accountcoa_code'); 
            $coaName = $request->input('accountcoa_name'); 
            $existingCOA = AccountCoa::where('accountcoa_code', $coaCode)->where('accountcoa_name', $coaName)->where('id', '!=', $request->input('id'))->first();

            if ($existingCOA) {
                return response()->json(['error' => true, 'message' => 'COA Account already exists'], 404);
            }

            $coaAccnt = AccountCoa::findOrFail($request->input('id'));
            $coaAccnt->update([
                'accountcoa_code' => $coaCode,
                'accountcoa_name' => $coaName,
        ]);
            return response()->json(['success' => true, 'message' => 'COA Account update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to store COA Account'], 404);
        }
    }

    public function accountCOADelete($id) 
    {
        $coaAccnt = AccountCoa::find($id);
        $coaAccnt->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

    public function accountAppraisalRead()
    {   
        $funds = Funds::orderBy('id', 'ASC')->get();
        $accntsCOA = AccountCoa::orderBy('id', 'ASC')->get();

        return view('assessment.studentfund.list_accounts', compact('funds', 'accntsCOA'));
    }

    public function getaccountAppraisalRead() 
    {
        $data = AccountAppraisal::leftJoin('coa_accounts', 'accounts.coa_id', '=', 'coa_accounts.accountcoa_code')
                ->select('accounts.id as acntid', 'accounts.*', 'coa_accounts.*')
                ->orderBy('accounts.account_name', 'ASC')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function accountAppraisalCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'fund_id' => 'required',
                'account_name' => 'required',
                'coa_id' => 'required',
            ]);

            $fundidName = $request->input('fund_id'); 
            $accntappName = $request->input('account_name'); 
            $coaidName = $request->input('coa_id'); 
            $existingAccntApp = AccountAppraisal::where('fund_id', $fundidName)->where('account_name', $accntappName)->first();

            if ($existingAccntApp) {
                return response()->json(['error' => true, 'message' => 'Account already exists'], 404);
            }

            try {
                AccountAppraisal::create([
                    'fund_id' => $request->input('fund_id'),
                    'account_name' => $request->input('account_name'),
                    'coa_id' => $request->input('coa_id'),
                    'remember_token' => Str::random(60),
                ]);

                return response()->json(['success' => true, 'message' => 'Account stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Account'], 404);
            }
        }
    }

    public function accountAppraisalUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'fund_id' => 'required',
            'account_name' => 'required',
            'coa_id' => 'required',
        ]);

        try {
            $fundidName = $request->input('fund_id'); 
            $accntappName = $request->input('account_name'); 
            $coaidName = $request->input('coa_id');
            $existingAccntApp = AccountAppraisal::where('fund_id', $fundidName)->where('account_name', $accntappName)->where('coa_id', $coaidName)->where('id', '!=', $request->input('id'))->first();

            if ($existingAccntApp) {
                return response()->json(['error' => true, 'message' => 'Account already exists'], 404);
            }

            $accntsApp = AccountAppraisal::findOrFail($request->input('id'));
            $accntsApp->update([
                'fund_id' => $fundidName,
                'account_name' => $accntappName,
                'coa_id' => $coaidName,
        ]);
            return response()->json(['success' => true, 'message' => 'Account update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to store Account'], 404);
        }
    }

    public function accountAppraisalDelete($id) 
    {
        $accntsApp = AccountAppraisal::find($id);
        $accntsApp->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }
}
