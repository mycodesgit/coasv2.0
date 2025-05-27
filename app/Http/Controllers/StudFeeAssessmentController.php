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
        $programsEn = EnPrograms::whereRaw("FIND_IN_SET(?, campus)", [Auth::guard('web')->user()->campus])
                    ->orderBy('progAcronym', 'ASC')
                    ->get();

        return view('assessment.studentfee.list_studfee', compact('programsEn', 'sy'));
    }

    public function list_searchStudfee(Request $request)
    {   
        $studfund = Funds::orderBy('id', 'DESC')->get();
        $studAccntap = AccountAppraisal::whereIn('id', ['2', '7', '33', '42', '44', '49', '74', '76', '79', '85', '90', '91', '92', '93', '99', '118', '133', '134', '151', '152', '153', '154', '155', '156', '159', '161', '168'])
                    ->orderBy('account_name', 'ASC')
                    ->get();

        $data = StudentFee::query()
            ->select('student_fee.*', 'coasv2_db_schedule.programs.progAcronym')
            ->join('coasv2_db_schedule.programs', function ($join) {
                $join->on('student_fee.prog_Code', '=', 'coasv2_db_schedule.programs.progCod');
                // Additional condition to match prog_Code and progCod
                $join->whereColumn('student_fee.prog_Code', '=', 'coasv2_db_schedule.programs.progCod');
            })
            ->whereColumn('student_fee.prog_Code', '=', 'coasv2_db_schedule.programs.progCod');
    


        if ($request->campus) {
            $data->where('student_fee.campus', $request->campus);
        }
        if ($request->prog_Code) {
            $data->where('student_fee.prog_Code', $request->prog_Code);
        }
        if ($request->yrlevel) {
            $data->where('student_fee.yrlevel', $request->yrlevel);
        }
        if ($request->schlyear) {
            $data->where('student_fee.schlyear', $request->schlyear);
        }
        if ($request->semester) {
            $data->where('student_fee.semester', $request->semester);
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

    public function fetchStudentFees(Request $request)
    {
        $progCode = explode('-', $request->query('prog_Code'))[0];
        $yrlevel = $request->query('yrlevel');
        if ($yrlevel === '1') {
            $mappedYrLevel = 'New'; 
        } else {
            $mappedYrLevel = 'Old'; 
        }

        $fees = StudFeeTemplate::where('semester', $request->query('semester'))
            ->where('yrlevel', $mappedYrLevel)
            ->get();

        $filteredFees = $fees->filter(function ($fee) use ($progCode) {
            if ($progCode === 'CCS') {
                if ($fee->accountName === 'IT FEE') {
                    return false; 
                }

                if ($fee->accountName === 'COMPUTER LAB FEE' && $progCode === 'CCS') {
                    $fee->amountFee += 500; 
                }
            }

            return $fee->accountName === "TUITION - $progCode" || 
                   strpos($fee->accountName, 'TUITION -') === false;
        });
        return response()->json($filteredFees->values());
    }

    public function fetchStudentFeesgrad(Request $request)
    {
        $progCode = explode('-', $request->query('prog_Code'))[1];
        $yrlevel = $request->query('yrlevel');
        if ($yrlevel === '1') {
            $mappedYrLevel = 'New'; 
        } else {
            $mappedYrLevel = 'Old'; 
        }

        $fees = StudFeeTemplate::where('semester', $request->query('semester'))
            ->where('yrlevel', $mappedYrLevel)
            ->where('temptype', '=', 'GSS')
            ->get();

        $filteredFees = $fees->filter(function ($fee) use ($progCode) {
            if ($progCode === 'CCS') {
                if ($fee->accountName === 'IT FEE') {
                    return false; 
                }

                if ($fee->accountName === 'COMPUTER LAB FEE' && $progCode === 'CCS') {
                    $fee->amountFee += 500; 
                }
            }

            return $fee->accountName === "TUITION - $progCode" || 
                   strpos($fee->accountName, 'TUITION -') === false;
        });
        return response()->json($filteredFees->values());
    }

    public function studFeeCreate(Request $request)
    {
        $validated = $request->validate([
            'rows_data' => 'required|array',
            'rows_data.*.fundname_code' => 'required|string',
            'rows_data.*.accountName' => 'required|string',
            'rows_data.*.amountFee' => 'required|numeric',
            'rows_data.*.prog_code' => 'required|string',
            'rows_data.*.yrlevel' => 'required|string',
            'rows_data.*.schlyear' => 'required|string',
            'rows_data.*.semester' => 'required|string',
            'rows_data.*.campus' => 'required|string',
        ]);

        foreach ($validated['rows_data'] as $data) {
            $exists = StudentFee::where('prog_Code', $data['prog_code'])
                ->where('yrlevel', $data['yrlevel'])
                ->where('schlyear', $data['schlyear'])
                ->where('semester', $data['semester'])
                ->where('campus', $data['campus'])
                ->where('fundname_code', $data['fundname_code'])
                ->where('accountName', $data['accountName'])
                ->exists();

            if ($exists) {
                return response()->json(['error' => true, 'message' => 'Student Fees Already Exist'], 409);
            }

            $studentFee = new StudentFee([
                'prog_Code' => $data['prog_code'],
                'yrlevel' => $data['yrlevel'],
                'schlyear' => $data['schlyear'],
                'semester' => $data['semester'],
                'campus' => $data['campus'],
                'fundname_code' => $data['fundname_code'],
                'accountName' => $data['accountName'],
                'amountFee' => $data['amountFee'],
            ]);
            $studentFee->save();
        }

        return response()->json(['success' => true, 'message' => 'Student fees added successfully!']);
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


    // public function fetchStudentFees(Request $request)
    // {
    //     $semester = $request->query('semester');

    //     $fees = StudFeeTemplate::where('semester', $semester)
    //             ->get();

    //     return response()->json($fees);
    // }

}
