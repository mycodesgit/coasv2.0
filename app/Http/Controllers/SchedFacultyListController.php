<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\Addressee;

class SchedFacultyListController extends Controller
{
    public function faculty_list() 
    {
        $collegelist = College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])->get();
        $adr = Addressee::all();
        return view('scheduler.faculty.list_faculty', compact('collegelist', 'adr'));
    }

    public function getfacultylistRead() 
    {
        $data = Faculty::join('addressee', 'faculty.adrID', '=', 'addressee.id')
                ->join('college', 'faculty.dept', '=', 'college.college_abbr')
                ->where('faculty.campus', '=', Auth::guard('web')->user()->campus)
                ->select('faculty.*', 'faculty.campus as fcamp', 'college.*', 'addressee.*', 'addressee.id as adrid')
                ->orderBy('faculty.lname')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function facultyCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'lname' => 'required',
                'fname' => 'required',
                'mname' => 'required',
                'dept' => 'required',
                'adrID' => 'required',
            ]);

            $lName = $request->input('lname'); 
            $fName = $request->input('lname'); 
            $mName = $request->input('lname'); 
            $existingFaculty = Faculty::where('lname', $lName)
                          ->where('fname', $fName)
                          ->where('mname', $mName)
                          ->first();

            if ($existingFaculty) {
                return response()->json(['error' => true, 'message' => 'Faculty already exists'], 404);
            }

            try {
                Faculty::create([
                    'campus' => Auth::guard('web')->user()->campus,
                    'lname' => $request->input('lname'),
                    'fname' => $request->input('fname'),
                    'mname' => $request->input('mname'),
                    'ext' => $request->input('ext'),
                    'dept' => $request->input('dept'),
                    'adrID' => $request->input('adrID'),
                    'remember_token' => Str::random(60),
                ]);

                return response()->json(['success' => true, 'message' => 'Faculty stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Faculty'], 404);
            }
        }
    }

}
