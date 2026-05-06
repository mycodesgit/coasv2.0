<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\AdmissionDB\ButtonAccess;
use App\Models\AdmissionDB\User;
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
use App\Models\SettingDB\SigPresVice;
use App\Models\SettingDB\EnrollmentMode;

use App\Models\EvaluationDB\QCEsetting;


class ControlController extends Controller
{
    public function getGuard()
    {
        if(\Auth::guard('web')->check()) {
            return 'web';
        } elseif(\Auth::guard('faculty')->check()) {
            return 'faculty';
        }
    }

    public function master()
    {
        $guard= $this->getGuard();
        return view('layouts.master', compact('guard'));
    }

    public function home()
    {
        $guard= $this->getGuard();

        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->year;
        $userCampus = Auth::guard('web')->user()->campus;

        $activeConfig = Cache::remember("active_config", 300, function () {
            return ConfigureCurrent::where('set_status', 2)->first();
        });

        if (!$activeConfig) {
            return back()->with('error', 'No active school year found.');
        }
        $activeConfigId = $activeConfig->id;
        
        $previousConfig = Cache::remember("previous_config_{$activeConfigId}", 300, function () use ($activeConfigId) {
            return ConfigureCurrent::where('id', '<', $activeConfigId)
                ->orderBy('id', 'desc')
                ->first();
        });

        $schlyearactiveYear = $activeConfig->schlyear;
        $schlyearactive = $activeConfig->schlyear;
        $semesteractive = $activeConfig->semester;
        $prevsemesteractive = $previousConfig->semester;

        $previousSchlyearYear = $previousConfig ? $previousConfig->schlyear : null;

        $cacheKeyPrefix = "dashboardcontrol_{$userCampus}_{$schlyearactive}_{$semesteractive}_";

        if (!$previousSchlyearYear) {
            return back()->with('error', 'No previous school year found.');
        }
        $collegesCurrentSemester = Cache::remember($cacheKeyPrefix . 'currentcolleges', 1000, function () use ($userCampus, $schlyearactive, $semesteractive) {
                return College::join('coasv2_db_enrollment.program_en_history', function ($join) {
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
            ->where('coasv2_db_enrollment.program_en_history.schlyear', $schlyearactive)
            ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
            ->orderBy('college_name', 'ASC')
            ->select(
                'college.id',
                'college.college_abbr',
                'college.college_name',
                'college.colcolor',
                'coasv2_db_enrollment.program_en_history.semester',
                'coasv2_db_enrollment.program_en_history.studYear',
                DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as student_count')
            )
            ->groupBy('college.id', 'coasv2_db_enrollment.program_en_history.studYear')
            ->get()
            ->groupBy('college_abbr');
        });


        return view('control.home', compact('guard', 'collegesCurrentSemester', 'previousSchlyearYear', 'semesteractive', 'schlyearactiveYear'));
    }

    public function masterfaculty()
    {
        $guard= $this->getGuard();
        return view('layouts.master_faculty', compact('guard'));
    }

    public function homefaculty(Request $request)
    {
        $guard= $this->getGuard();

        $activeConfig = ConfigureCurrent::where('set_status', 2)->first();
        if (!$activeConfig) {
            return back()->with('error', 'No active school year found.');
        }
        $activeConfigId = $activeConfig->id;
        
        $previousConfig = ConfigureCurrent::where('id', '<', $activeConfigId)
            ->orderBy('id', 'desc')
            ->first();

        $schlyearactiveYear = $activeConfig->schlyear;
        $schlyearactive = $activeConfig->schlyear;
        $semesteractive = $activeConfig->semester;

        $facultyId = Auth::guard('faculty')->user()->id;
        $campus = Auth::guard('faculty')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $cacheKeyhome = "faculty_home_subload_{$facultyId}_{$campus}_{$schlyearactive}_{$semesteractive}";

        $countstudsubfac = [];
        $underproglevsecname = [];
        
        $subload = SubjectOffered::join('coasv2_db_enrollment.studgrades', 'sub_offered.id', '=', 'coasv2_db_enrollment.studgrades.subjID')
                                ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                                ->join('scheduleclass', 'sub_offered.id', '=', 'scheduleclass.subject_id')
                                ->where('sub_offered.schlyear', 'LIKE', $schlyearactive)
                                ->where('sub_offered.semester', 'LIKE', $semesteractive)
                                ->where('scheduleclass.faculty_id', '=', $facultyId)
                                ->where('sub_offered.campus', '=', $campus)
                                ->select('sub_offered.subSec', 'subjects.sub_name', 'coasv2_db_enrollment.studgrades.subjID', DB::raw('COUNT(*) as count'))
                                ->groupBy('coasv2_db_enrollment.studgrades.subjID')
                                ->get();

        foreach ($subload as $program) {
            $underproglevsecname[] = $program->subSec .' - ' . $program->sub_name;
            $countstudsubfac[] = $program->count;
        }

        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        $enrolledStatus = EnrollmentMode::where('campus', $campus)->first();
        $faculevalStatus = QCEsetting::first();

        return view('control.facultyhome', compact('guard', 'schlyearactiveYear', 'schlyearactive', 'semesteractive', 'underproglevsecname', 'countstudsubfac', 'authfacdesig', 'enrolledStatus', 'faculevalStatus'));
    }

    public function dashcountstud()
    {
        $activeConfig = ConfigureCurrent::where('set_status', 2)->first();
        if (!$activeConfig) {
            return back()->with('error', 'No active school year found.');
        }
        $activeConfigId = $activeConfig->id;
        
        $previousConfig = ConfigureCurrent::where('id', '<', $activeConfigId)
            ->orderBy('id', 'desc')
            ->first();

        $schlyearactiveYear = $activeConfig->schlyear;
        $schlyearactive = $activeConfig->schlyear;
        $semesteractive = $activeConfig->semester;

        $facultyId = Auth::guard('faculty')->user()->id;
        $campus = Auth::guard('faculty')->user()->campus;

        $cacheKey1 = "faculty_home_subjectcount_{$facultyId}_{$campus}_{$schlyearactive}_{$semesteractive}";
        
        $data = Grade::leftJoin('coasv2_db_schedule.scheduleclass', 'studgrades.subjID', '=', 'coasv2_db_schedule.scheduleclass.subject_id')
                            ->leftJoin('coasv2_db_schedule.faculty', 'coasv2_db_schedule.scheduleclass.faculty_id', '=', 'coasv2_db_schedule.faculty.id')
                            ->join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                            ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                            ->select(
                                'studgrades.*',
                                'studgrades.id as stugdeID',
                                DB::raw('COUNT(DISTINCT studgrades.studID) as countsub'),
                                'coasv2_db_schedule.subjects.sub_name',
                                'coasv2_db_schedule.sub_offered.subSec',
                                'coasv2_db_schedule.sub_offered.schlyear',
                                'coasv2_db_schedule.sub_offered.semester',
                                'coasv2_db_schedule.sub_offered.campus',
                                'coasv2_db_schedule.scheduleclass.faculty_id',
                                'coasv2_db_schedule.scheduleclass.subject_id',
                                'coasv2_db_schedule.faculty.fname',
                                'coasv2_db_schedule.faculty.lname',
                            )
                    ->where('coasv2_db_schedule.sub_offered.semester', $semesteractive)
                    ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyearactive)
                    ->where('coasv2_db_schedule.sub_offered.campus', $campus)
                    ->where('coasv2_db_schedule.scheduleclass.faculty_id', $facultyId)
                    ->groupBy('studgrades.subjID')
                    ->get();     
        return response()->json(['data' => $data]);
    }
    
    public function logout()
    {
        // if (\Auth::guard('web')->check() || \Auth::guard('faculty')->check()) {
        //     auth()->guard('web')->logout();
        //     auth()->guard('faculty')->logout();
        //     return redirect()->route('login')->with('success', 'You have been Successfully Logged Out');
        // } else {
        //     return redirect()->route('home')->with('error', 'No authenticated user to log out');
        // }

        if (\Auth::guard('web')->check()) {
            auth()->guard('web')->logout();
            return redirect()->route('login')->with('success', 'You have been Successfully Logged Out');
        } elseif (\Auth::guard('faculty')->check()) {
            auth()->guard('faculty')->logout();
            return redirect()->route('loginfac')->with('success', 'You have been Successfully Logged Out');
        } elseif (\Auth::guard('kioskstudent')->check()) {
            auth()->guard('kioskstudent')->logout();
            return redirect()->route('loginstudonline')->with('success', 'You have been Successfully Logged Out');
            
            //$user = auth()->guard('kioskstudent')->user(); // Get the authenticated user
            
            // if ($user && $user->campus !== 'MC') { // Check if campus is NOT 'MC'
            //     auth()->guard('kioskstudent')->logout();
            //     return redirect()->route('loginextkioskstud')->with('success', 'You have been Successfully Logged Out');
            // } 
            // else {
            //     auth()->guard('kioskstudent')->logout();
            //     return redirect()->route('index.student')->with('success', 'You have been Successfully Logged Out');
            // }
        } else {
            // Check for kioskstudent guard before redirecting to home
            if (\Auth::guard('kioskstudent')->check()) {
                return redirect()->route('index.student')->with('error', 'No authenticated user to log out');
            }
            return redirect()->route('home')->with('error', 'No authenticated user to log out');
        }
    }
}
