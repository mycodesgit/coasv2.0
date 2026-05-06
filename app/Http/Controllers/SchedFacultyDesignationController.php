<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\FacDesignation;

use App\Models\SettingDB\ConfigureCurrent;

class SchedFacultyDesignationController extends Controller
{
    public function faculty_design() 
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('scheduler.designation.list_designate', compact('sy'));
    }

    public function faculty_design_search(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');
        $campusArray = array_map('trim', explode(',', $campus));

        $faclist = Faculty::where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('faculty.campus', 'LIKE', "%$campus%");
                    }
                })->get();

        $data = FacDesignation::select('fac_designation.*', 'faculty.*', 'fac_designation.id as fcdid')
                        ->join('faculty', 'fac_designation.fac_id', '=', 'faculty.id')
                        ->where('fac_designation.schlyear', $schlyear)
                        ->where('fac_designation.semester', $semester)
                        ->where('fac_designation.campus', $campus)
                        ->get();
        $totalSearchResults = count($data);

        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('scheduler.designation.list_designate_search', compact('data', 'faclist', 'totalSearchResults', 'sy'));
    }

    public function getfacultyDesigRead(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');

        $data = FacDesignation::join('faculty', 'fac_designation.fac_id', '=', 'faculty.id')
                        ->leftJoin('college', 'fac_designation.facCollege', '=', 'college.college_abbr')
                        ->where('fac_designation.schlyear', $schlyear)
                        ->where('fac_designation.semester', $semester)
                        ->where('fac_designation.campus', $campus)
                        ->select('fac_designation.*', 'faculty.*', 'fac_designation.id as fcdid', 'college.college_name')
                        ->get();

        return response()->json(['data' => $data]);
    }

    public function facdesignationCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'fac_id' => 'required',
            ]);

            $fac_id = $request->input('fac_id'); 
            $schlyear = $request->input('schlyear'); 
            $campus = $request->input('campus'); 
            $semester = $request->input('semester'); 
            $designation = $request->input('designation'); 
            $existingFacDeg = FacDesignation::where('fac_id', $fac_id)->where('schlyear', $schlyear)->where('semester', $semester)->where('campus', $campus)->where('designation', $designation)->first();

            if ($existingFacDeg) {
                return response()->json(['error' => true, 'message' => 'Already exists'], 404);
            }

            try {
                FacDesignation::create([
                    'schlyear' => $request->input('schlyear'),
                    'semester' => $request->input('semester'),
                    'campus' => $request->input('campus'),
                    'facCollege' => $request->input('facCollege'),
                    'fac_id' => $request->input('fac_id'),
                    'designation' => $request->input('designation'),
                    'dunit' => $request->input('dunit'),
                ]);

                return response()->json(['success' => true, 'message' => 'Stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store'], 404);
            }
        }
    }

    public function facdesignationUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'fac_id' => 'required',
        ]);

        try {
            $fac_id = $request->input('fac_id'); 
            $schlyear = $request->input('schlyear'); 
            $campus = $request->input('campus'); 
            $existingFacDeg = FacDesignation::where('fac_id', $fac_id)->where('schlyear', $schlyear)->where('campus', $campus)->where('id', '!=', $request->input('id'))->first();

            if ($existingFacDeg) {
                return response()->json(['error' => true, 'message' => 'Already exists'], 404);
            }

            $fund = FacDesignation::findOrFail($request->input('id'));
            $fund->update([
                'facCollege' => $request->input('facCollege'),
                'fac_id' => $request->input('fac_id'),
                'designation' => $request->input('designation'),
                'dunit' => $request->input('dunit'),
        ]);
            return response()->json(['success' => true, 'message' => 'Designation update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Designation'], 404);
        }
    }

    public function designationDelete($id) 
    {
        $desig = FacDesignation::find($id);
        $desig->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

}
