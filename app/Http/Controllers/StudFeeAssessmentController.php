<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use App\Models\AssessmentDB\AccountAppraisal;
use App\Models\AssessmentDB\StudentFee;
use App\Models\AssessmentDB\Funds;

use App\Models\ScheduleDB\EnPrograms;

use App\Models\SettingDB\ConfigureCurrent;

class StudFeeAssessmentController extends Controller
{
    public function searchStudfee()
    {   
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
        $programsEn = EnPrograms::orderBy('progAcronym', 'ASC')->get();
        return view('assessment.studentfee.list_studfee', compact('programsEn', 'sy'));
    }

    public function list_searchStudfee(Request $request)
    {   
        $studfund = Funds::orderBy('id', 'DESC')->get();
        $studAccntap = AccountAppraisal::orderBy('account_name', 'ASC')->get();

        $data = StudentFee::query()
            ->select('student_fee.*', 'coasv2_db_schedule.programs.progAcronym')
            ->join('coasv2_db_schedule.programs', function ($join) {
                $join->on('student_fee.prog_Code', '=', 'coasv2_db_schedule.programs.progCod');
                // Additional condition to match prog_Code and progCod
                $join->whereColumn('student_fee.prog_Code', '=', 'coasv2_db_schedule.programs.progCod');
            })
            ->whereColumn('student_fee.prog_Code', '=', 'coasv2_db_schedule.programs.progCod');
    


        if ($request->campus) {
            $data->where('campus', $request->campus);
        }
        if ($request->prog_Code) {
            $data->where('prog_Code', $request->prog_Code);
        }
        if ($request->yrlevel) {
            $data->where('yrlevel', $request->yrlevel);
        }
        if ($request->schlyear) {
            $data->where('schlyear', $request->schlyear);
        }
        if ($request->semester) {
            $data->where('semester', $request->semester);
        }

        $data = $data->get();

        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);

        return view('assessment.studentfee.listsearch_studfee', compact('totalSearchResults', 'data', 'studfund', 'studAccntap'));
    }

    public function getstudFeeRead(Request $request) 
    {
        $campus = $request->query('campus');
        $progCode = $request->query('prog_Code');
        $yrlevel = $request->query('yrlevel');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
    
        $data = StudentFee::where('campus', '=', $campus)
                ->where('prog_Code','=',  $progCode)
                ->where('yrlevel', '=', $yrlevel)
                ->where('schlyear', '=', $schlyear)
                ->where('semester', '=', $semester)
                ->orderBy('accountName', 'ASC')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function studFeeCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'campus' => 'required',
                'schlyear' => 'required',
                'semester' => 'required',
                'prog_Code' => 'required',
                'yrlevel' => 'required',
                'fundname_code' => 'required',
                'accountName' => 'required',
                'amountFee' => 'required',
            ]);

            $campus = $request->input('campus');
            $schlyear = $request->input('schlyear');
            $semester = $request->input('semester');
            $progCode = $request->input('prog_Code');
            $yrlevel = $request->input('yrlevel');

            $studfeeName = $request->input('accountName'); 
            $existingStudFee = StudentFee::where('accountName', $studfeeName)
                            ->where('campus', $campus)
                            ->where('schlyear', $schlyear)
                            ->where('semester', $semester)
                            ->where('prog_Code', $progCode)
                            ->where('yrlevel', $yrlevel)
                            ->first();

            if ($existingStudFee) {
                return response()->json(['error' => true, 'message' => 'Account Name in Student Fee already exists'], 404);
            }

            try {
                StudentFee::create([
                    'campus' => $request->input('campus'),
                    'schlyear' => $request->input('schlyear'),
                    'semester' => $request->input('semester'),
                    'prog_Code' => $request->input('prog_Code'),
                    'yrlevel' => $request->input('yrlevel'),
                    'fundname_code' => $request->input('fundname_code'),
                    'accountName' => $request->input('accountName'),
                    'amountFee' => $request->input('amountFee'),
                    'postedBy' => Auth::guard('web')->user()->id,
                    'remember_token' => Str::random(60),
                ]);

                return response()->json(['success' => true, 'message' => 'Student Fee stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Student Fee'], 404);
            }
        }
    }

    public function studFeeUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'fundname_code' => 'required',
            'accountName' => 'required',
            'amountFee' => 'required',
        ]);

        $campus = $request->input('campus');
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $progCode = $request->input('prog_Code');
        $yrlevel = $request->input('yrlevel');

        try {
            $studfeeName = $request->input('accountName'); 
            $existingStudFee = StudentFee::where('accountName', $studfeeName)
                            ->where('campus', $campus)
                            ->where('schlyear', $schlyear)
                            ->where('semester', $semester)
                            ->where('prog_Code', $progCode)
                            ->where('yrlevel', $yrlevel)
                            ->where('id', '!=', $request->input('id'))->first();

            if ($existingStudFee) {
                return response()->json(['error' => true, 'message' => 'Student Fee already exists'], 404);
            }

            $studfee = StudentFee::findOrFail($request->input('id'));
            $studfee->update([
                'fundname_code' => $request->input('fundname_code'),
                'accountName' => $request->input('accountName'),
                'amountFee' => $request->input('amountFee'),
        ]);
            return response()->json(['success' => true, 'message' => 'Student Feeupdate successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to store Student Fee'], 404);
        }
    }

    public function studFeeDelete($id) 
    {
        $studfee = StudentFee::find($id);
        $studfee->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }
}
