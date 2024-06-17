<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


use Storage;
use Carbon\Carbon;
use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\FacDesignation;
use App\Models\ScheduleDB\Room;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\FacultyLoad;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;

use App\Models\AdmissionDB\Programs;

class SchedClassController extends Controller
{
    public function classSchedRead() 
    {
        $cdata = ClassEnroll::where('campus', '=', Auth::user()->campus)
                    ->get();
        return view('scheduler.schedule.class_sched', compact('cdata'));
    }

    public function classSchedSetRead() 
    {
        return view('scheduler.schedule.class_schedset');
    }

    public function facultySchedRead() 
    {
        $fdata = Faculty::where('campus', '=', Auth::user()->campus)
                    ->orderBy('lname', 'asc')
                    ->get();
        return view('scheduler.schedule.faculty_sched', compact('fdata'));
    }

    public function roomSchedRead() 
    {
        $rdata = Room::where('campus', '=', Auth::user()->campus)
                    ->orderBy('room_name', 'asc')
                    ->get();
        return view('scheduler.schedule.room_sched', compact('rdata'));
    }
}
