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
