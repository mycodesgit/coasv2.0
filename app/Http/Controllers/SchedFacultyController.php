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

    // public function getSubjectsClassSchedFac(Request $request)
    // {
    //     $schlyear = $request->input('schlyear');
    //     $semester = $request->input('semester');
    //     $campus = Auth::guard('web')->user()->campus;

    //     $progsuboff = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
    //                         ->where('sub_offered.schlyear', $schlyear)
    //                         ->where('sub_offered.semester', $semester)
    //                         ->where('sub_offered.campus', $campus)
    //                         ->select('sub_offered.subCode', 'sub_offered.subSec', 'sub_offered.schlyear', 'sub_offered.semester', 'sub_offered.campus', 'sub_offered.id as soschid', 'subjects.*')
    //                         ->orderBy('subjects.sub_name', 'ASC')
    //                         ->orderBy('sub_offered.subSec', 'ASC')
    //                         ->get();

    //     return response()->json($progsuboff);
    // }  

    public function getSubjectsClassSchedFac(Request $request)
    {
        try {
            $schlyear = $request->input('schlyear');
            $semester = $request->input('semester');
            $campus = Auth::guard('web')->user()->campus;
            $progCod = $request->input('progCod');

            // Check if progCod is provided
            if (empty($progCod)) {
                return response()->json([]);
            }

            // Parse progCod to get program code and section/year
            // Handle both 'CAF-ABS-002+1-A' and 'CAF-ABS-002 1-A' formats
            $programCode = $progCod;
            $sectionYear = '';
            
            if (strpos($progCod, '+') !== false) {
                $parts = explode('+', $progCod);
                $programCode = $parts[0];
                $sectionYear = isset($parts[1]) ? $parts[1] : '';
            } elseif (strpos($progCod, ' ') !== false) {
                $parts = explode(' ', $progCod);
                $programCode = $parts[0];
                $sectionYear = isset($parts[1]) ? $parts[1] : '';
            }

            // Get the program using the program code
            $program = EnPrograms::where('progCod', $programCode)->first();
            
            if (!$program) {
                return response()->json([]);
            }

            $progAcronym = $program->progAcronym;

            // Build the exact subSec pattern: "BS AGRI BUS 1-A"
            $exactSubSec = $progAcronym . ' ' . $sectionYear;

            // Get subjects that match exactly this subSec
            $progsuboff = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                                ->where('sub_offered.schlyear', $schlyear)
                                ->where('sub_offered.semester', $semester)
                                ->where('sub_offered.campus', $campus)
                                ->where('sub_offered.subSec', $exactSubSec)
                                ->select(
                                    'sub_offered.subCode', 
                                    'sub_offered.subSec', 
                                    'sub_offered.schlyear', 
                                    'sub_offered.semester', 
                                    'sub_offered.campus', 
                                    'sub_offered.id as soschid', 
                                    'subjects.*'
                                )
                                ->orderBy('subjects.sub_name', 'ASC')
                                ->orderBy('sub_offered.subSec', 'ASC')
                                ->get();

            return response()->json($progsuboff);

        } catch (\Exception $e) {
            //\Log::error('Error in getSubjectsClassSchedFac: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
                        
        // Merge schedules with the same merge UUID
        $schedule = $schedule->groupBy(function ($item) {
            return !empty($item->is_merged)
                ? $item->is_merged
                : 'single_'.$item->id;
        })->map(function ($group) {

            $first = $group->first();

            // Combine section names
            $first->subSec = $group->pluck('subSec')
                ->unique()
                ->sort()
                ->implode('/');

            return $first;

        })->values();

        return response()->json($schedule);
    }

    public function facultySchedCreate(Request $request)
    {
        if ($request->isMethod('post')) {
            // Check if this is just a conflict check
            if ($request->has('check_only')) {
                return $this->checkConflictsOnly($request);
            }
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

            $subjectIds = [$subject_id];

            if ($request->boolean('is_merged') && $request->filled('merge_sections')) {
                $subjectIds = array_unique(
                    array_merge($subjectIds, $request->merge_sections)
                );
            }

            // Check for conflicts
            $conflicts = $this->checkAllConflicts(
                $day, $startTime, $endTime, $schlyear, $semester, $campus,
                $progcodename, $progcodesection, $faculty_id, $room_id,
                $subject_id, $subjectIds
            );

            // If there are conflicts and not force saving
            if (!empty($conflicts) && !$request->has('force_save')) {
                $conflictDetails = array_map(function($conflict) {
                    return [
                        'type' => $conflict['type'] ?? 'conflict',
                        'subject' => $conflict['subject'] ?? 'N/A',
                        'course' => $conflict['course'] ?? 'N/A',
                        'faculty' => $conflict['faculty'] ?? 'N/A',
                        'room' => $conflict['room'] ?? 'N/A',
                        'schedday' => $conflict['schedday'] ?? 'N/A',
                        'start_time' => $conflict['start_time'] ?? 'N/A',
                        'end_time' => $conflict['end_time'] ?? 'N/A',
                        'message' => $conflict['message'] ?? 'Conflict detected',
                    ];
                }, $conflicts);

                return response()->json([
                    'error' => true,
                    'message' => 'Schedule conflicts detected',
                    'conflicts' => $conflictDetails
                ], 409);
            }

            try {
                $mergeId = null;
                if ($request->boolean('is_merged')) {
                    $mergeId = (string) Str::uuid();
                }

                foreach ($subjectIds as $sid) {
                    $subjectOffer = SubjectOffered::findOrFail($sid);
                    $parts = preg_split('/[\+\s]/', $subjectOffer->subSec);
                    $section = end($parts);

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
                        'is_merged' => $mergeId,
                        'merge_sections' => implode(',', $subjectIds),
                    ]);
                }

                return response()->json(['success' => true, 'message' => 'Class Schedule Set successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to set Class Schedule'], 404);
            }
        }
    }

    private function checkConflictsOnly($request)
    {
        $day = $request->input('schedday');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = $request->input('campus');
        $progcodename = $request->input('progcodename');
        $progcodesection = $request->input('progcodesection');
        $faculty_id = $request->input('faculty_id');
        $room_id = $request->input('room_id');
        $subject_id = $request->input('subject_id');
        
        $subjectIds = [$subject_id];
        if ($request->boolean('is_merged') && $request->filled('merge_sections')) {
            $subjectIds = array_unique(
                array_merge($subjectIds, $request->merge_sections)
            );
        }

        $conflicts = $this->checkAllConflicts(
            $day, $startTime, $endTime, $schlyear, $semester, $campus,
            $progcodename, $progcodesection, $faculty_id, $room_id,
            $subject_id, $subjectIds
        );

        return response()->json([
            'has_conflicts' => !empty($conflicts),
            'conflict_count' => count($conflicts),
            'conflicts' => $conflicts
        ]);
    }

    private function checkAllConflicts($day, $startTime, $endTime, $schlyear, $semester, $campus, $progcodename, $progcodesection, $faculty_id, $room_id, $subject_id, $subjectIds, $schedule_id = null)
    {
        $conflicts = [];

        // Convert time strings to comparable format (HH:MM:SS)
        function getStartTime($timeRange) {
            $clean = str_replace(['am', 'pm'], '', $timeRange);
            $parts = explode('-', $clean);
            return trim($parts[0]);
        }

        function getEndTime($timeRange) {
            $clean = str_replace(['am', 'pm'], '', $timeRange);
            $parts = explode('-', $clean);
            return trim($parts[1]);
        }

        // Extract times from the incoming data
        $startTimeOnly = getStartTime($startTime);
        $endTimeOnly = getEndTime($endTime);

        // Format for database comparison (24-hour format)
        $startTimeFormatted = date('H:i:s', strtotime($startTimeOnly));
        $endTimeFormatted = date('H:i:s', strtotime($endTimeOnly));

        $existingSchedule = SetClassSchedule::where('progcodename', $progcodename)
            ->where('progcodesection', $progcodesection)
            ->where('schedday', $day)
            ->where('schlyear', $schlyear)
            ->where('semester', $semester)
            ->where('campus', $campus)
            ->where(function($query) use ($startTimeFormatted, $endTimeFormatted) {
                $query->whereRaw("
                    STR_TO_DATE(
                        SUBSTRING_INDEX(scheduleclass.start_time, '-', 1), 
                        '%H:%i'
                    ) < STR_TO_DATE(?, '%H:%i')
                ", [$endTimeFormatted])
                ->whereRaw("
                    STR_TO_DATE(
                        SUBSTRING_INDEX(scheduleclass.end_time, '-', -1), 
                        '%H:%i'
                    ) > STR_TO_DATE(?, '%H:%i')
                ", [$startTimeFormatted]);
            })
            ->first(); // Get the first matching schedule

        if ($existingSchedule) {
            // Skip if it's the same schedule being edited
            if (!$schedule_id || $existingSchedule->id != $schedule_id) {
                // Get the full details with joins
                $conflictDetails = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                    ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                    ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                    ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                    ->where('scheduleclass.id', $existingSchedule->id)
                    ->select(
                        'scheduleclass.*',
                        'sub_offered.subSec',
                        'subjects.sub_name',
                        'faculty.lname',
                        'faculty.fname',
                        'rooms.room_name'
                    )
                    ->first();

                if ($conflictDetails) {
                    $facultyName = trim(($conflictDetails->lname ?? '') . ' ' . ($conflictDetails->fname ?? ''));
                    $roomName = $conflictDetails->room_name ?? 'N/A';
                    $subjectName = $conflictDetails->sub_name ?? 'Unknown Subject';
                    $courseName = $conflictDetails->subSec ?? 'Unknown Course';
                    
                    $conflicts[] = [
                        'type' => 'program_section_conflict',
                        'subject' => $subjectName,
                        'course' => $courseName,
                        'faculty' => $facultyName,
                        'room' => $roomName,
                        'schedday' => $conflictDetails->schedday,
                        'start_time' => $conflictDetails->start_time,
                        'end_time' => $conflictDetails->end_time,
                        'message' => "{$courseName} already has a schedule on {$conflictDetails->schedday} from {$conflictDetails->start_time} to {$conflictDetails->end_time} in room {$roomName} with faculty {$facultyName} for subject {$subjectName}. This section already has a class at this time."
                    ];
                } else {
                    // Fallback if join fails
                    $conflicts[] = [
                        'type' => 'program_section_conflict',
                        'subject' => 'Unknown Subject',
                        'course' => $courseName,
                        'faculty' => 'N/A',
                        'room' => $existingSchedule->room_id ?? 'N/A',
                        'schedday' => $existingSchedule->schedday,
                        'start_time' => $existingSchedule->start_time,
                        'end_time' => $existingSchedule->end_time,
                        'message' => "{$courseName} already has a schedule on {$existingSchedule->schedday} from {$existingSchedule->start_time} to {$existingSchedule->end_time}. This section already has a class at this time."
                    ];
                }
            }
        }


        // 1. Check TIME CONFLICTS - ONLY ON THE SAME DAY
        $timeConflicts = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
            ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
            ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
            ->where('scheduleclass.schedday', $day)
            ->where('scheduleclass.schlyear', $schlyear)
            ->where('scheduleclass.semester', $semester)
            ->where('scheduleclass.campus', $campus)
            ->where('scheduleclass.subject_id', $subject_id)
            ->where('scheduleclass.progcodename', $progcodename)
            ->where('scheduleclass.progcodesection', $progcodesection)
            ->where('scheduleclass.faculty_id', $faculty_id)
            ->where('scheduleclass.room_id', $room_id)
            ->where(function ($query) use ($startTimeFormatted, $endTimeFormatted) {
                $query->whereRaw("
                    STR_TO_DATE(
                        SUBSTRING_INDEX(scheduleclass.start_time, '-', 1), 
                        '%H:%i'
                    ) < STR_TO_DATE(?, '%H:%i')
                ", [$endTimeFormatted])
                ->whereRaw("
                    STR_TO_DATE(
                        SUBSTRING_INDEX(scheduleclass.end_time, '-', -1), 
                        '%H:%i'
                    ) > STR_TO_DATE(?, '%H:%i')
                ", [$startTimeFormatted]);
            });

        if ($schedule_id) {
            $timeConflicts->where('scheduleclass.id', '!=', $schedule_id);
        }

        $timeConflicts = $timeConflicts->select(
                'scheduleclass.*',
                'sub_offered.subSec',
                'subjects.sub_name',
                'faculty.lname',
                'faculty.fname',
                'rooms.room_name'
            )
            ->get();

        if ($timeConflicts->count() > 0) {
            foreach ($timeConflicts as $schedule) {
                $facultyName = trim(($schedule->lname ?? '') . ' ' . ($schedule->fname ?? ''));

                $conflicts[] = [
                    'type' => 'time_conflict',
                    'subject' => $schedule->sub_name,
                    'course' => $schedule->progcodename . ' - ' . $schedule->progcodesection . ' (' . $schedule->subSec . ')',
                    'faculty' => $facultyName,
                    'room' => $schedule->room_name,
                    'schedday' => $schedule->schedday,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'message' => "Same subject ({$schedule->sub_name}) is already scheduled from {$schedule->start_time} to {$schedule->end_time} on {$schedule->schedday}."
                ];
            }
        }

        // 2. Check ROOM CONFLICTS - ONLY ON THE SAME DAY AND TIME
        $roomConflicts = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
            ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
            ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
            ->where('scheduleclass.room_id', $room_id)
            ->where('scheduleclass.schedday', $day)
            ->where('scheduleclass.schlyear', $schlyear)
            ->where('scheduleclass.semester', $semester)
            ->where('scheduleclass.campus', $campus)
            ->where(function ($query) use ($startTimeFormatted, $endTimeFormatted) {
                $query->whereRaw("
                    STR_TO_DATE(
                        SUBSTRING_INDEX(scheduleclass.start_time, '-', 1), 
                        '%H:%i'
                    ) < STR_TO_DATE(?, '%H:%i')
                ", [$endTimeFormatted])
                ->whereRaw("
                    STR_TO_DATE(
                        SUBSTRING_INDEX(scheduleclass.end_time, '-', -1), 
                        '%H:%i'
                    ) > STR_TO_DATE(?, '%H:%i')
                ", [$startTimeFormatted]);
            });

        if ($schedule_id) {
            $roomConflicts->where('scheduleclass.id', '!=', $schedule_id);
        }

        $roomConflicts = $roomConflicts->select(
                'scheduleclass.*',
                'sub_offered.subSec',
                'subjects.sub_name',
                'faculty.lname',
                'faculty.fname',
                'rooms.room_name'
            )
            ->get();

        if ($roomConflicts->count() > 0) {
            foreach ($roomConflicts as $schedule) {
                $facultyName = trim(($schedule->lname ?? '') . ' ' . ($schedule->fname ?? ''));

                $conflicts[] = [
                    'type' => 'room_conflict',
                    'subject' => $schedule->sub_name,
                    'course' => $schedule->subSec,
                    'faculty' => $facultyName,
                    'room' => $schedule->room_name,
                    'schedday' => $schedule->schedday,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'message' => "Room {$schedule->room_name} is already occupied by {$schedule->sub_name} ({$schedule->subSec}) from {$schedule->start_time} to {$schedule->end_time} on {$schedule->schedday}."
                ];
            }
        }

        // 3. Check FACULTY CONFLICTS - ONLY ON THE SAME DAY
        $facultyConflicts = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
            ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
            ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
            ->where('scheduleclass.faculty_id', $faculty_id)
            ->where('scheduleclass.schedday', $day) // ONLY SAME DAY
            ->where('scheduleclass.schlyear', $schlyear)
            ->where('scheduleclass.semester', $semester)
            ->where('scheduleclass.campus', $campus)
            ->whereNotIn('scheduleclass.subject_id', $subjectIds)
            ->where(function($query) use ($startTimeFormatted, $endTimeFormatted) {
                $query->whereRaw("
                    STR_TO_DATE(
                        SUBSTRING_INDEX(scheduleclass.start_time, '-', 1), 
                        '%H:%i'
                    ) < STR_TO_DATE(?, '%H:%i')
                ", [$endTimeFormatted])
                ->whereRaw("
                    STR_TO_DATE(
                        SUBSTRING_INDEX(scheduleclass.end_time, '-', -1), 
                        '%H:%i'
                    ) > STR_TO_DATE(?, '%H:%i')
                ", [$startTimeFormatted]);
            })
            ->select('scheduleclass.*', 'sub_offered.subSec', 'subjects.sub_name', 'faculty.lname', 'faculty.fname', 'rooms.room_name')
            ->get();

        foreach ($facultyConflicts as $schedule) {
            $conflicts[] = [
                'type' => 'faculty_conflict',
                'subject' => $schedule->sub_name,
                'course' => $schedule->subSec,
                'faculty' => $schedule->lname . ' ' . $schedule->fname,
                'room' => $schedule->room_name,
                'schedday' => $schedule->schedday,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'message' => 'Faculty is already scheduled to teach another class at this time on the same day',
            ];
        }

        // 4. Check MERGE CONFLICTS
        if (count($subjectIds) > 1) {
            $mergeErrors = [];
            
            // Log what we're checking
            // \Log::info('Merge Conflict Check:', [
            //     'subject_ids' => $subjectIds,
            //     'day' => $day,
            //     'start_time' => $startTimeFormatted,
            //     'end_time' => $endTimeFormatted,
            //     'room_id' => $room_id,
            //     'faculty_id' => $faculty_id
            // ]);
            
            // ============================================================
            // CHECK 1: Is the TIME and DAY already occupied by ANY schedule?
            // (Check ALL schedules regardless of room, subject, faculty)
            // ============================================================
            $timeOccupied = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                ->where('scheduleclass.schedday', $day)
                ->where('scheduleclass.schlyear', $schlyear)
                ->where('scheduleclass.semester', $semester)
                ->where('scheduleclass.campus', $campus)
                // NO room_id filter!
                // NO faculty_id filter!
                // NO subject_id filter!
                ->where(function($query) use ($startTimeFormatted, $endTimeFormatted) {
                    $query->whereRaw("
                        STR_TO_DATE(
                            SUBSTRING_INDEX(scheduleclass.start_time, '-', 1), 
                            '%H:%i'
                        ) < STR_TO_DATE(?, '%H:%i')
                    ", [$endTimeFormatted])
                    ->whereRaw("
                        STR_TO_DATE(
                            SUBSTRING_INDEX(scheduleclass.end_time, '-', -1), 
                            '%H:%i'
                        ) > STR_TO_DATE(?, '%H:%i')
                    ", [$startTimeFormatted]);
                })
                ->select(
                    'scheduleclass.*',
                    'sub_offered.subSec',
                    'subjects.sub_name',
                    'faculty.lname',
                    'faculty.fname',
                    'rooms.room_name'
                )
                ->first();

            // If ANY schedule exists at this day and time, BLOCK the merge!
            if ($timeOccupied) {
                $facultyName = trim(($timeOccupied->lname ?? '') . ' ' . ($timeOccupied->fname ?? ''));
                $roomName = $timeOccupied->room_name ?? 'N/A';
                $subjectName = $timeOccupied->sub_name ?? 'Unknown Subject';
                $section = $timeOccupied->subSec ?? 'N/A';
                
                $mergeErrors[] = "The time slot {$day} from {$startTime} to {$endTime} is already occupied by {$subjectName} ({$section}) in room {$roomName} with faculty {$facultyName}. Merge cannot proceed!";
            }

            // ============================================================
            // CHECK 2: Is the ROOM occupied at this day and time?
            // ============================================================
            $roomOccupied = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                ->where('scheduleclass.room_id', $room_id)
                ->where('scheduleclass.schedday', $day)
                ->where('scheduleclass.schlyear', $schlyear)
                ->where('scheduleclass.semester', $semester)
                ->where('scheduleclass.campus', $campus)
                ->whereNotIn('scheduleclass.subject_id', $subjectIds)
                ->where(function($query) use ($startTimeFormatted, $endTimeFormatted) {
                    $query->whereRaw("
                        STR_TO_DATE(
                            SUBSTRING_INDEX(scheduleclass.start_time, '-', 1), 
                            '%H:%i'
                        ) < STR_TO_DATE(?, '%H:%i')
                    ", [$endTimeFormatted])
                    ->whereRaw("
                        STR_TO_DATE(
                            SUBSTRING_INDEX(scheduleclass.end_time, '-', -1), 
                            '%H:%i'
                        ) > STR_TO_DATE(?, '%H:%i')
                    ", [$startTimeFormatted]);
                })
                ->select(
                    'scheduleclass.*',
                    'sub_offered.subSec',
                    'subjects.sub_name',
                    'faculty.lname',
                    'faculty.fname',
                    'rooms.room_name'
                )
                ->first();

            if ($roomOccupied) {
                $facultyName = trim(($roomOccupied->lname ?? '') . ' ' . ($roomOccupied->fname ?? ''));
                $roomName = $roomOccupied->room_name ?? 'N/A';
                $subjectName = $roomOccupied->sub_name ?? 'Unknown Subject';
                $section = $roomOccupied->subSec ?? 'N/A';
                
                $mergeErrors[] = "Room {$roomName} is already occupied by {$subjectName} ({$section}) with faculty {$facultyName} from {$roomOccupied->start_time} to {$roomOccupied->end_time} on {$roomOccupied->schedday}. Merge cannot proceed!";
            }

            // ============================================================
            // CHECK 3: Faculty conflict - Is the NEW faculty teaching another class?
            // ============================================================
            $facultyConflict = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                ->where('scheduleclass.faculty_id', $faculty_id)
                ->where('scheduleclass.schedday', $day)
                ->where('scheduleclass.schlyear', $schlyear)
                ->where('scheduleclass.semester', $semester)
                ->where('scheduleclass.campus', $campus)
                ->whereNotIn('scheduleclass.subject_id', $subjectIds)
                ->where(function($query) use ($startTimeFormatted, $endTimeFormatted) {
                    $query->whereRaw("
                        STR_TO_DATE(
                            SUBSTRING_INDEX(scheduleclass.start_time, '-', 1), 
                            '%H:%i'
                        ) < STR_TO_DATE(?, '%H:%i')
                    ", [$endTimeFormatted])
                    ->whereRaw("
                        STR_TO_DATE(
                            SUBSTRING_INDEX(scheduleclass.end_time, '-', -1), 
                            '%H:%i'
                        ) > STR_TO_DATE(?, '%H:%i')
                    ", [$startTimeFormatted]);
                })
                ->select(
                    'scheduleclass.*',
                    'sub_offered.subSec',
                    'subjects.sub_name',
                    'faculty.lname',
                    'faculty.fname',
                    'rooms.room_name'
                )
                ->first();

            if ($facultyConflict) {
                $facultyName = trim(($facultyConflict->lname ?? '') . ' ' . ($facultyConflict->fname ?? ''));
                $subjectName = $facultyConflict->sub_name ?? 'Unknown Subject';
                $section = $facultyConflict->subSec ?? 'N/A';
                $roomName = $facultyConflict->room_name ?? 'N/A';
                
                $mergeErrors[] = "Faculty {$facultyName} is already scheduled to teach {$subjectName} ({$section}) in room {$roomName} from {$facultyConflict->start_time} to {$facultyConflict->end_time} on {$facultyConflict->schedday}. Merge cannot proceed!";
            }

            // ============================================================
            // CHECK 4: Faculty change warning (different faculty)
            // THIS IS A WARNING, NOT A HARD CONFLICT
            // ============================================================
            $facultyChangeCheck = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                ->whereIn('scheduleclass.subject_id', $subjectIds)
                ->where('scheduleclass.schedday', $day)
                ->where('scheduleclass.schlyear', $schlyear)
                ->where('scheduleclass.semester', $semester)
                ->where('scheduleclass.campus', $campus)
                ->where('scheduleclass.faculty_id', '<>', $faculty_id)
                ->where(function($query) use ($startTimeFormatted, $endTimeFormatted) {
                    $query->whereRaw("
                        STR_TO_DATE(
                            SUBSTRING_INDEX(scheduleclass.start_time, '-', 1), 
                            '%H:%i'
                        ) < STR_TO_DATE(?, '%H:%i')
                    ", [$endTimeFormatted])
                    ->whereRaw("
                        STR_TO_DATE(
                            SUBSTRING_INDEX(scheduleclass.end_time, '-', -1), 
                            '%H:%i'
                        ) > STR_TO_DATE(?, '%H:%i')
                    ", [$startTimeFormatted]);
                })
                ->select(
                    'scheduleclass.*',
                    'sub_offered.subSec',
                    'subjects.sub_name',
                    'faculty.lname',
                    'faculty.fname',
                    'rooms.room_name'
                )
                ->first();

            if ($facultyChangeCheck) {
                $facultyName = trim(($facultyChangeCheck->lname ?? '') . ' ' . ($facultyChangeCheck->fname ?? ''));
                $subjectName = $facultyChangeCheck->sub_name ?? 'Unknown Subject';
                $roomName = $facultyChangeCheck->room_name ?? 'Unknown Room';
                $mergeErrors[] = "Warning: Subject {$subjectName} is assigned to a different faculty ({$facultyName}) on {$day} from {$startTime} to {$endTime}. Please verify faculty assignment.";
            }

            $mergedDisplay = [];
            foreach ($subjectIds as $sid) {
                $subjectOffer = SubjectOffered::find($sid);
                if ($subjectOffer) {
                    $subject = Subject::where('sub_code', $subjectOffer->subCode)->first();
                    $subjectName = $subject ? $subject->sub_name : 'Unknown';
                    $mergedDisplay[] = $subjectName . ' (' . $subjectOffer->subSec . ')';
                } else {
                    $mergedDisplay[] = 'Subject ID: ' . $sid;
                }
            }
            $mergedDisplayText = implode(' + ', $mergedDisplay);

            // ============================================================
            // If there are any merge errors, add them to conflicts
            // ============================================================
            if (!empty($mergeErrors)) {
                foreach ($mergeErrors as $error) {
                    $conflicts[] = [
                        'type' => 'merge_conflict',
                        'subject' => 'Merge Conflict',
                        'course' => $mergedDisplayText,
                        'faculty' => $facultyName,
                        'room' => $roomName,
                        'schedday' => $day,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'message' => "🔀 " . $error,
                    ];
                }
            }
        }

        // 5. Check if SAME subject has DIFFERENT faculty on DIFFERENT days
        $facultyChange = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
            ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
            ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
            ->where('scheduleclass.subject_id', $subject_id)
            ->where('scheduleclass.schlyear', $schlyear)
            ->where('scheduleclass.semester', $semester)
            ->where('scheduleclass.progcodename', $progcodename)
            ->where('scheduleclass.progcodesection', $progcodesection)
            ->where('scheduleclass.faculty_id', '<>', $faculty_id)
            ->select(
                'scheduleclass.*', 
                'sub_offered.subSec', 
                'subjects.sub_name',
                'faculty.lname',
                'faculty.fname',
                'rooms.room_name'
            )
            ->first();

        if ($facultyChange) {
            $facultyName = $facultyChange->lname . ' ' . $facultyChange->fname;
            $conflicts[] = [
                'type' => 'faculty_change',
                'subject' => $facultyChange->sub_name,
                'course' => $facultyChange->subSec,
                'faculty' => 'Different Faculty',
                'room' => $facultyChange->room_name ?? 'N/A',
                'schedday' => $facultyChange->schedday,
                'start_time' => $facultyChange->start_time,
                'end_time' => $facultyChange->end_time,
                'message' => 'This subject is assigned to different faculty on different days. Current faculty: ' . $facultyName,
            ];
        }

        return $conflicts;
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
