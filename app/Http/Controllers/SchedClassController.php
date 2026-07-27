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
            ->orderBy('class_enroll.progCode')
            ->orderBy('class_enroll.classSection')
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

    public function getschedclassplotted(Request $request)
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

        $data = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                    ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                    ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                    ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                    ->where('scheduleclass.schlyear', '=', $schlyear)
                    ->where('scheduleclass.semester', '=', $semester)
                    ->where('scheduleclass.progcodename', $progCodPart)
                    ->where('scheduleclass.progcodesection', $progCodSuffix)
                    ->where('scheduleclass.campus', $campus)
                    ->select('sub_offered.subSec', 'scheduleclass.*', 'subjects.sub_name', 'subjects.sub_title', 'faculty.lname', 'faculty.fname', 'faculty.fname', 'rooms.room_name')
                    ->get();

        foreach ($data as $row) {
            if ($row->is_merged) {
                $row->merged_sections = SetClassSchedule::join(
                        'sub_offered',
                        'scheduleclass.subject_id',
                        '=',
                        'sub_offered.id'
                    )
                    ->where('scheduleclass.is_merged', $row->is_merged)
                    ->pluck('sub_offered.subSec');
            } else {
                $row->merged_sections = [];
            }
        }

        return response()->json([
            'progAcronym' => $progAcronym,
            'data' => $data
        ]);
    }

    // public function getSubjectsClassSched(Request $request)
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

    public function getSubjectsClassSched(Request $request)
    {
        try {
            $schlyear = $request->input('schlyear');
            $semester = $request->input('semester');
            $campus = Auth::guard('web')->user()->campus;
            $progCod = $request->input('progCod');

            if (empty($progCod)) {
                return response()->json([]);
            }

            // Extract program code
            $programCode = preg_split('/[+ ]/', $progCod)[0];
            $sectionYear = '';
            
            if (preg_match('/[+ ](.+)$/', $progCod, $matches)) {
                $sectionYear = $matches[1];
            }

            $program = EnPrograms::where('progCod', $programCode)->first();
            
            if (!$program) {
                return response()->json([]);
            }

            $progAcronym = $program->progAcronym;

            // Use LIKE with wildcard for more flexibility
            $searchPattern = $progAcronym . '%' . $sectionYear;

            $results = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                            ->where('sub_offered.schlyear', $schlyear)
                            ->where('sub_offered.semester', $semester)
                            ->where('sub_offered.campus', $campus)
                            ->where('sub_offered.subSec', 'LIKE', $searchPattern)
                            ->select(
                                'sub_offered.id as soschid',
                                'sub_offered.subCode',
                                'sub_offered.subSec',
                                'sub_offered.schlyear',
                                'sub_offered.semester',
                                'sub_offered.campus',
                                'subjects.sub_name',
                            )
                            ->orderBy('subjects.sub_name', 'ASC')
                            ->get();

            return response()->json($results);

        } catch (\Exception $e) {
            //\Log::error('Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getFacultyClassSched(Request $request)
    {
        $campus = Auth::guard('web')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $progfaculty = Faculty::where(function ($q) use ($campusArray) {
                            foreach ($campusArray as $campus) {
                                $q->orWhere('faculty.campus', 'LIKE', "%$campus%");
                            }
                        })
                        ->orderBy('lname', 'ASC')
                        ->get();

        return response()->json($progfaculty);
    }

    public function getRoomClassSched(Request $request)
    {
        $campus = Auth::guard('web')->user()->campus;

        $progroom = Room::where('campus', $campus)
                        ->orderBy('room_name', 'ASC')
                        ->where('status', '=', 1)
                        ->get();

        return response()->json($progroom);
    }

    public function getAllSubjectsForMerge(Request $request)
    {
        try {
            $schlyear = $request->input('schlyear');
            $semester = $request->input('semester');
            $campus = Auth::guard('web')->user()->campus;

            // Get ALL subjects (all programs and sections) for the given year and semester
            $results = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                                ->where('sub_offered.schlyear', $schlyear)
                                ->where('sub_offered.semester', $semester)
                                ->where('sub_offered.campus', $campus)
                                ->select(
                                    'sub_offered.id as soschid',
                                    'sub_offered.subCode',
                                    'sub_offered.subSec',
                                    'sub_offered.schlyear',
                                    'sub_offered.semester',
                                    'sub_offered.campus',
                                    'subjects.sub_name'
                                )
                                ->orderBy('subjects.sub_name', 'ASC')
                                ->orderBy('sub_offered.subSec', 'ASC')
                                ->get();

            return response()->json($results);

        } catch (\Exception $e) {
            \Log::error('Error in getAllSubjectsForMerge: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function classSchedCreate(Request $request)
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
                'progcodesection' => 'required|string',
                'schlyear' => 'required|string',
                'semester' => 'required|string',
                'postedBy' => 'required|string',
                'campus' => 'required|string',
                'subject_id' => 'required|string',
                'faculty_id' => 'required|string',
                'room_id' => 'required|string',
                'remarks' => 'required|string',
                'is_merged' => 'nullable|boolean',
                'merge_sections' => 'nullable|array',
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

            // Create the schedule
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
                        'progcodesection' => $section,
                        'schlyear' => $schlyear,
                        'semester' => $semester,
                        'postedBy' => $request->postedBy,
                        'campus' => $campus,
                        'subject_id' => $sid,
                        'faculty_id' => $faculty_id,
                        'room_id' => $room_id,
                        'remarks' => $remarks,
                        'is_merged' => $mergeId,
                        'merge_sections' => implode(',', $subjectIds),
                    ]);
                }

                return response()->json(['success' => true, 'message' => 'Class Schedule Set successfully'], 200);
            } catch (\Exception $e) {
                Log::error('Schedule creation error: ' . $e->getMessage());
                return response()->json(['error' => true, 'message' => 'Failed to set Class Schedule: ' . $e->getMessage()], 404);
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
    private function parseTimeRange($timeRange) {
        // Remove any extra spaces
        $timeRange = trim($timeRange);
        
        // Split by hyphen
        $parts = explode('-', $timeRange);
        $start = trim($parts[0]);
        $end = trim($parts[1]);
        
        // Check if end contains AM/PM
        $hasAmPm = preg_match('/(am|pm)$/i', $end, $matches);
        
        if ($hasAmPm) {
            $amPm = strtolower($matches[1]);
            
            // Remove AM/PM from end time
            $end = trim(str_ireplace(['am', 'pm'], '', $end));
            
            // If start doesn't have AM/PM, inherit from end
            if (!preg_match('/(am|pm)$/i', $start)) {
                $start = trim($start) . $amPm;
            }
            
            // End time inherits the AM/PM
            $end = $end . $amPm;
        }
        
        return [
            'start' => $start,
            'end' => $end
        ];
    }

    private function checkAllConflicts($day, $startTime, $endTime, $schlyear, $semester, $campus, $progcodename, $progcodesection, $faculty_id, $room_id, $subject_id, $subjectIds, $schedule_id = null)
    {
        $conflicts = [];

        // Parse time range properly with AM/PM context
        $timeParts = $this->parseTimeRange($startTime . '-' . $endTime);

        // Convert to 24-hour format
        $startTimeFormatted = date('H:i:s', strtotime($timeParts['start']));
        $endTimeFormatted = date('H:i:s', strtotime($timeParts['end']));


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

    public function printSchedule(Request $request)
    {
        $scheduleHtml = $request->input('scheduleHtml');
        $progCod = $request->input('progCod', 'Not Available');
        $schlyear = $request->input('schlyear', 'Not Available');
        $semester = $request->input('semester', 'Unknown Semester');

        $parts = preg_split('/[\+\s]/', $progCod);
        $progCodPart = $parts[0];
        $progCodSuffix = isset($parts[1]) ? $parts[1] : null;
        $program = EnPrograms::whereRaw('LOWER(progCod) = ?', [strtolower($progCodPart)])->first();

        $progAcronym = $program ? $program->progAcronym : 'N/A';

        $data = [
            'scheduleHtml' => $scheduleHtml,
            'schlyear' => $schlyear,
            'semester' => $semester,
            'progAcronym' => $progAcronym,
            'progCodSuffix' => $progCodSuffix,
        ];

        $pdf = PDF::loadView('scheduler.schedule.pdf.schedule_pdf', $data);
        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('schedule.pdf');
    }

    public function schedclassplottedDelete($id) 
    {
        $schedplot = SetClassSchedule::find($id);
        $schedplot->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }
}
