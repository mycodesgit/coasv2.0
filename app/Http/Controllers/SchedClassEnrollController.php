<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Storage;
use Carbon\Carbon;
use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\EnPrograms;

class SchedClassEnrollController extends Controller
{
    
    public function courseEnroll_list() 
    {
        return view('scheduler.classenroll.list_classenroll');
    }

    public function courseEnroll_list_search(Request $request) {
        $program = EnPrograms::all();

        $data = ClassEnroll::query();
        if ($request->schlyear) {
            $data->where('schlyear', $request->schlyear);
        }
        if ($request->semester) {
            $data->where('semester', $request->semester);
        }

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
        $data = $data->get();

        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);

        return view('scheduler.classenroll.list_classenroll_search', compact('program', 'data', 'totalSearchResults'));
    }

    public function getclassEnRead(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
    
        $data = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                ->select('class_enroll.*', 'programs.progAcronym')
                ->where('schlyear', '=', $schlyear)
                ->where('semester', '=', $semester)
                ->where('campus', '=', $campus)
                ->orderBy('programs.progAcronym', 'ASC')
                ->orderBy('class_enroll.classSection', 'ASC')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function classEnrollCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'schlyear' => 'required',
                'semester' => 'required',
                'campus' => 'required',
                'progCode' => 'required',
                'classSection' => 'required',
                'classno' => 'required',
            ]);

            $schlyear = $request->input('schlyear');
            $semester = $request->input('semester');
            $campus =  $request->input('campus');
            $progCode = $request->input('progCode');
            $classSection = $request->input('classSection');
            $classno = $request->input('classno');

            $existingClassEn = ClassEnroll::where('schlyear', $schlyear)->where('semester', $semester)->where('progCode', $progCode)->where('classSection', $classSection)->first();

            if ($existingClassEn) {
                return response()->json(['error' => true, 'message' => 'Class already exists'], 404);
            }

            try {
                ClassEnroll::create([
                    'schlyear' => $request->input('schlyear'),
                    'semester' => $request->input('semester'),
                    'campus' => $request->input('campus'),
                    'progCode' => $request->input('progCode'),
                    'classSection' => $request->input('classSection'),
                    'classno' => $request->input('classno'),
                    'remember_token' => Str::random(60),
                ]);

                return response()->json(['success' => true, 'message' => 'Class stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Class'], 404);
            }
        }
    }

    public function classEnrolledUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'progCode' => 'required',
            'classSection' => 'required',
            'classno' => 'required',
        ]);

        try {
            $classEn = ClassEnroll::findOrFail($request->input('id'));
            $classEn->update([
                'progCode' => $request->input('progCode'),
                'classSection' => $request->input('classSection'),
                'classno' => $request->input('classno'),
        ]);
            return response()->json(['success' => true, 'message' => 'Class successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to store Class'], 404);
        }
    }

    public function getProgramId($progAcronym)
    {
        $program = EnPrograms::where('progAcronym', $progAcronym)->first();
        return response()->json(['id' => $program->id]);
    }

    public function classEnrolledDelete($id) 
    {
        $classEn = ClassEnroll::find($id);
        $classEn->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }
}


