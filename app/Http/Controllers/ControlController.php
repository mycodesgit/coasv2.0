<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        return view('control.home', compact('guard'));
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
        
        $previousConfig = ConfigureCurrent::where('id', '<', $activeConfigId) // Ensure it's before the current active one
            ->orderBy('id', 'desc') // Get the most recent one
            ->first();

        $schlyearactiveYear = $activeConfig->schlyear;
        $schlyearactive = $activeConfig->schlyear;
        $semesteractive = $activeConfig->semester;

        $countstudsubfac = [];
        $underproglevsecname = [];

        $subload = SubjectOffered::join('coasv2_db_enrollment.studgrades', 'sub_offered.id', '=', 'coasv2_db_enrollment.studgrades.subjID')
            ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->join('scheduleclass', 'sub_offered.id', '=', 'scheduleclass.subject_id')
            ->where('sub_offered.schlyear', 'LIKE', $schlyearactive)
            ->where('sub_offered.semester', 'LIKE', $semesteractive)
            ->where('scheduleclass.faculty_id', '=', Auth::guard('faculty')->user()->id)
            ->where('sub_offered.campus', '=', Auth::guard('faculty')->user()->campus)
            ->select('sub_offered.subSec', 'subjects.sub_name', 'coasv2_db_enrollment.studgrades.subjID', DB::raw('COUNT(*) as count'))
            ->groupBy('coasv2_db_enrollment.studgrades.subjID')
            ->get();

        // Populate the labels and data arrays
        foreach ($subload as $program) {
            $underproglevsecname[] = $program->subSec .' - ' . $program->sub_name;
            $countstudsubfac[] = $program->count;
        }

        return view('control.facultyhome', compact('guard', 'schlyearactiveYear', 'schlyearactive', 'semesteractive', 'underproglevsecname', 'countstudsubfac'));
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
            $user = auth()->guard('kioskstudent')->user(); // Get the authenticated user
            
            if ($user && $user->campus !== 'MC') { // Check if campus is NOT 'MC'
                auth()->guard('kioskstudent')->logout();
                return redirect()->route('loginextkioskstud')->with('success', 'You have been Successfully Logged Out');
            } else {
                auth()->guard('kioskstudent')->logout();
                return redirect()->route('loginkioskstud')->with('success', 'You have been Successfully Logged Out');
            }
        } else {
            // Check for kioskstudent guard before redirecting to home
            if (\Auth::guard('kioskstudent')->check()) {
                return redirect()->route('kioskhome')->with('error', 'No authenticated user to log out');
            }
            return redirect()->route('home')->with('error', 'No authenticated user to log out');
        }
    }
}
