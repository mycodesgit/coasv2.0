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

use App\Models\EvaluationDB\QCEratingscale;
use App\Models\EvaluationDB\QCEinstruction;
use App\Models\EvaluationDB\QCEcategory;
use App\Models\EvaluationDB\QCEquestion;
use App\Models\EvaluationDB\QCEsubquestion;
use App\Models\EvaluationDB\QCEsemester;

class StudentFacultyEvaluationController extends Controller
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

        $sy = ConfigureCurrent::where('set_status', 2)->first(['schlyear', 'semester']);

        $mysubj = Grade::join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                        ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                        ->leftJoin('coasv2_db_schedule.scheduleclass', 'studgrades.subjID', '=', 'coasv2_db_schedule.scheduleclass.subject_id')
                        ->leftJoin('coasv2_db_schedule.faculty', 'coasv2_db_schedule.scheduleclass.faculty_id', '=', 'coasv2_db_schedule.faculty.id')
                        ->select(
                            'studgrades.*',
                            'studgrades.id as stugdeID',
                            'coasv2_db_schedule.subjects.sub_name',
                            'coasv2_db_schedule.subjects.subjCollege',
                            'coasv2_db_schedule.sub_offered.subSec',
                            'coasv2_db_schedule.sub_offered.schlyear',
                            'coasv2_db_schedule.sub_offered.semester',
                            'coasv2_db_schedule.sub_offered.campus',
                            'coasv2_db_schedule.faculty.rank',
                            'coasv2_db_schedule.faculty.fname',
                            'coasv2_db_schedule.faculty.lname',
                            'coasv2_db_schedule.faculty.id',
                        )
                        ->where('coasv2_db_schedule.sub_offered.semester', $sy->semester)
                        ->where('coasv2_db_schedule.sub_offered.schlyear', $sy->schlyear)
                        ->where('studgrades.studID', '=', Auth::guard('kioskstudent')->user()->studid)
                        ->groupBy('studgrades.subjID')
                        ->get();

        return view('student.services.facultyevaluation.evalselectsubject', compact('guard', 'studentowner', 'studauth', 'mysubj'));
    }

    public function evalformStore(Request $request)
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $subjsIDselected = $request->query('id');
        $qcefacID = $request->query('qcefacID');

        $ratingscale = QCEratingscale::orderBy('inst_scale', 'DESC')->where('instratingscalestat', 1)->get();
        $inst = QCEinstruction::where('instructcat', 1)->get();
        $sy = ConfigureCurrent::where('set_status', 2)->first(['schlyear', 'semester']);
        $currsem = QCEsemester::where('qcesemstat', 2)
            ->first([
                'qceschlyear',
                'qcesemester',
                'qceratingfrom',
                'qceratingto'
            ]);

        $question = QCEquestion::join('qcecategory', 'qcequestion.catName_id', '=', 'qcecategory.id')
                ->select('qcecategory.catName', 'qcequestion.id', 'qcequestion.questiontext')
                ->where('qcecategory.catstatus', 2)
                ->orderBy('qcecategory.catName') 
                ->orderBy('qcequestion.id') 
                ->get()
                ->groupBy('catName');
        
        $facdetail = Faculty::where('id', $qcefacID)->get();

        $mysubjstarteval = Grade::join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                        ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                        ->leftJoin('coasv2_db_schedule.scheduleclass', 'studgrades.subjID', '=', 'coasv2_db_schedule.scheduleclass.subject_id')
                        ->select(
                            'studgrades.*',
                            'studgrades.id as stugdeID',
                            'coasv2_db_schedule.subjects.sub_name',
                            'coasv2_db_schedule.subjects.subjCollege',
                            'coasv2_db_schedule.subjects.sub_title',
                            'coasv2_db_schedule.sub_offered.subSec',
                            'coasv2_db_schedule.sub_offered.schlyear',
                            'coasv2_db_schedule.sub_offered.semester',
                            'coasv2_db_schedule.sub_offered.campus',
                        )
                        ->where('coasv2_db_schedule.sub_offered.semester', $currsem->qcesemester)
                        ->where('coasv2_db_schedule.sub_offered.schlyear', $currsem->qceschlyear)
                        ->where('studgrades.studID', '=', Auth::guard('kioskstudent')->user()->studid)
                        ->where('studgrades.subjID', $subjsIDselected)
                        ->groupBy('studgrades.subjID')
                        ->get();

        return view('student.services.facultyevaluation.evalselectsubjectrate', compact('studauth', 'inst', 'ratingscale',  'currsem', 'question', 'facdetail', 'mysubjstarteval'));
    }
}
