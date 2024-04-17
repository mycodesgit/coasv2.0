<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Room;

class SchedClassProgramsController extends Controller
{
    public function index()
    {
        $colCount = College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])->count();
        $enunprogCount = EnPrograms::where('progCod', 'NOT LIKE', '%GSS%')->count();
        $engradprogCount = EnPrograms::where('progCod', 'LIKE', '%GSS%')->count();
        $roomCount = Room::where('campus', Auth::guard('web')->user()->campus)->count();

        return view('scheduler.index', compact('colCount', 'enunprogCount', 'engradprogCount', 'roomCount'));
    }

    public function collegeRead() 
    {
        return view('scheduler.college.list_college');
    }

    public function getcollegeRead() 
    {
        $data = College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])->orderBy('college_name', 'ASC')->get();

        return response()->json(['data' => $data]);
    }
    
    public function programsRead() 
    {
        return view('scheduler.programs.list_programs');
    }

    public function getprogramsRead() 
    {
        $data = EnPrograms::orderBy('progAcronym', 'ASC')->get();

        return response()->json(['data' => $data]);
    }
}
