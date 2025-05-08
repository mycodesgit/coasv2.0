<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\Department;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\SubjectAcademicType;
use App\Models\ScheduleDB\SubjectDeliveryMode;

use App\Models\EnrollmentDB\StudentLevel;

class SchedClassProgramsController extends Controller
{
    public function programsRead() 
    {
        $col = College::whereBetween('id', [2, 8])->get();
        $dept = Department::all();
        $lev = StudentLevel::all();
        $acad = SubjectAcademicType::all();
        $delv = SubjectDeliveryMode::all();

        return view('scheduler.programs.list_programs', compact('col', 'dept', 'lev', 'delv', 'acad'));
    }

    public function getprogramsRead() 
    {
        $data = EnPrograms::orderBy('progAcronym', 'ASC')->get();

        return response()->json(['data' => $data]);
    }

    public function getDepartmentsByCollege(Request $request)
    {
        $collegeAbbr = $request->input('college_abbr');

        $departments = Department::where('collegeCod', $collegeAbbr)->get(['deptCod', 'deptName']);

        return response()->json($departments);
    }

    public function getNextProgramNumber(Request $request)
    {
        $college_abbr = $request->input('college_abbr');
        $deptCod = $request->input('deptCod');

        $lastProgram = EnPrograms::where('progCod', 'like', "$college_abbr-$deptCod-%")
                              ->orderBy('progCod', 'desc')
                              ->first();

        if ($lastProgram) {
            $lastNumber = intval(substr($lastProgram->progCod, strrpos($lastProgram->progCod, '-') + 1));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return response()->json(['nextNumber' => $nextNumber]);
    }

    public function programUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'campus' => 'required',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->input('id'));
            $coll = EnPrograms::findOrFail($decryptedId);
            $coll->update([
                'progCollege' => $request->input('progCollege'),
                'progDep' => $request->input('progDep'),
                'progCod' => $request->input('progCod'),
                'progAccount' => $request->input('progAccount'),
                'progName' => $request->input('progName'),
                'progAcronym' => $request->input('progAcronym'),
                'progLev' => $request->input('progLev'),
                'campus' => implode(',', $request->input('campus')),
        ]);
            return response()->json(['success' => true, 'message' => 'Program belong to Campus update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to store Program belong to Campus'], 404);
        }
    }
}
