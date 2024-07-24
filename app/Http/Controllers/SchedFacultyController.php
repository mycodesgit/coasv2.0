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

class SchedFacultyController extends Controller
{
    public function facultySchedRead() 
    {
         $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $fdata = Faculty::where('campus', '=', Auth::user()->campus)
                    ->orderBy('lname', 'asc')
                    ->get();
        return view('scheduler.schedule.faculty_sched', compact('sy', 'fdata'));
    }

    public function facultySchedSetRead(Request $request) 
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
        $faculty_id = $request->query('faculty_id');
        $campus = Auth::guard('web')->user()->campus;


        $fdata = Faculty::where('campus', '=', Auth::user()->campus)
                    ->orderBy('lname', 'asc')
                    ->get();

        $faculty = Faculty::where('faculty.id', '=', $faculty_id)->first();
        if ($faculty) {
            $facultyName = $faculty->fname . ' ' . substr($faculty->mname, 0, 1) . ' ' . $faculty->lname;
        } else {
            $facultyName = 'Faculty not found';
        }

        $days = Sday::all()->pluck('dayDesc')->toArray();
        $times = Stime::all()->pluck('timeDesc')->toArray();

        return view('scheduler.schedule.faculty_schedset', compact('sy', 'facultyName', 'fdata', 'days', 'times'));
    }

    public function getSubjectsClassSchedFac(Request $request)
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

    public function fetchFacultySchedule(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $faculty_id = $request->query('faculty_id');
        $campus = Auth::guard('web')->user()->campus;

        $schedule = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                        ->where('scheduleclass.schlyear', '=', $schlyear)
                        ->where('scheduleclass.semester', '=', $semester)
                        ->where('scheduleclass.faculty_id', $faculty_id)
                        ->where('scheduleclass.campus', $campus)
                        ->select('sub_offered.subSec', 'scheduleclass.*', 'subjects.sub_name', 'faculty.lname', 'faculty.fname', 'rooms.room_name')
                        ->get();

        return response()->json($schedule);
    }

    public function facultyloadPDFTemplate(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $faculty_id = $request->query('faculty_id');
        $campus = Auth::guard('web')->user()->campus;

        $faculty = Faculty::where('faculty.id', '=', $faculty_id)->first();
        if ($faculty) {
            $facultyName = $faculty->fname . ' ' . substr($faculty->mname, 0, 1) . ' ' . $faculty->lname;
        } else {
            $facultyName = 'Faculty not found';
        }

        $facloadsched = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                        ->leftJoin('coasv2_db_enrollment.studgrades', 'sub_offered.id', '=', 'coasv2_db_enrollment.studgrades.subjID')
                        ->where('scheduleclass.schlyear', '=', $schlyear)
                        ->where('scheduleclass.semester', '=', $semester)
                        ->where('scheduleclass.faculty_id', $faculty_id)
                        ->where('scheduleclass.campus', $campus)
                        ->select('sub_offered.subSec', 
                                'sub_offered.subCode', 
                                'scheduleclass.*', 
                                'subjects.sub_name', 
                                'subjects.sub_title',
                                'subjects.sublecredit',  
                                'subjects.sublabcredit', 
                                'subjects.sub_unit', 
                                'faculty.lname', 
                                'faculty.fname', 
                                'rooms.room_name',
                                DB::raw('COUNT(DISTINCT coasv2_db_enrollment.studgrades.studID) as studentCount'))
                        ->groupBy(
                            'sub_offered.subSec',
                            'sub_offered.subCode',
                        )
                        ->orderBy('sub_offered.subSec')
                        ->get();

        $data = [
            'facultyName' => $facultyName,
            'facloadsched' => $facloadsched
        ];
        $pdf = PDF::loadView('scheduler.schedule.pdf.facultyloadPDF', $data)->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    public function printFacultySchedule(Request $request)
    {
        $schlyear = $request->input('schlyear', 'Not Available');
        $semester = $request->input('semester', 'Unknown Semester');
        $faculty_id = $request->input('faculty_id', 'Unknown Faculty');
        $campus = Auth::guard('web')->user()->campus;

        $faculty = Faculty::where('faculty.id', '=', $faculty_id)->first();
        if ($faculty) {
            $facultyName = $faculty->fname . ' ' . substr($faculty->mname, 0, 1) . ' ' . $faculty->lname;
        } else {
            $facultyName = 'Faculty not found';
        }

        $breadcrumbHtml = '
            
            <table style="border: none; width: 100%; font-size: 10pt; background-color: none !important">
                <thead>
                    <tr>
                        <th style="border: none; text-align: left; font-weight: bold; background-color: none !important">
                            <span>Course: ' . htmlspecialchars($facultyName) . '</span>
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
                        <h3>Class Schedule</h3>
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
