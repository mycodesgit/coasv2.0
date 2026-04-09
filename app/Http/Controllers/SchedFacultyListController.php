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
use App\Models\ScheduleDB\Department;
use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\Addressee;

class SchedFacultyListController extends Controller
{
    public function faculty_list() 
    {
        $collegelist = College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])->get();
        $deptlist = Department::get();
        $depts = Department::join('faculty', 'faculty.facdept', '=', 'department.deptCod')->get();
        $adr = Addressee::all();

        return view('scheduler.faculty.list_faculty', compact('collegelist', 'deptlist', 'depts', 'adr'));
    }

    public function getfacultylistRead() 
    {
        $campus = Auth::guard('web')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $data = Faculty::join('addressee', 'faculty.adrID', '=', 'addressee.id')
                ->join('college', 'faculty.faccollege', '=', 'college.college_abbr')
                ->leftJoin('department', 'faculty.facdept', '=', 'department.deptCod')
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('faculty.campus', 'LIKE', "%$campus%");
                    }
                })
                ->select('faculty.*', 'faculty.id as fctyid', 'faculty.campus as fcamp', 'faculty.campactive', 'college.*', 'addressee.*', 'addressee.id as adrid', 'department.deptCod')
                ->orderBy('faculty.lname')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function search(Request $request)
    {
        $search = $request->search;

        $faculty = Faculty::where('lname', 'LIKE', "%$search%")
                        ->orWhere('fname', 'LIKE', "%$search%")
                        ->limit(10)
                        ->get(['id','lname','fname']);

        $results = $faculty->map(function($item){
            return [
                'id' => $item->id,
                'text' => $item->lname . ', ' . $item->fname
            ];
        });
        return response()->json($results);
    }

    public function getDepartments($college)
    {
        $departments = Department::where('collegeCod', $college)->get();
        return response()->json($departments);
    }

    public function facultyCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'lname' => 'required',
                'fname' => 'required',
                'facdept' => 'required',
                'adrID' => 'required',
            ]);

            $lName = $request->input('lname'); 
            $fName = $request->input('fname');

            $existingFaculty = Faculty::where('lname', $lName)
                ->where('fname', $fName)
                ->where('campus', Auth::guard('web')->user()->campus)
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
                    'faccollege' => $request->input('faccollege'),
                    'facdept' => $request->input('facdept'),
                    'adrID' => $request->input('adrID'),
                    'email' => $request->input('email'),
                    'remember_token' => Str::random(60),
                ]);

                return response()->json(['success' => true, 'message' => 'Faculty stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Faculty'], 404);
            }
        }
    }

    public function facultyUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'lname' => 'required',
            'fname' => 'required',
            'adrID' => 'required',
        ]);

        try {
            $lName = $request->input('lname'); 
            $fName = $request->input('fname');
            $existingFaculty = Faculty::where('lname', $lName)->where('fname', $fName)->where('campus', Auth::guard('web')->user()->campus)->where('id', '!=', $request->input('id'))->first();

            if ($existingFaculty) {
                return response()->json(['error' => true, 'message' => 'Faculty already exists'], 404);
            }

            $faclty = Faculty::findOrFail($request->input('id'));
            $faclty->update([
                'lname' => $request->input('lname'),
                'fname' => $request->input('fname'),
                'mname' => $request->input('mname'),
                'ext' => $request->input('ext'),
                'faccollege' => $request->input('faccollege'),
                'facdept' => $request->input('facdept'),
                'adrID' => $request->input('adrID'),
                'email' => $request->input('email'),
                'rank' => $request->input('rank'),
        ]);
            return response()->json(['success' => true, 'message' => 'Faculty update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Faculty'], 404);
        }
    }

    public function ajaxUpdateCampus(Request $request, $facultyId)
    {
        $request->validate([
            'campus' => 'required|string|in:MC,VC,SCC,HC,MP,IC,CA,CC,SC,HinC',
        ]);

        $faculty = Faculty::findOrFail($facultyId);
        $existing = $faculty->campus ? explode(',', $faculty->campus) : [];
        if (!in_array($request->campus, $existing)) {
            $existing[] = $request->campus;
        }
        $faculty->campus = implode(',', $existing);
        $faculty->campactive = $request->input('campactive');
        $faculty->save();

        return response()->json(['message' => 'Campus updated successfully']);
    }

    public function facultyDelete($id) 
    {
        $faclty = Faculty::find($id);
        $faclty->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

}
