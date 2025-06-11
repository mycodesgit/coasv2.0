<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\GradesheetSignatory;
use App\Models\SettingDB\SigPresVice;

class SettingSignatoryController extends Controller
{
    public function gradesheetSignatoryRead()
    {   
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            //->where('set_status', 2)
            ->whereIn('id', ['21', '20'])
            ->orderBy('id', 'DESC')
            ->get();

        return view('control.settings.signatory.listsignatories', compact('sy'));
    }

    public function getPresViceSigRead() 
    {
        $data = SigPresVice::orderBy('id', 'ASC')->get();

        return response()->json(['data' => $data]);
    }

    public function presViceSigCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'fulname' => 'required',
                'titledeg' => 'required',
            ]);

            $fulName = $request->input('fulname'); 
            $existingSig = SigPresVice::where('fulname', $fulName)->first();

            if ($existingSig) {
                return response()->json(['error' => true, 'message' => 'Signatory already exists'], 404);
            }

            try {
                SigPresVice::create([
                    'fulname' => $request->input('fulname'),
                    'titledeg' => $request->input('titledeg'),
                    'position' => $request->input('position'),
                    'schlyear' => $request->input('schlyear'),
                    'semester' => $request->input('semester'),
                ]);

                return response()->json(['success' => true, 'message' => 'Signatory stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Signatory'], 404);
            }
        }
    }
}
