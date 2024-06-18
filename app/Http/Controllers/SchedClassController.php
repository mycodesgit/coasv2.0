<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Stime;
use App\Models\ScheduleDB\Sday;

use App\Models\SettingDB\ConfigureCurrent;

class SchedClassController extends Controller
{
    public function classSchedRead() 
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $class = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')->get();

        return view('scheduler.schedule.class_sched', compact('sy', 'class'));
    }

    public function getCoursesyearsec(Request $request)
    {
        $semester = $request->semester;
        $schlyear = $request->schlyear;
        $campus = Auth::guard('web')->user()->campus;

        $courses = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
            ->where('class_enroll.semester', $semester)
            ->where('class_enroll.schlyear', $schlyear)
            ->where('class_enroll.campus', $campus)
            ->get();

        return response()->json($courses);
    }

    public function classSchedSetRead(Request $request) 
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $progCod = $request->query('progCod');
        $campus = Auth::guard('web')->user()->campus;

        $parts = preg_split('/[\+\s]/', $progCod);
        $progCodPart = $parts[0];
        $progCodSuffix = isset($parts[1]) ? $parts[1] : null;
        $program = EnPrograms::whereRaw('LOWER(progCod) = ?', [strtolower($progCodPart)])->first();

        $progAcronym = $program ? $program->progAcronym : 'N/A';

        $studclass = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                        ->where('class_enroll.schlyear', '=', $schlyear)
                        ->where('class_enroll.semester', '=', $semester)
                        ->where('class_enroll.progCode', $progCod)
                        ->select('programs.progAcronym', 'class_enroll.*')
                        ->get();

        $days = Sday::all()->pluck('dayDesc')->toArray();
        $times = Stime::all()->pluck('timeDesc')->toArray();

        return view('scheduler.schedule.class_schedset', compact('sy', 'studclass', 'progAcronym', 'progCodSuffix', 'days', 'times'));
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
