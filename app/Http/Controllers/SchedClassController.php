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
use App\Models\ScheduleDB\SetClassSchedule;

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

        return view('scheduler.schedule.class_schedset', compact('sy', 'studclass', 'progAcronym', 'progCodPart', 'progCodSuffix', 'days', 'times'));
    }

    public function getSubjectsClassSched(Request $request)
    {
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = Auth::guard('web')->user()->campus;

        $progsuboff = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                            ->where('sub_offered.schlyear', $schlyear)
                            ->where('sub_offered.semester', $semester)
                            ->where('sub_offered.campus', $campus)
                            ->select('sub_offered.subCode', 'sub_offered.subSec', 'sub_offered.schlyear', 'sub_offered.semester', 'sub_offered.campus', 'sub_offered.id as soschid', 'subjects.*')
                            ->orderBy('subjects.sub_name', 'ASC')
                            ->orderBy('sub_offered.subSec', 'ASC')
                            ->get();

        return response()->json($progsuboff);
    }

    public function getFacultyClassSched(Request $request)
    {
        $campus = Auth::guard('web')->user()->campus;

        $progfaculty = Faculty::where('campus', $campus)
                        ->orderBy('lname', 'ASC')
                        ->get();

        return response()->json($progfaculty);
    }

    public function getRoomClassSched(Request $request)
    {
        $campus = Auth::guard('web')->user()->campus;

        $progroom = Room::where('campus', $campus)
                        ->orderBy('room_name', 'ASC')
                        ->get();

        return response()->json($progroom);
    }

    public function classSchedCreate(Request $request)
{
    if ($request->isMethod('post')) {
        $request->validate([
            'schedday' => 'required',
            'start_time' => 'required|string',
            'end_time' => 'required|string',
            'progcodename' => 'required|string',
            'progcodesection' => 'required|string',
            'schlyear' => 'required|string',
            'semester' => 'required|string',
            'postedBy' => 'required|string',
            'campus' => 'required|string',
            'subject_id' => 'required|string',
            'faculty_id' => 'required|string',
            'room_id' => 'required|string',
        ]);

        $day = $request->input('schedday');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $progcodename = $request->input('progcodename');
        $progcodesection = $request->input('progcodesection');
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = $request->input('campus');
        $subject_id = $request->input('subject_id');
        $faculty_id = $request->input('faculty_id');
        $room_id = $request->input('room_id');
        $remarks = $request->input('remarks');

        $conflicts = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                    ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                    ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                    ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                    ->where('scheduleclass.schedday', $day)
                    ->where('scheduleclass.schlyear', $schlyear)
                    ->where('scheduleclass.semester', $semester)
                    ->where('scheduleclass.campus', $campus)
                    ->where(function($query) use ($startTime, $endTime) {
                        $query->whereBetween('start_time', [$startTime, $endTime])
                              ->orWhereBetween('end_time', [$startTime, $endTime])
                              ->orWhere(function($query) use ($startTime, $endTime) {
                                  $query->where('start_time', '<=', $startTime)
                                        ->where('end_time', '>=', $endTime);
                              });
                    })
                    ->where(function($query) use ($progcodename, $progcodesection, $subject_id, $faculty_id, $room_id) {
                        $query->where('progcodename', $progcodename)
                              ->where('progcodesection', $progcodesection)
                              ->orWhere('subject_id', $subject_id)
                              ->orWhere('faculty_id', $faculty_id)
                              ->orWhere('room_id', $room_id);
                    })
                    ->select('sub_offered.subSec', 'scheduleclass.*', 'subjects.sub_name', 'faculty.lname', 'faculty.fname')
                    ->get();

        if ($conflicts->isNotEmpty()) {
            $conflictDetails = $conflicts->map(function($conflict) {
                return [
                    'subject' => $conflict->sub_name,
                    'course' => $conflict->subSec,
                    'faculty' => $conflict->lname,
                    'room' => $conflict->room_name,
                ];
            });
            return response()->json(['error' => true, 'message' => 'Schedule conflict detected.', 'conflicts' => $conflictDetails], 409);
        }

        try {
            SetClassSchedule::create([
                'schedday' => $day,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'progcodename' => $progcodename,
                'progcodesection' => $progcodesection,
                'schlyear' => $schlyear,
                'semester' => $semester,
                'postedBy' => $request->input('postedBy'),
                'campus' => $campus,
                'subject_id' => $subject_id,
                'faculty_id' => $faculty_id,
                'room_id' => $room_id,
                'remarks' => $remarks,
            ]);

            return response()->json(['success' => true, 'message' => 'Class Schedule Set successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to set Class Schedule'], 404);
        }
    }
}


    public function fetchSchedule(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $progCod = $request->query('progCod');
        $campus = Auth::guard('web')->user()->campus;

        $parts = preg_split('/[\+\s]/', $progCod);
        $progCodPart = $parts[0];
        $progCodSuffix = isset($parts[1]) ? $parts[1] : null;
        $program = EnPrograms::whereRaw('LOWER(progCod) = ?', [strtolower($progCodPart)])->first();

        $progAcronym = $program ? $program->progAcronym : 'N/A';

        $schedule = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                        ->where('scheduleclass.schlyear', '=', $schlyear)
                        ->where('scheduleclass.semester', '=', $semester)
                        ->where('scheduleclass.progcodename', $progCodPart)
                        ->where('scheduleclass.progcodesection', $progCodSuffix)
                        ->where('scheduleclass.campus', $campus)
                        ->select('sub_offered.subSec', 'scheduleclass.*', 'subjects.sub_name', 'faculty.lname', 'faculty.fname', 'rooms.room_name')
                        ->get();

        return response()->json($schedule);
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
