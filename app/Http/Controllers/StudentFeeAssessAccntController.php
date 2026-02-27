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

class StudentFeeAssessAccntController extends Controller
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

        $studfees = StudentAppraisal::select('student_appraisal.*')
                    ->where('student_appraisal.studID', $studentowner)
                    ->orderBy('student_appraisal.id', 'ASC')
                    ->get();

        return view('student.services.studntassessfees.listassess', compact('studauth', 'studfees'));
    }
}
