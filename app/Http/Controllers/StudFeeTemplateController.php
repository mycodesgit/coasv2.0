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
use App\Models\AssessmentDB\StudFeeTemplate;
use App\Models\AssessmentDB\Funds;

use App\Models\ScheduleDB\EnPrograms;

use App\Models\SettingDB\ConfigureCurrent;

class StudFeeTemplateController extends Controller
{
    public function searchStudfeeTemplate()
    {   
        return view('assessment.template.list_template');
    }

    public function list_searchStudfeetemplate(Request $request)
    {   
        $studfund = Funds::orderBy('id', 'DESC')->get();
        $studAccntap = AccountAppraisal::whereIn('id', ['2', '7', '33', '42', '44', '49', '74', '76', '79', '85', '90', '91', '92', '93', '99', '118', '133', '134', '151', '152', '153', '154', '155', '156', '159', '161', '168'])
                    ->orderBy('account_name', 'ASC')
                    ->get();

        $data = StudFeeTemplate::all();

        return view('assessment.template.listsearch_template', compact('data', 'studfund', 'studAccntap'));
    }

    public function getstudFeetemplateRead(Request $request) 
    {
        $temptype = $request->query('temptype');
        $semester = $request->query('semester');
        $yrlevel = $request->query('yrlevel');
    
        $data = StudFeeTemplate::where('temptype', '=', $temptype)
                ->where('yrlevel', '=', $yrlevel)
                ->where('semester', '=', $semester)
                ->orderBy('accountName', 'ASC')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function studFeeTemplateCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'temptype' => 'required',
                'semester' => 'required',
                'yrlevel' => 'required',
                'fundname_code' => 'required',
                'accountName' => 'required',
                'amountFee' => 'required',
            ]);

            $temptype = $request->input('temptype');
            $semester = $request->input('semester');
            $yrlevel = $request->input('yrlevel');

            $studfeeName = $request->input('accountName'); 
            $existingStudFee = StudFeeTemplate::where('accountName', $studfeeName)
                            ->where('temptype', $temptype)
                            ->where('semester', $semester)
                            ->where('yrlevel', $yrlevel)
                            ->first();

            if ($existingStudFee) {
                return response()->json(['error' => true, 'message' => 'Account Name in Student Fee already exists'], 404);
            }

            try {
                StudFeeTemplate::create([
                    'temptype' => $request->input('temptype'),
                    'semester' => $request->input('semester'),
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
}
