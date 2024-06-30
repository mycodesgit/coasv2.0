<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\KioskUser;

class KioskAdminController extends Controller
{
    public function adminkioskRead()
    {
        $campus = Auth::guard('web')->user()->campus;

        $studkiosk = KioskUser::leftJoin('students', 'kioskstudent.studid', '=', 'students.stud_id')
                    ->where('students.campus', $campus)
                    ->get();

        return view('kioskadmin.list_kioskuser', compact('studkiosk'));
    }

    public function getadminkioskRead()
    {
        $campus = Auth::guard('web')->user()->campus;

        $data = KioskUser::leftJoin('students', 'kioskstudent.studid', '=', 'students.stud_id')
                    ->where('students.campus', $campus)
                    ->select('kioskstudent.*', 'kioskstudent.id as studkiosid', 'students.lname', 'students.fname', 'students.mname')
                    ->get();

        return response()->json(['data' => $data]);
    }

    public function adminkioskCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'studid' => 'required',
                'password' => 'required',
            ]);

            $studidName = $request->input('studid'); 
            $existingStudentID = KioskUser::where('studid', $studidName)->first();

            if ($existingStudentID) {
                return response()->json(['error' => true, 'message' => 'Student ID No already exists'], 404);
            }

            try {
                KioskUser::create([
                    'studid' => $request->input('studid'),
                    'password' => Hash::make($request->input('password')),
                    'postedBy' => Auth::guard('web')->user()->id
                ]);

                return response()->json(['success' => true, 'message' => 'Stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store'], 404);
            }
        }
    }

    public function adminkioskUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'studid' => 'required',
            'password' => 'required',
        ]);

        try {
            $studidName = $request->input('studid');
            $existingStudentID = KioskUser::where('studid', $studidName)->where('id', '!=', $request->input('id'))->first();

            if ($existingStudentID) {
                return response()->json(['error' => true, 'message' => 'Student ID No already exists'], 404);
            }

            $kioskuser = KioskUser::findOrFail($request->input('id'));
            $kioskuser->update([
                'studid' => $studidName,
                'password' => Hash::make($request->input('password')),
                'postedBy' => Auth::guard('web')->user()->id
        ]);
            return response()->json(['success' => true, 'message' => 'Student Password in Kiosk Updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Student Password'], 404);
        }
    }

    public function adminkioskDelete($id) 
    {
        $kioskuser = KioskUser::find($id);
        $kioskuser->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }
}
