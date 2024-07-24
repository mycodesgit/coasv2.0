<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use PDF;
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

class SchedRoomController extends Controller
{
    public function roomSchedRead() 
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        
        return view('scheduler.schedule.room_sched', compact('sy'));
    }

    public function roomSchedSetRead(Request $request) 
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
        $room_id = $request->query('room_id');
        $campus = Auth::guard('web')->user()->campus;

        $room = Room::where('rooms.id', '=', $room_id)->first();
        if ($room) {
            $roomName = $room->room_name;
        } else {
            $roomName = 'Room not found';
        }

        $days = Sday::all()->pluck('dayDesc')->toArray();
        $times = Stime::all()->pluck('timeDesc')->toArray();

        return view('scheduler.schedule.room_schedset', compact('sy', 'roomName', 'days', 'times'));
    }

    public function fetchRoomSchedule(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $room_id = $request->query('room_id');
        $campus = Auth::guard('web')->user()->campus;

        $schedule = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                        ->where('scheduleclass.schlyear', '=', $schlyear)
                        ->where('scheduleclass.semester', '=', $semester)
                        ->where('scheduleclass.room_id', $room_id)
                        ->where('scheduleclass.campus', $campus)
                        ->select('sub_offered.subSec', 'scheduleclass.*', 'subjects.sub_name', 'faculty.lname', 'faculty.fname', 'rooms.room_name')
                        ->get();

        return response()->json($schedule);
    }

    public function printRoomSchedule(Request $request)
    {
        $schlyear = $request->input('schlyear', 'Not Available');
        $semester = $request->input('semester', 'Unknown Semester');
        $room_id = $request->input('room_id', 'Unknown Room');
        $campus = Auth::guard('web')->user()->campus;

        $room = Room::where('rooms.id', '=', $room_id)->first();
        if ($room) {
            $roomName = $room->room_name;
        } else {
            $roomName = 'Room not found';
        }

        $breadcrumbHtml = '
            
            <table style="border: none; width: 100%; font-size: 10pt; background-color: none !important">
                <thead>
                    <tr>
                        <th style="border: none; text-align: left; font-weight: bold; background-color: none !important">
                            <span>Course: ' . htmlspecialchars($roomName) . '</span>
                        </th>
                        <th style="border: none; text-align: left; font-weight: bold; color: #000; background-color: none !important">
                            <span>School Year: ' . htmlspecialchars($schlyear) . '</span>
                        </th>
                        <th style="border: none; text-align: left; font-weight: bold; color: #000; background-color: none !important">
                            <span>Semester: ' . htmlspecialchars($semester) . '</span>
                        </th>
                    </tr>
                </thead>
            </table>
        ';
        $scheduleHtml = $request->input('scheduleHtml');
        $headerImage = asset("template/img/schedclass/schedclassheaderMain.png");

        $html = '
            <html>
                <head>
                    <style>
                        table {
                            width: 100%;
                            border-collapse: collapse;
                            font-size: 8px; /* Reduce font size for better fitting */
                        }
                        th, td {
                            border: 1px solid #000;
                            text-align: center;
                        }
                        th {
                            // background-color: #e9ecef;
                        }
                        .highlighted {
                            background-color: #d9edf7;
                        }
                    </style>
                </head>
                <body>
                    <div align="center" style="margin-top: -20px">
                        <img src="' . $headerImage . '" width="70%">
                    </div>
                    <div align="center">
                        <h3>Room Schedule</h3>
                    </div>
                    <div class="margin-top: 50px">
                    ' . $breadcrumbHtml . '
                    ' . $scheduleHtml . '
                    </div>
                </body>
            </html>';

        $pdf = PDF::loadHTML($html)
            ->setPaper('Legal', 'portrait')
            ->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

        return $pdf->stream('schedule.pdf');
    }
}
