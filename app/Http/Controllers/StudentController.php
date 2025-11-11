<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\StudentType;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\ClassesSubjects;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\FacultyLoad;
use App\Models\ScheduleDB\FacDesignation;
use App\Models\ScheduleDB\Room;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\Stime;
use App\Models\ScheduleDB\Sday;
use App\Models\ScheduleDB\SetClassSchedule;

use App\Models\ScholarshipDB\Scholar;

use App\Models\AssessmentDB\StudentFee;
use App\Models\AssessmentDB\StudentAppraisal;
use App\Models\AssessmentDB\StudPayment;

use App\Models\SettingDB\ConfigureCurrent;

class StudentController extends Controller
{
    public function getGuard()
    {
        if(\Auth::guard('web')->check()) {
            return 'web';
        } elseif(\Auth::guard('faculty')->check()) {
            return 'faculty';
        } elseif(\Auth::guard('kioskstudent')->check()) {
            return 'kioskstudent';
        }
    }

    public function index()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;

        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $campus = $studauth->campus;

        $campusArray = array_map('trim', explode(',', $campus));

        $enrollmentHistory = StudEnrolmentHistory::join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('program_en_history.studentID', $studauth->stud_id)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('program_en_history.campus', 'LIKE', "%$campus%");
                }
            })
            ->select('program_en_history.*', 'coasv2_db_schedule.programs.progAcronym')
            ->orderBy('schlyear', 'ASC')
            ->get();

        return view('student.dashstud', compact('guard', 'studauth', 'enrollmentHistory'));
    }

    public function show()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;

        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $studsub = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('studgrades.studID', $studentowner)
                    ->orderBy('coasv2_db_schedule.sub_offered.id', 'ASC')
                    ->get();

        return view('student.grades.view', compact('guard', 'studauth', 'studsub'));
    }

    public function showaccount()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;

        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $studfees = StudentAppraisal::select('student_appraisal.*')
                    ->where('student_appraisal.studID', $studentowner)
                    ->orderBy('student_appraisal.id', 'ASC')
                    ->get();

        

        return view('student.accnts.viewacct', compact('guard', 'studauth', 'studfees'));
    }

    public function schedclassRead()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $enrollmentHistory = StudEnrolmentHistory::join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('program_en_history.studentID', $studauth->stud_id)
            ->where('program_en_history.campus', $studauth->campus)
            ->select('program_en_history.*', 'coasv2_db_schedule.programs.progAcronym')
            ->orderBy('schlyear', 'ASC')
            ->get();

        return view('student.scheds.viewschedule', compact('studauth', 'enrollmentHistory'));
    }

    public function schedstudentclassShow(Request $request)
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        if (!$studauth) {
            abort(404, 'Student record not found.');
        }

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $progCod = $request->query('progCod');
        $campus = $studauth->campus;

        $parts = preg_split('/[\+\s]/', $progCod);
        $progCodPart = $parts[0];
        $progCodSuffix = isset($parts[1]) ? $parts[1] : null;
        $program = EnPrograms::whereRaw('LOWER(progCod) = ?', [strtolower($progCodPart)])->first();

        $progAcronym = $program ? $program->progAcronym : 'N/A';

        $studclass = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                        ->where('class_enroll.schlyear', '=', $schlyear)
                        ->where('class_enroll.semester', '=', $semester)
                        ->where('class_enroll.progCode', $progCod)
                        ->where('class_enroll.campus', $campus)
                        ->select('programs.progAcronym', 'class_enroll.*')
                        ->get();

        $days = Sday::all()->pluck('dayDesc')->toArray();
        $times = Stime::all()->pluck('timeDesc')->toArray();

        return view('student.scheds.viewscheduleresult', compact('studauth', 'progAcronym', 'progCodPart', 'progCodSuffix', 'days', 'times'));
    }

    public function fetchSchedulestud(Request $request)
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        if (!$studauth) {
            return response()->json(['message' => 'Student record not found'], 404);
        }
        
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $progCod = $request->query('progCod');
        $campus = $studauth->campus;

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

    public function preenrolment()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;

        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $sy = ConfigureCurrent::select('id', 'schlyear', 'semester')
                ->where('set_status', 3)
                ->orderBy('id', 'DESC')
                ->get()
                ->unique('schlyear');

        return view('student.preenrol.prelist', compact('studauth', 'sy'));
    }

    public function preenrolment_searchResult(Request $request)
    {
        $guard = $this->getGuard(); 
        $studentowner = Auth::guard($guard)->user()->studid;
        
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        $studauth = Student::where('stud_id', '=', $studentowner)->first();
        $campus = $studauth->campus;
        $campusArray = array_map('trim', explode(',', $studauth->campus));

        $sy = ConfigureCurrent::select('id', 'schlyear', 'semester')
                ->where('set_status', 3)
                ->orderBy('id', 'DESC')
                ->get()
                ->unique('schlyear');
        
        $studstat = StudentStatus::all();
        $studtype = StudentType::all();
        $studlvl = StudentLevel::all();

        $enrollmentHistory = StudEnrolmentHistory::where('studentID', $studauth->stud_id)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campusItem) {
                    $q->orWhere('campus', 'LIKE', "%$campusItem%");
                }
            })
            ->first();
        
        $selectedStudType = $enrollmentHistory->studType;

        $currentProgCode = $enrollmentHistory ? $enrollmentHistory->progCod : null;

        // Now query classEnrolls, filtered by student's progCod if available
        $classEnrollsQuery = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                    ->join('coasv2_db_enrollment.yearlevel', function($join) {
                        $join->on(\DB::raw('SUBSTRING_INDEX(class_enroll.classSection, "-", 1)'), '=', 'coasv2_db_enrollment.yearlevel.yearsection');
                    })
                    ->select('class_enroll.*', 'class_enroll.id as clid', 'programs.progAcronym', 'programs.progName', 'coasv2_db_enrollment.yearlevel.*')
                    ->where('class_enroll.schlyear', '=', $schlyear)
                    ->where('class_enroll.semester', '=', $semester)
                    ->where('class_enroll.campus', '=', $campus);

        // Filter by progCod if set (only show student's program sections)
        if ($currentProgCode) {
            $classEnrollsQuery->where('programs.progCod', '=', $currentProgCode);
        }

        $classEnrolls = $classEnrollsQuery
                    ->orderBy('programs.progAcronym', 'ASC')
                    ->orderBy('class_enroll.classSection', 'ASC')
                    ->get();

        return view('student.preenrol.prelistview', compact('studauth', 'sy', 'studstat', 'studtype', 'studlvl', 'selectedStudType', 'classEnrolls', 'currentProgCode'));
    }

    public function checkPreEnroll(Request $request)
    {
        try {
            $guard = $this->getGuard();  
            $studentowner = Auth::guard($guard)->user()->studid;
            $studauth = Student::where('stud_id', '=', $studentowner)->first();

            $progCod = $request->input('programCode');
            $schlyear = $request->input('schlyear');
            $semester = $request->input('semester');
            $campus = $studauth->campus;
            $stud_id = $studauth->stud_id;
            $classSection = $request->input('classSection');
            $campusArray = array_map('trim', explode(',', $campus));

            $parts = explode('-', $classSection);
            if (count($parts) !== 2) {
                return response()->json(['error' => 'Invalid classSection format'], 400);
            }
            $studYear = $parts[0];
            $studSec = $parts[1];

            // Count the number of students enrolled in the specified program, school year, semester, and campus
            $enrolledStudents = StudEnrolmentHistory::where('schlyear', $schlyear)
                                ->where('semester', $semester)
                                // ->where('campus', $campus)
                                ->where(function ($q) use ($campusArray) {
                                    foreach ($campusArray as $campus) {
                                        $q->orWhere('campus', 'LIKE', "$campus");
                                    }
                                })
                                ->where('progCod', $progCod)
                                ->where('studYear', $studYear)
                                ->where('studSec', $studSec)
                                ->count();


            // Fetch the classno from the ClassEnroll model
            $classEnroll = ClassEnroll::where('schlyear', $schlyear)
                            ->where('semester', $semester)
                            // ->where('campus', $campus)
                            ->where(function ($q) use ($campusArray) {
                                foreach ($campusArray as $campus) {
                                    $q->orWhere('campus', 'LIKE', "$campus");
                                }
                            })
                            ->where('progCode', $progCod)
                            ->where('classSection', $classSection)
                            ->first();

            if (!$classEnroll) {
                return response()->json(['error' => 'Class not found'], 404);
            }

            $classNo = $classEnroll->classno;


            return response()->json([
                'enrolledStudents' => $enrolledStudents,
                'classNo' => $classNo,
                'isFull' => $enrolledStudents >= $classNo,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function fetchpreenrolSubjects(Request $request)
    {
        $guard = $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $course = $request->input('course');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $studauth->campus;
        $subjects = SubjectOffered::join('subjects', 'sub_offered.subCode', 'subjects.sub_code')
                        ->select('subjects.*', 'sub_offered.*', 'sub_offered.id as subjID')
                        ->where('subSec', $course)
                        ->where('isTemp', 'Yes')
                        ->where('schlyear', $schlyear)
                        ->where('semester', $semester)
                        ->where('campus', $campus)
                        ->orderBy('sub_offered.subCode', 'ASC')
                        ->get();

        return response()->json($subjects);
    }
}
