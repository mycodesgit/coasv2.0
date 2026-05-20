<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\GradesheetSignatory;
use App\Models\SettingDB\SignatoriesEmp;

class SettingSignatoryController extends Controller
{
    public function index()
    {   
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('control.settings.signatory.listsign', compact('sy'));
    }

    public function store(Request $request)
    {   
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('control.settings.signatory.listsignatories', compact('sy'));
    }

    public function show(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        $data = SignatoriesEmp::where('schlyear', $schlyear)
            ->where('semester', $semester)
            ->where('position', '=', 'Guidance Counselor')
            ->orderBy('id', 'ASC')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function create(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'fulname' => 'required',
                'titledeg' => 'required',
            ]);

            $fulName = $request->input('fulname'); 
            $existingSig = SignatoriesEmp::where('fulname', $fulName)->first();

            if ($existingSig) {
                return response()->json(['error' => true, 'message' => 'Signatory already exists'], 404);
            }

            //try {
                SignatoriesEmp::create([
                    'fulname' => $request->input('fulname'),
                    'titledeg' => $request->input('titledeg'),
                    'position' => $request->input('position'),
                    'schlyear' => $request->input('schlyear'),
                    'semester' => $request->input('semester'),
                    'campus' => $request->input('campus'),
                ]);

                return response()->json(['success' => true, 'message' => 'Signatory stored successfully'], 200);
            //} catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Signatory'], 404);
            //}
        }
    }
}
