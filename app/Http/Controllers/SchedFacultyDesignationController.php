<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\FacDesignation;

class SchedFacultyDesignationController extends Controller
{
    public function faculty_design() 
    {
        return view('scheduler.designation.list_designate');
    }

    public function faculty_design_search(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        $schlyear = is_array($schlyear) ? $schlyear : [$schlyear];
        $semester = is_array($semester) ? $semester : [$semester];

        $faclist = Faculty::all();

        $data = FacDesignation::select('fac_designation.*', 'faculty.*', 'fac_designation.id as fcdid')
                        ->join('faculty', 'fac_designation.fac_id', '=', 'faculty.id')
                        ->where('fac_designation.schlyear', $schlyear)
                        ->where('fac_designation.semester', $semester)
                        ->get();
        $totalSearchResults = count($data);

        return view('scheduler.designation.list_designate_search', compact('data', 'faclist', 'totalSearchResults'));
    }

    public function faculty_designdAdd(Request $request)
    {
        $faclist = Faculty::all();
        $faclist = FacDesignation::all();

        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = $request->input('campus');
        $facdept = $request->input('facdept');
        $fac_id = $request->input('fac_id');
        $designation = $request->input('designation');
        $dunit = $request->input('dunit');

        $existingRecord = FacDesignation::where('fac_id', $fac_id)
            ->where('schlyear', $schlyear)
            ->where('semester', $semester)
            ->where('campus', $campus)
            ->first();

        if ($existingRecord) {
            return redirect()->back()->with('fail', 'Faculty Designation already exists.');
        }

        $facDesig = new FacDesignation();
        $facDesig->schlyear = $schlyear;
        $facDesig->semester = $semester;
        $facDesig->campus = $campus;
        $facDesig->facdept = $facdept;
        $facDesig->fac_id = $fac_id;
        $facDesig->designation = $designation;
        $facDesig->dunit = $dunit;

        $facDesig->save();

        return redirect()->back()->with('success','Faculty Designation successfully saved in this semester');
    }

    public function faculty_designdUpdate(Request $request)
    {
        $id = $request->input('edit_id');
        $facdept = $request->input('edit_facdept');
        $fac_id = $request->input('edit_fac_id');
        $designation = $request->input('edit_designation');
        $dunit = $request->input('edit_dunit');

        $facDesig = FacDesignation::find($id);
        $facDesig->facdept = $facdept;
        $facDesig->fac_id = $fac_id;
        $facDesig->designation = $designation;
        $facDesig->dunit = $dunit;

        $facDesig->save();

        return redirect()->back()->with('success', 'Faculty Designation successfully updated.');
    }

}
