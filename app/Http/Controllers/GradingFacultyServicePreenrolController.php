<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use App\Mail\FacultyOtpMail;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\PreEnroll;
use App\Models\EnrollmentDB\PreEnrollSubj;
use App\Models\EnrollmentDB\DeleteEnrollmentLogs;
use App\Models\EnrollmentDB\StudHisLog;
use App\Models\EnrollmentDB\StudSubLog;

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

use App\Models\ScholarshipDB\Scholar;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\SigPresVice;
use App\Models\SettingDB\QueueMode;

class GradingFacultyServicePreenrolController extends Controller
{
    public function index()
    {
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        $sy = ConfigureCurrent::select('id', 'schlyear', 'semester')
            ->where('set_status', 2)
            ->orderBy('id', 'DESC')
            ->get();
        
        $queueMode = QueueMode::first();

        return view('grading.gradesheet.faculty.services.preenrolment.facpreenrollist', compact('authfacdesig', 'sy', 'queueMode'));
    }

    public function fetchprestudenrol()
    {
        $campus = Auth::guard('faculty')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $sy = ConfigureCurrent::where('set_status', 2)
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
            ->whereRaw("SUBSTRING_INDEX(preenrol.progCod, '-', 1) = ?", [
                Auth::guard('faculty')->user()->faccollege
            ])
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('preenrol.campus', 'LIKE', "%$campus%");
                }
            })
            ->where('preenrol.status', 1)
            ->orderBy('preenrol.created_at', 'asc')
            ->get();

        return response()->json(['data' => $student], 200);
    }

    public function process(Request $request)
    {
        $stud_id = encrypt($request->query('stud_id'));
        $decrypted = decrypt($stud_id);
        $schlyear  = $request->query('schlyear');
        $semester = $request->query('semester');

        $campus = Auth::guard('faculty')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $student = Student::where('stud_id', $decrypted)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('campus', 'LIKE', "%$campus%");
                }
            })
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $decrypted . '</strong> does not exist.');
        }

        $enrollmentHistory = StudEnrolmentHistory::where('studentID', $decrypted)
            ->where('schlyear', $schlyear)
            ->where('semester', $semester)
            // ->where('campus', $campus)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('campus', 'LIKE', "%$campus%");
                }
            })
            ->first();

        if ($enrollmentHistory) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $decrypted . '</strong> is already enrolled in this semester.');
        }
        return redirect()->route('prelist.store', [
            'stud_id' => encrypt($request->stud_id),
            'schlyear' => $request->schlyear,
            'semester' => $request->semester,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        if(Auth::guard('faculty')->user()->role == 15) 
        {
            $studlvl = StudentLevel::whereIn('id', ['80', '90'])->get();
        } else {
            $studlvl = StudentLevel::where('id', '=', '50')->get();
        }

        $studscholar = Scholar::all();
        $mamisub = MajorMinor::all();
        $studstat = StudentStatus::all();
        $studtype = StudentType::all();
        $shiftrans = StudentShifTrans::all();
        $program = EnPrograms::all();

        $stud_id = decrypt($request->query('stud_id'));
        $schlyear  = decrypt($request->query('schlyear'));
        $semester = decrypt($request->query('semester'));
        $campus = "MC";

        $sy = ConfigureCurrent::select('id', 'schlyear', 'semester')
            ->where('set_status', 3)
            ->orderBy('id', 'DESC')
            ->get();

        $campusArray = array_map('trim', explode(',', $campus));

        // $student = Student::where('stud_id', $stud_id)->where('campus', $campus)->first();
        $student = Student::where('stud_id', $stud_id)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('campus', 'LIKE', "%$campus%");
                }
            })
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }

        $enrollmentHistory = StudEnrolmentHistory::where('studentID', $stud_id)
            ->where('schlyear', $schlyear)
            ->where('semester', $semester)
            // ->where('campus', $campus)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('campus', 'LIKE', "%$campus%");
                }
            })
            ->first();

        if ($enrollmentHistory) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> is already enrolled in this semester.');
        }

        if(Auth::guard('faculty')->user()->role == 15) 
        {
            $classEnrolls = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                    ->join('coasv2_db_enrollment.yearlevel', function($join) {
                        $join->on(\DB::raw('SUBSTRING_INDEX(class_enroll.classSection, "-", 1)'), '=', 'coasv2_db_enrollment.yearlevel.yearsection');
                    })
                    ->select('class_enroll.*', 'class_enroll.id as clid', 'programs.progAcronym', 'programs.progName', 'coasv2_db_enrollment.yearlevel.*')
                    ->where('schlyear', '=', $schlyear)
                    ->where('semester', '=', $semester)
                    ->where('campus', '=', $campus)
                    ->where('class_enroll.progCode', 'LIKE', '%-GSS-%')
                    ->orderBy('programs.progAcronym', 'ASC')
                    ->orderBy('class_enroll.classSection', 'ASC')
                    ->get();
        } else { 
                $classEnrolls = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                    ->join('coasv2_db_enrollment.yearlevel', function($join) {
                        $join->on(\DB::raw('SUBSTRING_INDEX(class_enroll.classSection, "-", 1)'), '=', 'coasv2_db_enrollment.yearlevel.yearsection');
                    })
                    ->select('class_enroll.*', 'class_enroll.id as clid', 'programs.progAcronym', 'programs.progName', 'coasv2_db_enrollment.yearlevel.*')
                    ->where('class_enroll.schlyear', '=', $schlyear)
                    ->where('class_enroll.semester', '=', $semester)
                    ->where('class_enroll.campus', '=', $campus)
                    ->orderBy('programs.progAcronym', 'ASC')
                    ->orderBy('class_enroll.classSection', 'ASC')
                    ->get();
        }

        $subjOffer = SubjectOffered::join('subjects', 'sub_offered.subCode', 'subjects.sub_code')
                        ->select('subjects.*', 'sub_offered.*',)
                        ->where('schlyear', $schlyear)
                        ->where('semester', $semester)
                        ->where('campus', $campus)
                        ->orderBy('subjects.sub_name', 'ASC')
                        ->orderBy('sub_offered.subSec', 'ASC')
                        ->get();
                        
        $subjectCount = $subjOffer->count();

        $selectedScholar = StudEnrolmentHistory::where('studentID', $stud_id)
            ->orderBy('id', 'desc')
            ->value('studSch');

        return view('grading.gradesheet.faculty.services.preenrolment.facpreenrollist_searchresult', compact('authfacdesig', 'sy', 'stud_id', 'schlyear', 'semester', 'studlvl', 'studscholar', 'student', 'semester', 'schlyear', 'program', 'classEnrolls', 'mamisub', 'subjOffer', 'subjectCount', 'studstat', 'studtype', 'shiftrans', 'selectedScholar'));
    }

    public function storeprenrolprocess(Request $request)
    {
        $stud_id = encrypt($request->query('stud_id'));
        $decrypted = decrypt($stud_id);
        $schlyear  = $request->query('schlyear');
        $semester = $request->query('semester');

        $campus = Auth::guard('faculty')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $student = Student::where('stud_id', $decrypted)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('campus', 'LIKE', "%$campus%");
                }
            })
            ->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $decrypted . '</strong> does not exist.');
        }

        $enrollmentHistory = StudEnrolmentHistory::where('studentID', $decrypted)
            ->where('schlyear', $schlyear)
            ->where('semester', $semester)
            // ->where('campus', $campus)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('campus', 'LIKE', "%$campus%");
                }
            })
            ->first();

        if ($enrollmentHistory) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $decrypted . '</strong> is already enrolled in this semester.');
        }
        return redirect()->route('storeprenrolview.store', [
            'stud_id' => encrypt($request->stud_id),
            'schlyear' => encrypt($request->schlyear),
            'semester' => encrypt($request->semester),
        ]);
    }

    public function storeprenrolview(Request $request)
    {
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        if(Auth::guard('faculty')->user()->role == 15) 
        {
            $studlvl = StudentLevel::whereIn('id', ['80', '90'])->get();
        } else {
            $studlvl = StudentLevel::where('id', '=', '50')->get();
        }

        $studscholar = Scholar::all();
        $mamisub = MajorMinor::all();
        $studstat = StudentStatus::all();
        $studtype = StudentType::all();
        $shiftrans = StudentShifTrans::all();
        $program = EnPrograms::all();

        $stud_id = decrypt($request->query('stud_id'));
        $schlyear  = decrypt($request->query('schlyear'));
        $semester = decrypt($request->query('semester'));
        $campus = Auth::guard('faculty')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $syold = ConfigureCurrent::where('set_status', '=', '2')->get();
        $sy = ConfigureCurrent::where('set_status', 2)
            ->first(['schlyear', 'semester']);

        $campusArray = array_map('trim', explode(',', $campus));

        $student = Student::where('stud_id', $stud_id)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('campus', 'LIKE', "%$campus%");
                }
            })
            ->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }
        $programEnHistory = PreEnroll::where('preenrol.studentID', $stud_id)
                ->where('preenrol.schlyear', $sy->schlyear)
                ->where('preenrol.semester', $sy->semester)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('preenrol.campus', 'LIKE', "%$campus%");
                    }
                })
                ->select('preenrol.*')
                ->first(); 

        if (!$programEnHistory) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> not enrolled at this term or school year.');
        }

        $selectedProgValue = $programEnHistory->progCod . ' '. $programEnHistory->studYear . '-' . $programEnHistory->studSec;

        $selectedProgStudLevel = $programEnHistory->studLevel;
        $selectedStudSch = $programEnHistory->studSch;
        $selectedStudMajor = $programEnHistory->studMajor;
        $selectedStudMinor = $programEnHistory->studMinor;
        $selectedStudStatus = $programEnHistory->studStatus;
        $selectedStudType = $programEnHistory->studType;
        $selectedStudTransferee = $programEnHistory->transferee;
        $selectedStudFourPs = $programEnHistory->fourPs ?? 0;
        $selectedStudCourse = $programEnHistory->course;
        $selectedpostedby = $programEnHistory->fname . ' ' . $programEnHistory->lname;


        $subjectsEn = PreEnrollSubj::join('coasv2_db_schedule.sub_offered', 'preenrollsubj.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $sy->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $sy->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('preenrollsubj.studID', '=', $programEnHistory->studentID)
                    ->get();

        $subjectsEnID = PreEnrollSubj::join('coasv2_db_schedule.sub_offered', 'preenrollsubj.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $sy->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $sy->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('preenrollsubj.studID', '=', $programEnHistory->studentID)
                    ->pluck('coasv2_db_schedule.sub_offered.id');
        $subOfferedIds = implode(',', $subjectsEnID->toArray());

        $studsubview = PreEnrollSubj::join('coasv2_db_schedule.sub_offered', 'preenrollsubj.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $sy->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $sy->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('preenrollsubj.studID', '=', $programEnHistory->studentID)
                    ->pluck('preenrollsubj.subjID');
        $studsubenrollIds = implode(',', $studsubview->toArray());

        $studsubviewprimID = PreEnrollSubj::join('coasv2_db_schedule.sub_offered', 'preenrollsubj.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $sy->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $sy->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('preenrollsubj.studID', '=', $programEnHistory->studentID)
                    ->pluck('preenrollsubj.id');
        $studsubenrollIdsprimID = implode(',', $studsubviewprimID->toArray());

        $studsubviewprimIDitfee = PreEnrollSubj::join('coasv2_db_schedule.sub_offered', 'preenrollsubj.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $sy->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $sy->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('preenrollsubj.studID', '=', $programEnHistory->studentID)
                    ->pluck('coasv2_db_schedule.sub_offered.itfee');
        $studsubenrollIdsprimIDitfee = implode(',', $studsubviewprimIDitfee->toArray());

        // Start for studsublogtable
        $subjectsEnIDlog = StudSubLog::join('coasv2_db_schedule.sub_offered', 'studsublog.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $sy->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $sy->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studsublog.studID', '=', $programEnHistory->studentID)
                    ->pluck('coasv2_db_schedule.sub_offered.id');
        $subOfferedIdslog = implode(',', $subjectsEnIDlog->toArray());

        $studsubviewlog = StudSubLog::join('coasv2_db_schedule.sub_offered', 'studsublog.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $sy->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $sy->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studsublog.studID', '=', $programEnHistory->studentID)
                    ->pluck('studsublog.subjID');
        $studsubenrollIdslog = implode(',', $studsubviewlog->toArray());

        $studsubviewprimIDlog = StudSubLog::join('coasv2_db_schedule.sub_offered', 'studsublog.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $sy->schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $sy->semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studsublog.studID', '=', $programEnHistory->studentID)
                    ->pluck('studsublog.id');
        $studsubenrollIdsprimIDlog = implode(',', $studsubviewprimIDlog->toArray());
        // End for studsublogtable

        if(Auth::guard('faculty')->user()->role == 15) 
        {
            $classEnrolls = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                    ->join('coasv2_db_enrollment.yearlevel', function($join) {
                        $join->on(\DB::raw('SUBSTRING_INDEX(class_enroll.classSection, "-", 1)'), '=', 'coasv2_db_enrollment.yearlevel.yearsection');
                    })
                    ->select('class_enroll.*', 'class_enroll.id as clid', 'programs.progAcronym', 'programs.progName', 'coasv2_db_enrollment.yearlevel.*')
                    ->where('schlyear', '=', $sy->schlyear)
                    ->where('semester', '=', $sy->semester)
                    ->where('campus', '=', $campus)
                    ->where('class_enroll.progCode', 'LIKE', '%-GSS-%')
                    ->orderBy('programs.progAcronym', 'ASC')
                    ->orderBy('class_enroll.classSection', 'ASC')
                    ->get();
        } else { 
                $classEnrolls = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                    ->join('coasv2_db_enrollment.yearlevel', function($join) {
                        $join->on(\DB::raw('SUBSTRING_INDEX(class_enroll.classSection, "-", 1)'), '=', 'coasv2_db_enrollment.yearlevel.yearsection');
                    })
                    ->select('class_enroll.*', 'class_enroll.id as clid', 'programs.progAcronym', 'programs.progName', 'coasv2_db_enrollment.yearlevel.*')
                    ->where('class_enroll.schlyear', '=', $sy->schlyear)
                    ->where('class_enroll.semester', '=', $sy->semester)
                    ->where('class_enroll.campus', '=', $campus)
                    ->orderBy('programs.progAcronym', 'ASC')
                    ->orderBy('class_enroll.classSection', 'ASC')
                    ->get();
        }

        $subjOffer = SubjectOffered::join('subjects', 'sub_offered.subCode', 'subjects.sub_code')
                        ->select('subjects.*', 'sub_offered.*',)
                        ->where('schlyear', $sy->schlyear)
                        ->where('semester', $sy->semester)
                        ->where('campus', $campus)
                        ->orderBy('subjects.sub_name', 'ASC')
                        ->orderBy('sub_offered.subSec', 'ASC')
                        ->get();
                        
        $subjectCount = $subjOffer->count();

        return view('grading.gradesheet.faculty.services.preenrolment.facpreenrollist_reqprendingsearchresult', compact('authfacdesig', 'syold', 'sy', 'studlvl', 'studscholar', 'student', 'program', 'classEnrolls', 'mamisub', 'subjOffer', 'subjectCount', 'studstat', 'studtype', 'shiftrans', 'programEnHistory', 'selectedProgValue', 'subjectsEn', 'selectedProgStudLevel', 'selectedStudMajor', 'selectedStudMinor', 'selectedStudStatus', 'selectedStudType', 'selectedStudTransferee', 'selectedStudFourPs', 'selectedStudCourse', 'subOfferedIds', 'studsubenrollIds', 'studsubenrollIdsprimID', 'studsubenrollIdsprimIDitfee', 'subOfferedIdslog', 'studsubenrollIdslog', 'studsubenrollIdsprimIDlog'));
    }

    public function fetchSubjectsOffered(Request $request)
    {
        $course = $request->input('course');
        $schlyear = decrypt($request->query('schlyear'));
        $semester = decrypt($request->query('semester'));
        $campus = Auth::guard('faculty')->user()->campus;
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

    public function coursefetchSubjectsSelect(Request $request)
    {
        $dd = $request->input('dd');
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = Auth::guard('faculty')->user()->campus;

        $subjects = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->select('subjects.*', 'sub_offered.*')
                        ->where('sub_offered.subSec', $dd)
                        ->where('sub_offered.schlyear', $schlyear)
                        ->where('sub_offered.semester', $semester)
                        ->where('sub_offered.campus', $campus)
                        ->orderBy('sub_offered.subCode', 'ASC')
                        ->get();

        return response()->json($subjects);
    }

    public function fetchFeeSubjectsSelect(Request $request)
    {
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = Auth::guard('faculty')->user()->campus;
        $programCode = $request->input('programCode');
        $numericPart = $request->input('numericPart');
        // $campusArray = array_map('trim', explode(',', $campus));

        $data = StudentFee::where('prog_Code', $programCode)
                    ->where('yrlevel', $numericPart)
                    ->where('schlyear', $schlyear)
                    ->where('semester', $semester)
                    ->where('campus', $campus)
                    // ->where(function ($q) use ($campusArray) {
                    //     foreach ($campusArray as $campus) {
                    //         $q->orWhere('campus', 'LIKE', "%$campus%");
                    //     }
                    // })
                    ->get();
        return response()->json($data);
    }

    public function faccheckEnrollment(Request $request)
    {
        try {

            $progCod = $request->input('programCode');
            $schlyear = decrypt($request->input('schlyear'));
            $semester = decrypt($request->input('semester'));
            //$campus = $request->input('campus');
            $campus = Auth::guard('faculty')->user()->campus;
            $stud_id = $request->input('stud_id');
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

    public function studFacEvalEnrollmentCreate(Request $request) 
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

            $schlyear = decrypt($request->input('schlyear'));
            $semester = decrypt($request->input('semester'));
            $campus = $request->input('campus');

            $existingStudEnroll = StudEnrolmentHistory::where('schlyear', $schlyear)
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
                $enrolment = PreEnroll::find($request->input('id'));
                if ($enrolment) {
                    $enrolment->update([
                        'status' => 2,
                    ]);

                    StudEnrolmentHistory::create([
                        'studentID' => $request->input('studentID'),
                        'schlyear' => decrypt($request->input('schlyear')),
                        'semester' => decrypt($request->input('semester')),
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
                        'studSch' => $request->input('studSch'),
                        'studClassID' => $request->input('studClassID'),
                        'postedBy' => $request->input('postedBy'),
                        'confirmBy' => $request->input('confirmBy'),
                        'postedDate' => $request->input('postedDate'),
                        'studType' => $request->input('studType'),
                        'transferee' => $request->input('transferee'),
                        'fourPs' => $request->input('fourPs'),
                    ]);

                    StudHisLog::create([
                        'studentID' => $request->input('studentID'),
                        'schlyear' => decrypt($request->input('schlyear')),
                        'semester' => decrypt($request->input('semester')),
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
                        'studSch' => $request->input('studSch'),
                        'studClassID' => $request->input('studClassID'),
                        'postedBy' => Auth::guard('faculty')->user()->fname . ' ' . Auth::guard('faculty')->user()->lname,
                        'confirmBy' => $request->input('confirmBy'),
                        'postedDate' => $request->input('postedDate'),
                        'studType' => $request->input('studType'),
                        'transferee' => $request->input('transferee'),
                        'fourPs' => $request->input('fourPs'),
                        'encode' => $encode,
                    ]);

                    $subjIDs = $request->input('subjIDs');
                    foreach ($subjIDs as $subjID) {
                        Grade::create([
                            'studID' => $studentID,
                            'subjID' => $subjID,
                            'postedBy' => $request->input('postedBy'),
                            'schlyear' => decrypt($request->input('schlyear')),
                            'semester' => decrypt($request->input('semester')),
                            'campus' => Auth::guard('faculty')->user()->campus,
                        ]);
                    }

                    $subjIDs = $request->input('subjIDs');
                    foreach ($subjIDs as $subjID) {
                        StudSubLog::create([
                            'studID' => $studentID,
                            'subjID' => $subjID,
                            'postedBy' => $request->input('postedBy'),
                            'campus' => Auth::guard('faculty')->user()->campus,
                            'encode' => $encode,
                            'schlyear' => decrypt($request->input('schlyear')),
                            'semester' => decrypt($request->input('semester')),
                            'campus' => Auth::guard('faculty')->user()->campus,
                        ]);
                    }
                } else {   
                    StudEnrolmentHistory::create([
                        'studentID' => $request->input('studentID'),
                        'schlyear' => decrypt($request->input('schlyear')),
                        'semester' => decrypt($request->input('semester')),
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
                        'studSch' => $request->input('studSch'),
                        'studClassID' => $request->input('studClassID'),
                        'postedBy' => $request->input('postedBy'),
                        'confirmBy' => $request->input('confirmBy'),
                        'postedDate' => $request->input('postedDate'),
                        'studType' => $request->input('studType'),
                        'transferee' => $request->input('transferee'),
                        'fourPs' => $request->input('fourPs'),
                    ]);

                    StudHisLog::create([
                        'studentID' => $request->input('studentID'),
                        'schlyear' => decrypt($request->input('schlyear')),
                        'semester' => decrypt($request->input('semester')),
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
                        'studSch' => $request->input('studSch'),
                        'studClassID' => $request->input('studClassID'),
                        'postedBy' => Auth::guard('faculty')->user()->fname . ' ' . Auth::guard('faculty')->user()->lname,
                        'confirmBy' => $request->input('confirmBy'),
                        'postedDate' => $request->input('postedDate'),
                        'studType' => $request->input('studType'),
                        'transferee' => $request->input('transferee'),
                        'fourPs' => $request->input('fourPs'),
                        'encode' => $encode,
                    ]);

                    $subjIDs = $request->input('subjIDs');
                    foreach ($subjIDs as $subjID) {
                        Grade::create([
                            'studID' => $studentID,
                            'subjID' => $subjID,
                            'postedBy' => $request->input('postedBy'),
                            'schlyear' => decrypt($request->input('schlyear')),
                            'semester' => decrypt($request->input('semester')),
                            'campus' => Auth::guard('faculty')->user()->campus,
                        ]);
                    }

                    $subjIDs = $request->input('subjIDs');
                    foreach ($subjIDs as $subjID) {
                        StudSubLog::create([
                            'studID' => $studentID,
                            'subjID' => $subjID,
                            'postedBy' => $request->input('postedBy'),
                            'campus' => Auth::guard('faculty')->user()->campus,
                            'encode' => $encode,
                            'schlyear' => decrypt($request->input('schlyear')),
                            'semester' => decrypt($request->input('semester')),
                            'campus' => Auth::guard('faculty')->user()->campus,
                        ]);
                    }
                }

                return response()->json(['success' => true, 'message' => 'Student Subject Loaded successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Student Subjects'], 404);
            }
        }
    }
}
