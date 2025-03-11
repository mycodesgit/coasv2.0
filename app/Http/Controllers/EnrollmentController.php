<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\UniqueStudentID;
use Illuminate\Support\Facades\Log;

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
use App\Models\EnrollmentDB\DeleteEnrollmentLogs;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

use App\Models\ScholarshipDB\Scholar;

use App\Models\AssessmentDB\StudentFee;
use App\Models\AssessmentDB\StudentAppraisal;
use App\Models\AssessmentDB\StudPayment;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\QueueCounter;
use App\Models\SettingDB\QueueCustomer;
use App\Models\SettingDB\QueueMode;


class EnrollmentController extends Controller
{
    public function index()
    {   
        $grdCode = GradeCode::all();
        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->year;
        $userCampus = Auth::guard('web')->user()->campus;

        // Fetch the active configuration with set_status = 2
        $activeConfig = ConfigureCurrent::where('set_status', 2)->first();
        if (!$activeConfig) {
            return back()->with('error', 'No active school year found.');
        }
        $activeConfigId = $activeConfig->id;
        
        $previousConfig = ConfigureCurrent::where('id', '<', $activeConfigId) // Ensure it's before the current active one
            ->orderBy('id', 'desc') // Get the most recent one
            ->first();

        $schlyearactiveYear = $activeConfig->schlyear;
        $schlyearactive = $activeConfig->schlyear;
        $semesteractive = $activeConfig->semester;
        $prevsemesteractive = $previousConfig->semester;

        $previousSchlyearYear = $previousConfig ? $previousConfig->schlyear : null;

        if (!$previousSchlyearYear) {
            return back()->with('error', 'No previous school year found.');
        }

        if(Auth::guard('web')->check() && Auth::guard('web')->user()->role >= 0 && Auth::guard('web')->user()->role <= 14)
        {
            // Query for the previous school year's first semester
            $collegesFirstSemester = College::join('coasv2_db_enrollment.program_en_history', function ($join) {
                    $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
                })
                ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
                ->where(function ($query) use ($userCampus) {
                    $campuses = explode(', ', $userCampus);
                    foreach ($campuses as $campus) {
                        $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                    }
                })
                ->where('coasv2_db_enrollment.program_en_history.semester', '=', $prevsemesteractive)
                ->where('coasv2_db_enrollment.program_en_history.schlyear', $previousSchlyearYear)
                ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
                ->orderBy('college_name', 'ASC')
                ->select('college.college_abbr', 'college.colcolor', 'coasv2_db_enrollment.program_en_history.semester', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
                ->groupBy('college.id')
                ->get();


            // Query for the current active school year's second semester
            $collegesSecondSemester = College::join('coasv2_db_enrollment.program_en_history', function ($join) {
                    $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
                })
                ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
                ->where(function ($query) use ($userCampus) {
                    $campuses = explode(', ', $userCampus);
                    foreach ($campuses as $campus) {
                        $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                    }
                })
                ->where('coasv2_db_enrollment.program_en_history.semester', '=', $semesteractive)
                ->where('coasv2_db_enrollment.program_en_history.schlyear', $schlyearactiveYear)
                ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
                ->orderBy('college_name', 'ASC')
                ->select('college.college_abbr', 'college.colcolor', 'coasv2_db_enrollment.program_en_history.semester', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
                ->groupBy('college.id')
                ->get();


            $prevenrolmentCounts = [];
            for ($year = 1; $year <= 4; $year++) {
                $prevenrolmentCounts[] = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                    ->where('program_en_history.schlyear', 'LIKE', $previousSchlyearYear)
                    ->where('program_en_history.semester', 'LIKE', $prevsemesteractive)
                    ->where('program_en_history.studYear', '=', $year)
                    ->where('program_en_history.campus', '=', $userCampus)
                    ->count();
            }

            $currenrolmentCounts = [];
            for ($year = 1; $year <= 4; $year++) {
                $currenrolmentCounts[] = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                    ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                    ->where('program_en_history.semester', 'LIKE', $semesteractive)
                    ->where('program_en_history.studYear', '=', $year)
                    ->where('program_en_history.campus', '=', $userCampus)
                    ->count();
            }

            $enrlstudcountfirst = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.studYear', '=', '1')
                                ->where('program_en_history.status',  2)
                                ->where('program_en_history.campus', '=', $userCampus)
                                ->count();


            $enrlstudcountsecond = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.studYear', '=', '2')
                                ->where('program_en_history.campus', '=', $userCampus)
                                ->where('program_en_history.status',  2)
                                ->count();

            $enrlstudcountthird = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.studYear', '=', '3')
                                ->where('program_en_history.campus', '=', $userCampus)
                                ->where('program_en_history.status',  2)
                                ->count();

            $enrlstudcountfourth = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.studYear', '=', '4')
                                ->where('program_en_history.campus', '=', $userCampus)
                                ->where('program_en_history.status',  2)
                                ->count();

            $MainEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.campus', '=', 'MC')
                                ->count();

            $VcEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.campus', '=', 'VC')
                                ->count();
            $SccEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.campus', '=', 'SCC')
                                ->count();

            $HcEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.campus', '=', 'HC')
                                ->count();

            $MpEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.campus', '=', 'MP')
                                ->count();

            $IcEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.campus', '=', 'IC')
                                ->count();

            $CaEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.campus', '=', 'CA')
                                ->count();

            $CcEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.campus', '=', 'CC')
                                ->count();

            $ScEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.campus', '=', 'SC')
                                ->count();

            $HinCEnrollmentCount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                            ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                            ->where('program_en_history.semester', 'LIKE', $semesteractive)
                            ->where('program_en_history.campus', '=', 'HinC')
                            ->count();

            $currunderprogramenrolmentCounts = [];
            $underprogramAcronyms = [];

            // Retrieve the count of students for each program acronym
            $programs = StudEnrolmentHistory::join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->whereNot(function ($query) {
                    $query->where('program_en_history.studentID', 'LIKE', '%G%')
                          ->orWhere('program_en_history.studentID', 'LIKE', '%N%');
                })
                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                ->where('program_en_history.campus', '=', $userCampus)
                //->where('coasv2_db_schedule.programs.progDep', 'LIKE', '%GSS%')
                ->select('coasv2_db_schedule.programs.progAcronym', DB::raw('COUNT(*) as count'))
                ->groupBy('coasv2_db_schedule.programs.progAcronym')
                ->get();

            // Populate the labels and data arrays
            foreach ($programs as $program) {
                $underprogramAcronyms[] = $program->progAcronym;
                $currunderprogramenrolmentCounts[] = $program->count;
            }

            $enrlstudRegularcount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.studStatus', '=', '1')
                                ->where('program_en_history.campus', '=', $userCampus)
                                ->count();


            $enrlstudIrregularcount = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.studStatus', '=', '2')
                                ->where('program_en_history.campus', '=', $userCampus)
                                ->count();
        

            return view('enrollment.index', compact('grdCode', 'collegesFirstSemester', 'collegesSecondSemester', 'currentYear', 'previousYear', 'enrlstudcountfirst', 'enrlstudcountsecond', 'enrlstudcountthird', 'enrlstudcountfourth', 'MainEnrollmentCount', 'VcEnrollmentCount', 'SccEnrollmentCount', 'HcEnrollmentCount', 'MpEnrollmentCount', 'IcEnrollmentCount', 'CaEnrollmentCount', 'CcEnrollmentCount', 'ScEnrollmentCount', 'HinCEnrollmentCount', 'schlyearactive', 'semesteractive', 'schlyearactiveYear', 'previousSchlyearYear', 'prevsemesteractive',  'prevenrolmentCounts', 'currenrolmentCounts', 'currunderprogramenrolmentCounts', 'underprogramAcronyms', 'enrlstudRegularcount', 'enrlstudIrregularcount'));
        } else {

            $gradenrlnewstudcount = StudEnrolmentHistory::whereNot(function ($query) {
                                    $query->where('program_en_history.studentID', 'LIKE', '%G%')
                                          ->orWhere('program_en_history.studentID', 'LIKE', '%N%');
                                })
                                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                                ->where('program_en_history.studYear', '=', '1')
                                ->where('program_en_history.campus', '=', $userCampus)
                                ->count();

            $prevgradenrolmentCounts = [];
            for ($year = 1; $year <= 2; $year++) {
                $prevgradenrolmentCounts[] = StudEnrolmentHistory::whereNot(function ($query) {
                        $query->where('program_en_history.studentID', 'LIKE', '%G%')
                              ->orWhere('program_en_history.studentID', 'LIKE', '%N%');
                    })
                    ->where('program_en_history.schlyear', 'LIKE', $previousSchlyearYear)
                    ->where('program_en_history.semester', 'LIKE', $prevsemesteractive)
                    ->where('program_en_history.studYear', '=', $year)
                    ->where('program_en_history.campus', '=', $userCampus)
                    ->count();
            }

            $currgradenrolmentCounts = [];
            for ($year = 1; $year <= 2; $year++) {
                $currgradenrolmentCounts[] = StudEnrolmentHistory::whereNot(function ($query) {
                        $query->where('program_en_history.studentID', 'LIKE', '%G%')
                              ->orWhere('program_en_history.studentID', 'LIKE', '%N%');
                    })
                    ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                    ->where('program_en_history.semester', 'LIKE', $semesteractive)
                    ->where('program_en_history.studYear', '=', $year)
                    ->where('program_en_history.campus', '=', $userCampus)
                    ->count();
            }

            $currgradprogramenrolmentCounts = [];
            $programAcronyms = [];

            // Retrieve the count of students for each program acronym
            $programs = StudEnrolmentHistory::join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->where(function ($query) {
                    $query->where('program_en_history.studentID', 'LIKE', '%G%')
                          ->orWhere('program_en_history.studentID', 'LIKE', '%N%');
                })
                ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                ->where('program_en_history.semester', 'LIKE', $semesteractive)
                ->where('program_en_history.campus', '=', $userCampus)
                //->where('coasv2_db_schedule.programs.progDep', 'LIKE', '%GSS%')
                ->select('coasv2_db_schedule.programs.progAcronym', DB::raw('COUNT(*) as count'))
                ->groupBy('coasv2_db_schedule.programs.progAcronym')
                ->get();

            // Populate the labels and data arrays
            foreach ($programs as $program) {
                $programAcronyms[] = $program->progAcronym;
                $currgradprogramenrolmentCounts[] = $program->count;
            }

            return view('enrollment.index', compact('prevgradenrolmentCounts', 'currgradenrolmentCounts', 'currgradprogramenrolmentCounts', 'programAcronyms', 'semesteractive', 'schlyearactiveYear', 'previousSchlyearYear', 'prevsemesteractive',));
        }
    }

    public function searchStud()
    {   
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $queueMode = QueueMode::first();

        return view('enrollment.studenroll.index', compact('sy', 'queueMode'));
    }

    public function checkEnrollment(Request $request)
    {
        try {
            //Log::info('checkEnrollment called', $request->all());

            $progCod = $request->input('programCode');
            $schlyear = $request->input('schlyear');
            $semester = $request->input('semester');
            $campus = $request->input('campus');
            $stud_id = $request->input('stud_id');
            $classSection = $request->input('classSection');
            $campusArray = array_map('trim', explode(',', $campus));

            // Log::info('Parameters received', [
            //     'programCode' => $progCod,
            //     'schlyear' => $schlyear,
            //     'semester' => $semester,
            //     'campus' => $campus,
            //     'stud_id' => $stud_id,
            //     'classSection' => $classSection
            // ]);

            // Split classSection into studYear and studSec
            $parts = explode('-', $classSection);
            if (count($parts) !== 2) {
                //Log::error('Invalid classSection format', ['classSection' => $classSection]);
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
                                        $q->orWhere('campus', 'LIKE', "%$campus%");
                                    }
                                })
                                ->where('progCod', $progCod)
                                ->where('studYear', $studYear)
                                ->where('studSec', $studSec)
                                ->count();

            //Log::info('Enrolled students count', ['count' => $enrolledStudents]);

            // Fetch the classno from the ClassEnroll model
            $classEnroll = ClassEnroll::where('schlyear', $schlyear)
                            ->where('semester', $semester)
                            // ->where('campus', $campus)
                            ->where(function ($q) use ($campusArray) {
                                foreach ($campusArray as $campus) {
                                    $q->orWhere('campus', 'LIKE', "%$campus%");
                                }
                            })
                            ->where('progCode', $progCod)
                            ->where('classSection', $classSection)
                            ->first();

            if (!$classEnroll) {
                //Log::error('Class not found', ['programCode' => $progCod, 'classSection' => $classSection]);
                return response()->json(['error' => 'Class not found'], 404);
            }

            $classNo = $classEnroll->classno;

            //Log::info('Class details', ['classNo' => $classNo]);

            return response()->json([
                'enrolledStudents' => $enrolledStudents,
                'classNo' => $classNo,
                'isFull' => $enrolledStudents >= $classNo,
            ]);
        } catch (\Exception $e) {
            //Log::error('Exception occurred', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    public function searchStudEnroll(Request $request)
    {
        if(Auth::guard('web')->user()->role == 15) 
        {
            $studlvl = StudentLevel::whereIn('id', ['80', '90'])->get();
        } else {
            $studlvl = StudentLevel::all();
        }

        $studscholar = Scholar::all();
        $mamisub = MajorMinor::all();
        $studstat = StudentStatus::all();
        $studtype = StudentType::all();
        $shiftrans = StudentShifTrans::all();
        $program = EnPrograms::all();

        $stud_id = $request->stud_id;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

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

        if(Auth::guard('web')->user()->role == 15) 
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
                    ->where('schlyear', '=', $schlyear)
                    ->where('semester', '=', $semester)
                    ->where('campus', '=', $campus)
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
    
        return view('enrollment.studenroll.enrollStudent', compact( 'studlvl', 'studscholar', 'student', 'semester', 'schlyear', 'program', 'classEnrolls', 'mamisub', 'subjOffer', 'subjectCount', 'studstat', 'studtype', 'shiftrans'));
    }

    public function coursefetchSubjects(Request $request)
    {
        $dd = $request->input('dd');
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = Auth::guard('web')->user()->campus;

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

    public function fetchSubjects(Request $request)
    {
        $course = $request->input('course');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
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

    public function editfetchSubjects(Request $request)
    {
        $course = $request->input('course');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $subjects = SubjectOffered::join('subjects', 'sub_offered.subCode', 'subjects.sub_code')
                        ->leftJoin('studgrades', function ($join) {
                            $join->on('program_en_history.studentID', '=', 'studgrades.studID')
                                ->on('sub_offered.id', '=', 'studgrades.subjID');
                        })
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


    public function fetchFeeSubjects(Request $request)
    {
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = $request->input('campus');
        $programCode = $request->input('programCode');
        $numericPart = $request->input('numericPart');
        $campusArray = array_map('trim', explode(',', $campus));

        $data = StudentFee::where('prog_Code', $programCode)
                    ->where('yrlevel', $numericPart)
                    ->where('schlyear', $schlyear)
                    ->where('semester', $semester)
                    // ->where('campus', $campus)
                    ->where(function ($q) use ($campusArray) {
                        foreach ($campusArray as $campus) {
                            $q->orWhere('campus', 'LIKE', "%$campus%");
                        }
                    })
                    ->get();
        return response()->json($data);
    }

    public function studEnrollmentCreate(Request $request) 
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
                'studSch' => 'required',
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

            try {
                StudEnrolmentHistory::create([
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
                    'studSch' => $request->input('studSch'),
                    'studClassID' => $request->input('studClassID'),
                    'postedBy' => $request->input('postedBy'),
                    'confirmBy' => $request->input('confirmBy'),
                    'postedDate' => $request->input('postedDate'),
                    'studType' => $request->input('studType'),
                    'transferee' => $request->input('transferee'),
                    'fourPs' => $request->input('fourPs'),
                ]);

                $subjIDs = $request->input('subjIDs');
                foreach ($subjIDs as $subjID) {
                    Grade::create([
                        'studID' => $studentID,
                        'subjID' => $subjID,
                        'postedBy' => $request->input('postedBy'),
                        'campus' => Auth::guard('web')->user()->campus,
                    ]);
                }

                $fndCodes = $request->input('fndCodes');
                $accntNames = $request->input('accntNames');
                $amntFees = $request->input('amntFees');
                foreach ($fndCodes as $key => $fndCode) {
                    StudentAppraisal::create([
                        'studID' => $request->input('studentID'),
                        'semester' => $request->input('semester'),
                        'schlyear' => $request->input('schlyear'),
                        'campus' => $request->input('campus'),
                        'fundID' => $fndCode,
                        'account' => $accntNames[$key], 
                        'dateAssess' => $request->input('postedDate'),
                        'amount' => $amntFees[$key], 
                        'postedBy' => $request->input('postedBy'),
                    ]);
                }

                return response()->json(['success' => true, 'message' => 'Student Enrolled successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Enroll Student'], 404);
            }
        }
    }

    public function studrfprint(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        // $student = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
        //             ->join('coasv2_db_scholarship.scholarship', 'program_en_history.studSch', '=', 'coasv2_db_scholarship.scholarship.id')
        //             ->join('studgrades', 'program_en_history.studentID', '=', 'studgrades.studID')
        //             ->leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
        //             ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
        //             ->select('students.*', 'program_en_history.*', 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*', 'coasv2_db_scholarship.scholarship.*')
        //             ->where('program_en_history.schlyear',  $schlyear)
        //             ->where('program_en_history.semester',  $semester)
        //             ->where('program_en_history.campus',  $campus)
        //             ->where('program_en_history.studentID', $stud_id)->first();

        $student = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                    ->join('coasv2_db_scholarship.scholarship', 'program_en_history.studSch', '=', 'coasv2_db_scholarship.scholarship.id')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->select('students.*', 'program_en_history.*', 'coasv2_db_scholarship.scholarship.*', 'program_en_history.updated_at as updated_ats', 'coasv2_db_schedule.programs.progAcronym')
                    ->where('program_en_history.schlyear',  $schlyear)
                    ->where('program_en_history.semester',  $semester)
                    ->where('program_en_history.campus',  $campus)
                    ->where('students.campus',  $campus)
                    ->where('program_en_history.studentID', $stud_id)->first();

        $studsub = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('coasv2_db_schedule.sub_offered.schlyear',  $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester',  $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus',  $campus)
                    ->where('studgrades.studID', $stud_id)
                    ->orderBy('coasv2_db_schedule.sub_offered.subCode', 'ASC')
                    ->get();

        $studfees = StudentAppraisal::select('student_appraisal.*')
                    ->where('student_appraisal.schlyear',  $schlyear)
                    ->where('student_appraisal.semester',  $semester)
                    ->where('student_appraisal.campus',  $campus)
                    ->where('student_appraisal.studID', $stud_id)
                    ->orderBy('student_appraisal.account', 'ASC')
                    ->get();

        $studor = StudPayment::select('studpayment.*')
                    ->where('studpayment.studID', $stud_id)
                    ->where('studpayment.schlyear',  $schlyear)
                    ->where('studpayment.semester',  $semester)
                    ->get();

        $data = [
            'student' => $student,
            'studsub' => $studsub,
            'studfees' => $studfees,
            'studor' => $studor
        ];
        
        $pdf = PDF::loadView('enrollment.studenroll.pdfrf.studRF', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function editsearchStud()
    {   
        if (in_array(Auth::guard('web')->user()->campus, ['MC', 'VC', 'HinC', 'CC', 'CA', 'SCC', 'MP', 'SC', 'HC'])) {
            $sy = ConfigureCurrent::select('id', 'schlyear')
                ->whereIn('id', ['18'])
                ->orderBy('id', 'DESC')
                ->get();
            // $sy = ConfigureCurrent::select('id', 'schlyear')
            //     ->whereIn('id', function($query) {
            //         $query->select(DB::raw('MAX(id)'))
            //             ->from('settings_conf')
            //             ->groupBy('schlyear');
            //     })
            //     ->orderBy('id', 'DESC')
            //     ->get();
        }

        // if(Auth::guard('web')->user()->campus == 'CA') {
        //     $sy = ConfigureCurrent::select('id', 'schlyear')
        //         ->whereIn('id', function($query) {
        //             $query->select(DB::raw('MAX(id)'))
        //                 ->from('settings_conf')
        //                 ->groupBy('schlyear');
        //         })
        //         ->orderBy('id', 'DESC')
        //         ->get();
        // }

        $queueMode = QueueMode::first();
            
        return view('enrollment.studenroll.editenroll', compact('sy', 'queueMode'));
    }

    public function editsearchStudRead(Request $request)
    {
        $studlvl = StudentLevel::all();
        $studscholar = Scholar::all();
        $mamisub = MajorMinor::all();
        $studstat = StudentStatus::all();
        $studtype = StudentType::all();
        $shiftrans = StudentShifTrans::all();
        $program = EnPrograms::all();

        $stud_id = $request->stud_id;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $campusArray = array_map('trim', explode(',', $campus));

        $student = Student::where('stud_id', $stud_id)->where('campus', $campus)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }
        $programEnHistory = StudEnrolmentHistory::join('coasv2_db_admission.users', 'program_en_history.postedBy', '=', 'coasv2_db_admission.users.id')
                ->where('program_en_history.studentID', $stud_id)
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', '=', $semester)
                ->where('program_en_history.campus', '=', $campus)
                ->select('program_en_history.*', 'coasv2_db_admission.users.lname', 'coasv2_db_admission.users.fname', 'coasv2_db_admission.users.id as uid')
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
        $selectedpostedby = $programEnHistory->fname . ' ' . $programEnHistory->lname;


        $subjectsEn = Grade::join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studgrades.studID', '=', $programEnHistory->studentID)
                    ->get();

        $subjectsEnID = Grade::join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studgrades.studID', '=', $programEnHistory->studentID)
                    ->pluck('coasv2_db_schedule.sub_offered.id');
        $subOfferedIds = implode(',', $subjectsEnID->toArray());

        $studsubview = Grade::join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studgrades.studID', '=', $programEnHistory->studentID)
                    ->pluck('studgrades.subjID');
        $studsubenrollIds = implode(',', $studsubview->toArray());

        $studsubviewprimID = Grade::join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studgrades.studID', '=', $programEnHistory->studentID)
                    ->pluck('studgrades.id');
        $studsubenrollIdsprimID = implode(',', $studsubviewprimID->toArray());

        $studsubviewprimIDitfee = Grade::join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studgrades.studID', '=', $programEnHistory->studentID)
                    ->pluck('coasv2_db_schedule.sub_offered.itfee');
        $studsubenrollIdsprimIDitfee = implode(',', $studsubviewprimIDitfee->toArray());

        $studEditfees = StudentAppraisal::join('coasv2_db_enrollment.program_en_history', 'student_appraisal.studID', '=', 'coasv2_db_enrollment.program_en_history.studentID')
                    ->select('coasv2_db_enrollment.program_en_history.studentID', 'student_appraisal.*')
                    ->where('student_appraisal.schlyear', '=', $schlyear)
                    ->where('student_appraisal.semester', '=',  $semester)
                    ->where('student_appraisal.campus', '=',  $campus)
                    ->where('student_appraisal.studID', '=', $programEnHistory->studentID)
                    ->orderBy('student_appraisal.account', 'ASC')
                    ->distinct('student_appraisal.fundID')
                    ->get();

        $classEnrolls = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                    ->join('coasv2_db_enrollment.yearlevel', function($join) {
                        $join->on(\DB::raw('SUBSTRING_INDEX(class_enroll.classSection, "-", 1)'), '=', 'coasv2_db_enrollment.yearlevel.yearsection');
                    })
                    ->select('class_enroll.*', 'class_enroll.id as clid', 'programs.progAcronym', 'programs.progName', 'coasv2_db_enrollment.yearlevel.*')
                    ->where('schlyear', '=', $schlyear)
                    ->where('semester', '=', $semester)
                    ->where('campus', '=', $campus)
                    ->orderBy('programs.progAcronym', 'ASC')
                    ->orderBy('class_enroll.classSection', 'ASC')
                    ->get();

        $subjOffer = SubjectOffered::join('subjects', 'sub_offered.subCode', 'subjects.sub_code')
                    ->select('subjects.*', 'sub_offered.*')
                    ->where('schlyear', $schlyear)
                    ->where('semester', $semester)
                    ->where('campus', $campus)
                    ->orderBy('subjects.sub_name', 'ASC')
                    ->get();
                        
        $subjectCount = $subjOffer->count();
    
        return view('enrollment.studenroll.editenroll_searchview', compact( 'studlvl', 'studscholar', 'student', 'semester', 'schlyear', 'program', 'classEnrolls', 'mamisub', 'subjOffer', 'subjectCount', 'studstat', 'studtype', 'shiftrans', 'selectedProgValue', 'selectedProgStudLevel', 'selectedStudSch', 'selectedStudMajor', 'selectedStudMinor', 'selectedStudStatus', 'selectedStudType', 'selectedStudTransferee', 'selectedStudFourPs', 'selectedpostedby', 'subjectsEn', 'subOfferedIds', 'studEditfees', 'programEnHistory', 'studsubenrollIds', 'studsubenrollIdsprimID' ,'studsubenrollIdsprimIDitfee'));
    }

    public function studEnrollmentUpdate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'id' => 'required',
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
                'studSch' => 'required',
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

            $existingStudEnroll = StudEnrolmentHistory::where('schlyear', $schlyear)
                    ->where('semester', $semester)
                    ->where('campus', $campus)
                    ->where('studentID', $studentID)
                    ->where('id', '!=', $request->input('id'))->first();

            if ($existingStudEnroll) {
                return response()->json(['error' => true, 'message' => 'Enrollment for this Student ID No. already exists this semester'], 404);
            }

            // Check maxstud attribute
            $subjIDs = $request->input('subjIDs');
            $fullSubjects = [];
            foreach ($subjIDs as $subjID) {
                $alreadyEnrolled = Grade::where('subjID', $subjID)
                            ->where('studID', $studentID) // Assuming studentID is the column for the student's ID
                            ->exists();
                if (!$alreadyEnrolled) {
                    $subject = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')->find($subjID);
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
            }

            if (!empty($fullSubjects)) {
                return response()->json(['error' => true, 'message' => 'Some subjects are full', 'fullSubjects' => $fullSubjects], 400);
            }

            try {
                $enrolment = StudEnrolmentHistory::findOrFail($request->input('id'));
                $enrolment->update([
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
                    'studSch' => $request->input('studSch'),
                    'studClassID' => $request->input('studClassID'),
                    'postedBy' => $request->input('postedBy'),
                    'confirmBy' => $request->input('confirmBy'),
                    'postedDate' => $request->input('postedDate'),
                    'studType' => $request->input('studType'),
                    'transferee' => $request->input('transferee'),
                    'fourPs' => $request->input('fourPs'),
                ]);

                //$studentID = $request->input('studentID');
                $studID = $request->input('studentID');
                $newSubjIDs = $request->input('subjIDs');
                $currentSubjprimIDs = $request->input('subjprimIDs');
                $primaryIDs = $request->input('primaryIDs');

                // Ensure the inputs are arrays
                $newSubjIDsArray = is_array($newSubjIDs) ? $newSubjIDs : explode(',', $newSubjIDs);
                $currentSubjprimIDsArray = is_array($currentSubjprimIDs) ? $currentSubjprimIDs : explode(',', $currentSubjprimIDs);
                $primaryIDsArray = is_array($primaryIDs) ? $primaryIDs : explode(',', $primaryIDs);

                // Find subjIDs that have been removed
                $removedSubjIDs = array_diff($currentSubjprimIDsArray, $newSubjIDsArray);

                // Delete grades for removed subjIDs
                Grade::where('studID', $studID)
                    ->whereIn('subjID', $removedSubjIDs)
                    ->delete();

                foreach ($newSubjIDsArray as $index => $subjID) {
                    $grade = Grade::where('studID', $studID)
                                  ->where('subjID', $subjID)
                                  ->first();

                    if ($grade) {
                        // Update existing grade
                        $grade->update([
                            'subjID' => $subjID,
                            'subjFgrade' => $request->input('subjFgrade')[$index] ?? '',
                            'subjComp' => $request->input('subjComp')[$index] ?? '',
                            'creditEarned' => $request->input('creditEarned')[$index] ?? '',
                            'status' => $request->input('status')[$index] ?? '',
                            'compstat' => $request->input('compstat')[$index] ?? '',
                            'postedBy' => $request->input('postedBy'),
                        ]);
                    } else {
                        // Create new grade
                        Grade::create([
                            'studID' => $studID,
                            'subjID' => $subjID,
                            'subjFgrade' => $request->input('subjFgrade')[$index] ?? '',
                            'subjComp' => $request->input('subjComp')[$index] ?? '',
                            'creditEarned' => $request->input('creditEarned')[$index] ?? 0,
                            'status' => $request->input('status')[$index] ?? '',
                            'compstat' => $request->input('compstat')[$index] ?? '',
                            'postedBy' => $request->input('postedBy'),
                            'campus' => Auth::guard('web')->user()->campus,
                        ]);
                    }
                }


                $studentID = $request->input('studentID');
                $postedBy = $request->input('postedBy');

                $fndCodes = $request->input('fndCodes');
                $accntNames = $request->input('accntNames');
                $amntFees = $request->input('amntFees');

                $primIDs = $request->input('primIDs');

                // if ($primIDs && $fndCodes && $accntNames && $amntFees) {
                //     foreach ($primIDs as $index => $primID) {
                //         // Find the record with the given primary key ID
                //         $studappfees = StudentAppraisal::find($primID);

                //         if ($studappfees) {
                //             // Update the existing record
                //             $studappfees->update([
                //                 'fundID' => $fndCodes[$index],
                //                 'account' => $accntNames[$index],
                //                 'amount' => $amntFees[$index],
                //                 'postedBy' => $postedBy,
                //             ]);
                //         }
                //     }
                // }
                
                if ($fndCodes && $accntNames && $amntFees) {
                    foreach ($fndCodes as $index => $fndCode) {
                        $account = $accntNames[$index];
                        $amount = $amntFees[$index];
                        $primID = $primIDs[$index] ?? null; // Handle cases where primID might not be set

                        // \Log::info('Processing:', [
                        //     'primID' => $primID,
                        //     'fundID' => $fndCode,
                        //     'account' => $account,
                        //     'amount' => $amount,
                        // ]);

                        if ($primID) {
                            // Find and update the existing record
                            $studappfees = StudentAppraisal::find($primID);

                            if ($studappfees) {
                                $studappfees->update([
                                    'fundID' => $fndCode,
                                    'account' => $account,
                                    'amount' => $amount,
                                    'postedBy' => $postedBy,
                                ]);
                            }
                        } else {
                            // Insert new record
                            StudentAppraisal::create([
                                'studID' => $request->input('studentID'),
                                'semester' => $request->input('semester'),
                                'schlyear' => $request->input('schlyear'),
                                'campus' => $request->input('campus'),
                                'fundID' => $fndCode,
                                'account' => $account,
                                'dateAssess' => $request->input('postedDate'),
                                'amount' => $amount,
                                'postedBy' => $request->input('postedBy'),
                            ]);
                        }
                    }
                }


                return response()->json(['success' => true, 'message' => 'Student Enrolled successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Enroll Student'], 404);
            }
        }
    }

    public function deleteAllRecords(Request $request)
    {
        $programEnHistoryId = $request->input('programEnHistoryId');
        $studentAppraisalIds = explode(',', $request->input('studentAppraisalIds'));
        $stuGradesIds = explode(',', $request->input('stuGradesIds'));
        
        if ($programEnHistoryId) {
            $enrollmentHistory = StudEnrolmentHistory::find($programEnHistoryId);
            
            if ($enrollmentHistory) {
                DeleteEnrollmentLogs::create([
                    'delstudentID' => $enrollmentHistory->studentID,
                    'delMC' => $enrollmentHistory->campus,
                    'delemployeename' => Auth::guard('web')->user()->fname . ' ' . Auth::guard('web')->user()->lname,
                    'delsemester' => $enrollmentHistory->semester,
                    'delschlyear' => $enrollmentHistory->schlyear,
                ]);
                $enrollmentHistory->delete();
            }
        }

        if (!empty($studentAppraisalIds)) {
            StudentAppraisal::whereIn('id', $studentAppraisalIds)->delete();
        }

        if (!empty($stuGradesIds)) {
            Grade::whereIn('id', $stuGradesIds)->delete();
        }

        return response()->json(['success' => true, 'message' => 'Deleted Successfully', 'redirect_url' => route('editsearchStud')]);
    }

    public function getNextQueue(Request $request)
    {
        $counterId = $request->input('counter_id');

        $counter = QueueCounter::where('useridlog', '=', Auth::guard('web')->user()->id)->first();

        if (!$counter) {
            return response()->json([
                'success' => false,
                'message' => 'No counter found for the current user.',
            ]);
        }

        $queueNumber = QueueCustomer::where('status', 'waiting')
            ->where('catname', $counter->category) 
            ->orderBy('id', 'ASC')
            ->first();

        if ($queueNumber) {
            $queueNumber->update(['status' => 'serving']);

            $counter->update([
                'activeidnumber' => $queueNumber->id,
                'currentid' => $queueNumber->id,
            ]);

            return response()->json([
                'success' => true,
                'queue_number' => $queueNumber->queue_number,
                'counter_window' => $counter->windowname, 
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No more queues available for this counter.',
        ]);
    }


    public function getCallQueue(Request $request)
    {
        $counterId = $request->input('counter_id'); 


        $queue = QueueCustomer::join('counters', 'customers.id', '=', 'counters.activeidnumber')
            ->join('coasv2_db_admission.users', 'counters.useridlog', '=', 'coasv2_db_admission.users.id')
            ->where('counters.useridlog', Auth::guard('web')->user()->id)
            ->first();


        if ($queue) {
            $queue->update([
                'status' => 'serving',
            ]);

            QueueCounter::where('callid', '!=', 0)
                ->update(['callid' => 0]);

            $callqueue = QueueCounter::where('useridlog', Auth::guard('web')->user()->id)->first();

            if ($callqueue) {
                // Only update if activeidnumbercall is 0
                if ($callqueue->callid == 0) {
                    $callqueue->update([
                        'callid' => $queue->activeidnumber, // Set the callid to the activeidnumber
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'queue_number' => $queue->queue_number,  // Return the queue number
                'active_id' => $queue->activeidnumber,   // Return the active ID
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No queue available to call.',
        ]);
    }
}
