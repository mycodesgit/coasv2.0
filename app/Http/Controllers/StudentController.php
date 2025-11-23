<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\AdmissionDB\User;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\KioskUser;
use App\Models\EnrollmentDB\StudHisLog;
use App\Models\EnrollmentDB\PreEnroll;
use App\Models\EnrollmentDB\PreEnrollSubj;
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

use App\Models\SettingDB\ChatMessage;
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
        
        $campus = "MC";
        $campusArray = array_map('trim', explode(',', $campus));

        $sy = ConfigureCurrent::select('id', 'schlyear', 'semester')
                ->where('set_status', 3)
                ->orderBy('id', 'DESC')
                ->get()
                ->unique('schlyear');
        
        $sypre = ConfigureCurrent::select('id', 'schlyear', 'semester')
                ->where('set_status', 3)
                ->orderBy('id', 'DESC')
                ->get()
                ->unique('schlyear')
                ->first();
        
        $prewait  = PreEnroll::where('studentID', '=', $studentowner)
                    ->where('schlyear', '=', $sypre->schlyear)
                    ->where('semester', '=', $sypre->semester)
                    ->whereIn('status', [1])
                    ->first();

        $prewaitreg  = StudEnrolmentHistory::where('studentID', '=', $studentowner)
                    ->where('schlyear', '=', $sypre->schlyear)
                    ->where('semester', '=', $sypre->semester)
                    ->where('status', 1)
                    ->first();

        $preconfirmenrollreg  = StudEnrolmentHistory::where('studentID', '=', $studentowner)
                    ->where('schlyear', '=', $sypre->schlyear)
                    ->where('semester', '=', $sypre->semester)
                    ->where('status', 3)
                    ->first();

        $preofficialenrollreg  = StudEnrolmentHistory::where('studentID', '=', $studentowner)
                    ->where('schlyear', '=', $sypre->schlyear)
                    ->where('semester', '=', $sypre->semester)
                    ->where('status', 2)
                    ->first();

        $student = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                    ->join('coasv2_db_scholarship.scholarship', 'program_en_history.studSch', '=', 'coasv2_db_scholarship.scholarship.id')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->select('students.*', 'program_en_history.*', 'coasv2_db_scholarship.scholarship.*', 'coasv2_db_schedule.programs.progAcronym')
                    ->where('program_en_history.schlyear',  $sypre->schlyear)
                    ->where('program_en_history.semester',  $sypre->semester)
                    // ->where('program_en_history.campus',  $campus)
                    ->where(function ($q) use ($campusArray) {
                        foreach ($campusArray as $campus) {
                            $q->orWhere('program_en_history.campus', 'LIKE', "$campus");
                        }
                    })
                    ->where('program_en_history.studentID', $studentowner)->first();

        $programEnHistory = StudEnrolmentHistory::join('coasv2_db_admission.users', 'program_en_history.postedBy', '=', 'coasv2_db_admission.users.id')
                ->where('program_en_history.studentID', $studentowner)
                ->where('program_en_history.schlyear', $sypre->schlyear)
                ->where('program_en_history.semester', '=', $sypre->semester)
                ->where('program_en_history.campus', '=', $campus)
                ->select('program_en_history.*', 'coasv2_db_admission.users.lname', 'coasv2_db_admission.users.fname', 'coasv2_db_admission.users.id as uid')
                ->first(); 
        //$selectedpostedby = $programEnHistory->fname . ' ' . $programEnHistory->lname;

        $studsub = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('coasv2_db_schedule.sub_offered.schlyear',  $sypre->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester',  $sypre->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus',  $campus)
                    ->where('studgrades.studID', $studentowner)
                    ->orderBy('coasv2_db_schedule.sub_offered.subCode', 'ASC')
                    ->get();

        $studfees = StudentAppraisal::select('student_appraisal.*')
                    ->where('student_appraisal.schlyear',  $sypre->schlyear)
                    ->where('student_appraisal.semester',  $sypre->semester)
                    // ->where('student_appraisal.campus',  $campus)
                    ->where(function ($q) use ($campusArray) {
                        foreach ($campusArray as $campus) {
                            $q->orWhere('student_appraisal.campus', 'LIKE', "%$campus%");
                        }
                    })
                    ->where('student_appraisal.studID', $studentowner)
                    ->orderBy('student_appraisal.account', 'ASC')
                    ->get();

        $studor = StudPayment::select('studpayment.*')
                    ->where('studpayment.studID', $studentowner)
                    ->where('studpayment.schlyear',  $sypre->schlyear)
                    ->where('studpayment.semester',  $sypre->semester)
                    ->get();

        return view('student.preenrol.prelist', compact('studauth', 'sy', 'prewait', 'prewaitreg', 'preconfirmenrollreg', 'preofficialenrollreg', 'student', 'programEnHistory', 'studsub', 'studfees', 'studor'));
    }

    public function preenrolmentfetch()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $campus = "MC";
        $campusArray = array_map('trim', explode(',', $campus));

        $sy = ConfigureCurrent::where('set_status', 3)
            ->first(['schlyear', 'semester']);

        $student = PreEnroll::join('students', 'preenrol.studentID', '=', 'students.stud_id')
            ->leftJoin('coasv2_db_schedule.programs', 'preenrol.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->select(
                'students.*', 
                'preenrol.*', 
                'preenrol.created_at as created_ats', 
                'coasv2_db_schedule.programs.progAcronym'
            )
            ->where('preenrol.schlyear', $sy->schlyear ?? '')
            ->where('preenrol.semester', $sy->semester ?? '')
            ->where('preenrol.studentID', $studauth->stud_id)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('preenrol.campus', 'LIKE', "%$campus%");
                }
            })
            ->whereIn('preenrol.status', [1, 2])
            ->orderBy('preenrol.created_at', 'asc')
            ->get();

        return response()->json(['data' => $student], 200);
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
        $selectedStudStatus = $enrollmentHistory->studStatus;

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

        return view('student.preenrol.prelistview', compact('studauth', 'sy', 'studstat', 'studtype', 'studlvl', 'selectedStudType', 'selectedStudStatus', 'classEnrolls', 'currentProgCode'));
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

    public function studPreEnrollmentCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'studentID' => 'required',
                'schlyear' => 'required',
                'semester' => 'required',
                'campus' => 'required',
                'course' => 'required',
                'progCod' => 'required',
                'studMajor' => 'required',
                'studMinor' => 'required',
                'studLevel' => 'required',
                'studStatus' => 'required',
                'studClassID' => 'required',
                'studType' => 'required',
                'transferee' => 'required',
                'fourPs' => 'required',
            ]);


            $studentID = $request->input('studentID');

            if (empty($studentID)) {
                return response()->json(['error' => true, 'message' => 'Student ID is required'], 400);
            }   

            $schlyear = $request->input('schlyear');
            $semester = $request->input('semester');
            $campus = $request->input('campus');

            $existingStudEnroll = PreEnroll::where('schlyear', $schlyear)
                    ->where('semester', $semester)
                    ->where('campus', $campus)
                    ->where('studentID', $studentID)
                    ->first();

            if ($existingStudEnroll) {
                return response()->json(['error' => true, 'message' => 'Enrollment for this Student ID No. already exists this semester'], 404);
            }

            // Check maxstud attribute
            $subjIDs = $request->input('subjIDs');
            $fullSubjects = [];
            foreach ($subjIDs as $subjID) {
                $subject = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')->find($subjID);
                if ($subject) {
                    $currentEnrollmentCount = Grade::where('subjID', $subjID)->count();
                    if ($currentEnrollmentCount >= $subject->maxstud) {
                        $fullSubjects[] = [
                            //'id' => $subjID,
                            'name' => $subject->sub_name, // Assuming you have a name attribute
                            'section' => $subject->subSec,
                            'maxstud' => $subject->maxstud
                        ];
                    }
                } else {
                    return response()->json(['error' => true, 'message' => 'Subject ID ' . $subjID . ' not found'], 404);
                }
            }

            if (!empty($fullSubjects)) {
                return response()->json(['error' => true, 'message' => 'Some subjects are full', 'fullSubjects' => $fullSubjects], 400);
            }

            $encode = str_replace('-', '', now()->format('Ymd')) .'-'. strtoupper(Str::random(4)) .'-'. str_replace('-', '', $request->input('studentID'));

            try {
                PreEnroll::create([
                    'studentID' => $request->input('studentID'),
                    'schlyear' => $request->input('schlyear'),
                    'semester' => $request->input('semester'),
                    'campus' => $request->input('campus'),
                    'course' => $request->input('course'),
                    'progCod' => $request->input('progCod'),
                    'studMajor' => $request->input('studMajor'),
                    'studMinor' => $request->input('studMinor'),
                    'studLevel' => $request->input('studLevel'),
                    'studYear' => $request->input('studYear'),
                    'studSec' => $request->input('studSec'),
                    'studUnit' => $request->input('studUnit'),
                    'studStatus' => $request->input('studStatus'),
                    'studClassID' => $request->input('studClassID'),
                    'postedDate' => $request->input('postedDate'),
                    'studType' => $request->input('studType'),
                    'transferee' => $request->input('transferee'),
                    'fourPs' => $request->input('fourPs'),
                ]);

                $subjIDs = $request->input('subjIDs');
                foreach ($subjIDs as $subjID) {
                    PreEnrollSubj::create([
                        'studID' => $studentID,
                        'subjID' => $subjID,
                        'campus' => $request->campus,
                    ]);
                }

                return response()->json(['success' => true, 'message' => 'Your Pre-enrollment submitted successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Submit Pre-enrollment'], 404);
            }
        }
    }

    public function rfstudactconfirm(Request $request)
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $campus = "MC";
        $campusArray = array_map('trim', explode(',', $campus));

        $sy = ConfigureCurrent::where('set_status', 3)
            ->first(['schlyear', 'semester']);


        $student = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                    ->join('coasv2_db_scholarship.scholarship', 'program_en_history.studSch', '=', 'coasv2_db_scholarship.scholarship.id')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->select('students.*', 'program_en_history.*', 'coasv2_db_scholarship.scholarship.*', 'coasv2_db_schedule.programs.progAcronym')
                    ->where('program_en_history.schlyear',  $sy->schlyear)
                    ->where('program_en_history.semester',  $sy->semester)
                    // ->where('program_en_history.campus',  $campus)
                    ->where(function ($q) use ($campusArray) {
                        foreach ($campusArray as $campus) {
                            $q->orWhere('program_en_history.campus', 'LIKE', "$campus");
                        }
                    })
                    ->where('program_en_history.studentID', $studentowner)->first();

        $programEnHistory = StudEnrolmentHistory::join('coasv2_db_admission.users', 'program_en_history.postedBy', '=', 'coasv2_db_admission.users.id')
                ->where('program_en_history.studentID', $studentowner)
                ->where('program_en_history.schlyear', $sy->schlyear)
                ->where('program_en_history.semester', '=', $sy->semester)
                ->where('program_en_history.campus', '=', $campus)
                ->select('program_en_history.*', 'coasv2_db_admission.users.lname', 'coasv2_db_admission.users.fname', 'coasv2_db_admission.users.id as uid')
                ->first(); 
        $selectedpostedby = $programEnHistory->fname . ' ' . $programEnHistory->lname;

        $studsub = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('coasv2_db_schedule.sub_offered.schlyear',  $sy->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester',  $sy->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus',  $campus)
                    ->where('studgrades.studID', $studentowner)
                    ->orderBy('coasv2_db_schedule.sub_offered.subCode', 'ASC')
                    ->get();

        $studfees = StudentAppraisal::select('student_appraisal.*')
                    ->where('student_appraisal.schlyear',  $sy->schlyear)
                    ->where('student_appraisal.semester',  $sy->semester)
                    // ->where('student_appraisal.campus',  $campus)
                    ->where(function ($q) use ($campusArray) {
                        foreach ($campusArray as $campus) {
                            $q->orWhere('student_appraisal.campus', 'LIKE', "%$campus%");
                        }
                    })
                    ->where('student_appraisal.studID', $studentowner)
                    ->orderBy('student_appraisal.account', 'ASC')
                    ->get();

        $studor = StudPayment::select('studpayment.*')
                    ->where('studpayment.studID', $studentowner)
                    ->where('studpayment.schlyear',  $sy->schlyear)
                    ->where('studpayment.semester',  $sy->semester)
                    ->get();

        $data = [
            'student' => $student,
            'studsub' => $studsub,
            'studfees' => $studfees,
            'studor' => $studor,
            'selectedpostedby' => $selectedpostedby,
        ];
        $pdf = PDF::loadView('enrollment.studenroll.pdfrf.studRFconfirmation', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();

    }

    public function chatmessagefetch()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $messages = ChatMessage::where('recipient_type', 'student')
                    ->where('recipient_id', $studauth->stud_id)
                    ->orderBy('created_at', 'desc')
                    ->get();

        return response()->json(['data' => $messages], 200);
    }

    public function chatmessageview($id)
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $message = ChatMessage::where('id', $id)
                    ->where('recipient_type', 'student')
                    ->where('recipient_id', $studauth->stud_id)
                    ->first();

        if (!$message) {
            return response()->json(['error' => 'Message not found'], 404);
        }

        // Mark the message as read
        if (!$message->is_read) {
            $message->is_read = true;
            $message->save();
        }

        return response()->json(['data' => $message], 200);
    }

    public function confirmEnrollment(Request $request)
    {
        $guard = $this->getGuard();
        $studentId = Auth::guard($guard)->user()->studid;

        // find authenticated student
        $student = Student::where('stud_id', $studentId)->first();

        // update enrollment history to status 2
        StudEnrolmentHistory::where('studentID', $student->stud_id)
            ->where('schlyear', $request->schlyear)
            ->where('semester', $request->semester)
            ->update([
                'status' => 2
            ]);

        return back()->with('success', 'Enrollment confirmed.');
    }
}
