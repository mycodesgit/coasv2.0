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
use App\Models\SettingDB\SignatoriesEmp;

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

        $campus = Auth::guard('web')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $fdata = Faculty::where(function ($q) use ($campusArray) {
                        foreach ($campusArray as $campus) {
                            $q->orWhere('faculty.campus', 'LIKE', "%$campus%");
                        }
                    })
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

        $days = Sday::whereIn('id', [1, 2, 3, 4, 5])->pluck('dayDesc')->toArray();
        $times = Stime::whereIn('id', range(1, 26))->pluck('timeDesc')->toArray();

        return view('scheduler.schedule.faculty_schedset', compact('sy', 'facultyName', 'fdata', 'days', 'times'));
    }

    public function getCoursesyearsecFac(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $courses = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
            ->where('class_enroll.semester', $semester)
            ->where('class_enroll.schlyear', $schlyear)
            ->where('class_enroll.campus', $campus)
            ->orderBy('class_enroll.progCode')
            ->orderBy('class_enroll.classSection')
            ->get();

        return response()->json($courses);
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

        // Merge schedules having the same merge UUID
        $schedule = $schedule->groupBy(function ($item) {
            return $item->is_merged ?: 'single_'.$item->id;
        })->map(function ($group) {

            $first = $group->first();

            if ($first->is_merged) {

                $sections = $group->pluck('subSec')
                    ->unique()
                    ->sort()
                    ->values()
                    ->implode('/');

                $first->subSec = $sections;
            }

            return $first;

        })->values();

        return response()->json($schedule);
    }

    public function facultySchedCreate(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'schedday' => 'required',
                'start_time' => 'required|string',
                'end_time' => 'required|string',
                'progcodename' => 'required|string',
                'schlyear' => 'required|string',
                'semester' => 'required|string',
                'postedBy' => 'required|string',
                'campus' => 'required|string',
                'subject_id' => 'required|string',
                'faculty_id' => 'required|string',
                'room_id' => 'required|string',
                'remarks' => 'required|string',
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
                        ->where('scheduleclass.remarks', $request->input('remarks'))
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
                                  ->where('subject_id', $subject_id)
                                  ->where('faculty_id', $faculty_id)
                                  ->where('room_id', $room_id);
                        })
                        ->orWhere(function($query) use ($subject_id, $progcodename, $progcodesection, $faculty_id) {
                            $query->where('subject_id', $subject_id)
                                  ->where('progcodename', $progcodename)
                                  ->where('progcodesection', $progcodesection)
                                  ->where('faculty_id', '<>', $faculty_id);
                        })
                    ->select('sub_offered.subSec', 'scheduleclass.*', 'subjects.sub_name', 'faculty.lname', 'faculty.fname', 'rooms.room_name')
                    ->get();


            $roomConflicts = $conflicts->where('room_id', $room_id);
            $facultyConflicts = $conflicts->where('faculty_id', $faculty_id);
            $subjectConflicts = $conflicts->where('subject_id', $subject_id);

            if ($roomConflicts->isNotEmpty() || $facultyConflicts->isNotEmpty() || $subjectConflicts->isNotEmpty()) {
                $conflictDetails = $conflicts->map(function($conflict) {
                    return [
                        'subject' => $conflict->sub_name,
                        'course' => $conflict->subSec,
                        'faculty' => $conflict->lname,
                        'room' => $conflict->room_name,
                        'schedday' => $conflict->schedday,
                        'start_time' => $conflict->start_time,
                        'end_time' => $conflict->end_time,
                    ];
                });
                return response()->json(['error' => true, 'message' => '', 'conflicts' => $conflictDetails], 409);
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

                // FacultyLoad::create([
                //     'subjectID' => $subject_id,
                //     'facultyID' => $faculty_id,
                //     'remember_token' => Str::random(60),
                // ]);

                return response()->json(['success' => true, 'message' => 'Class Schedule Set successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to set Class Schedule'], 404);
            }
        }
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
                            DB::raw('COALESCE(scheduleclass.is_merged, scheduleclass.id)'),
                            'sub_offered.subCode',
                            'subjects.sub_name',
                            'subjects.sub_title',
                            'subjects.sublecredit',
                            'subjects.sublabcredit',
                            'subjects.sub_unit',
                            'faculty.lname',
                            'faculty.fname',
                            'rooms.room_name'
                        )
                        ->orderBy('sub_offered.subSec')
                        ->get();
        $facloadsched = $facloadsched->map(function ($row) {

            if (!empty($row->is_merged)) {

                $sections = SetClassSchedule::join(
                        'sub_offered',
                        'scheduleclass.subject_id',
                        '=',
                        'sub_offered.id'
                    )
                    ->where('scheduleclass.is_merged', $row->is_merged)
                    ->orderBy('sub_offered.subSec')
                    ->pluck('sub_offered.subSec')
                    ->unique()
                    ->values()
                    ->toArray();

                $row->displaySection = implode('/', $sections);

            } else {

                $row->displaySection = $row->subSec;

            }

            return $row;
        });

        $data = [
            'facultyName' => $facultyName,
            'facloadsched' => $facloadsched
        ];
        $pdf = PDF::loadView('scheduler.schedule.pdf.facultyloadPDF', $data)->setPaper('A4', 'landscape');
        return $pdf->stream();
    }

    public function printFacultySchedule(Request $request)
    {
        $scheduleHtml = $request->input('scheduleHtml');
        $schlyear = $request->input('schlyear', 'Not Available');
        $semester = $request->input('semester', 'Unknown Semester');
        $faculty_id = $request->input('faculty_id', 'Unknown Faculty');
        $campus = Auth::guard('web')->user()->campus;
        

        $faculty = Faculty::where('faculty.id', '=', $faculty_id)->first();
        if ($faculty) {
            $facultyName = $faculty->lname . ', ' . $faculty->fname . ' ' . substr($faculty->mname, 0, 1);
            $facultysigName = $faculty->fname . ' ' . substr($faculty->mname, 0, 1) . '. ' . $faculty->lname;
        } else {
            $facultyName = 'Faculty not found';
            $facultysigName = 'Faculty not found';
        }

        $facDesignateId = FacDesignation::join('college', 'fac_designation.facCollege', '=', 'college.college_abbr')
            ->join('faculty', 'fac_designation.fac_id', '=', 'faculty.id')
            ->where('fac_designation.schlyear', $schlyear)
            ->where('fac_designation.semester', $semester)
            ->where('fac_designation.facCollege', $faculty->faccollege)
            ->where('fac_designation.designation', '=', 'Dean')
            ->first();

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

        $facloadsched = $facloadsched
            ->groupBy(function ($item) {
                // If merged, group by the merge UUID.
                // Otherwise, group by the schedule ID.
                return $item->is_merged ?: 'single_'.$item->id;
            })
            ->map(function ($group) {

                $first = $group->first();

                if (!empty($first->is_merged)) {

                    $sections = $group->pluck('subSec')
                        ->unique()
                        ->sort()
                        ->values()
                        ->implode('/');

                    $first->subSec = $sections;
                }

                return $first;
            })
            ->values();

        $groupedFacloadsched = $facloadsched->groupBy('sub_name');

        $totalUnits = $facloadsched->sum('sub_unit');
        $totalLeCredits = $facloadsched->sum('sublecredit');
        $totalLabCredits = $facloadsched->sum('sublabcredit');
        $totalContactHours = $facloadsched->sum(function($s) {
            return $s->sublecredit + $s->sublabcredit;
        });

        $vice = SignatoriesEmp::where('position', 'Vice President')
            ->first();

        $data = [
            'scheduleHtml' => $scheduleHtml,
            'schlyear' => $schlyear,
            'semester' => $semester,
            'facultyName' => $facultyName,
            'facultysigName' => $facultysigName,
            'groupedFacloadsched' => $groupedFacloadsched,
            'totalUnits' => $totalUnits,
            'totalLeCredits' => $totalLeCredits,
            'totalLabCredits' => $totalLabCredits,
            'totalContactHours' => $totalContactHours,
            'facDesignateId' => $facDesignateId,
            'vice' => $vice,
        ];

        $pdf = PDF::loadView('scheduler.schedule.pdf.schedulefaculty_pdf', $data);
        $pdf->setPaper('Legal', 'portrait');

        return $pdf->stream('schedule.pdf');
    }
}
