<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\Department;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\SubjectAcademicType;
use App\Models\ScheduleDB\SubjectDeliveryMode;

use App\Models\EnrollmentDB\StudentLevel;

class EnSubjectsController extends Controller
{
    public function subjectsRead() 
    {
        $col = College::whereBetween('id', [2, 8])->get();
        $dept = Department::all();
        $lev = StudentLevel::all();
        $acad = SubjectAcademicType::all();
        $delv = SubjectDeliveryMode::all();
        return view('enrollment.subject.sublist', compact('col', 'dept', 'lev', 'delv', 'acad'));
    }

    public function getsubjectsRead() 
    {
        $data = Subject::all();
        return response()->json(['data' => $data]);
    }

    public function getNextSubjectNumber(Request $request)
    {
        $college_abbr = $request->input('college_abbr');
        $deptCod = $request->input('deptCod');

        $lastSubject = Subject::where('sub_code', 'like', "$college_abbr-$deptCod-%")
                              ->orderBy('sub_code', 'desc')
                              ->first();

        if ($lastSubject) {
            $lastNumber = intval(substr($lastSubject->sub_code, strrpos($lastSubject->sub_code, '-') + 1));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return response()->json(['nextNumber' => $nextNumber]);
    }

    public function subjectsCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'sub_code' => 'required',
                'subjcostcenter' => 'required',
                'sub_name' => 'required',
                'sub_title' => 'required',
                'sublecredit' => 'required',
                'sublabcredit' => 'required',
                'sub_unit' => 'required',
                'subjweeks' => 'required',
                'subjconthrs' => 'required',
                'subjlev' => 'required',
                'subdelmod' => 'required',
                'subacadtype' => 'required',
                'subjweeks' => 'required',
                'subjweeks' => 'required',
            ]);

            $sub_code = $request->input('sub_code');
            $sub_name = $request->input('sub_name');

            $existingSubject = Subject::where('sub_code', $sub_code)->where('sub_name', $sub_name)->first();

            if ($existingSubject) {
                return response()->json(['error' => true, 'message' => 'Subject already exists'], 404);
            }

            try {
                Subject::create([
                    'sub_code' => $request->input('sub_code'),
                    'subjcostcenter' => $request->input('subjcostcenter'),
                    'sub_name' => $request->input('sub_name'),
                    'sub_title' => $request->input('sub_title'),
                    'subjcollege' => $request->input('subjcollege'),
                    'subjdep' => $request->input('subjdep'),
                    'sublecredit' => $request->input('sublecredit'),
                    'sublabcredit' => $request->input('sublabcredit'),
                    'sub_unit' => $request->input('sub_unit'),
                    'subjweeks' => $request->input('subjweeks'),
                    'subjconthrs' => $request->input('subjconthrs'),
                    'subjlev' => $request->input('subjlev'),
                    'subdelmod' => $request->input('subdelmod'),
                    'subjprereq' => $request->input('subjprereq'),
                    'subjcoreq' => $request->input('subjcoreq'),
                    'subacadtype' => $request->input('subacadtype'),
                ]);

                return response()->json(['success' => true, 'message' => 'Subject stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Subject'], 404);
            }
        }
    }
}
