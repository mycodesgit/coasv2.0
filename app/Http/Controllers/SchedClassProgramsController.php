<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\EnPrograms;

class SchedClassProgramsController extends Controller
{
    public function index()
    {
        return view('scheduler.index');
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
