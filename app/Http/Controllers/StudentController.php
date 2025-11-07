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

        $studfees = DB::table('coasv2_db_assessment.student_appraisal')
            ->leftJoin('coasv2_db_assessment.studpayment', function($join) {
                $join->on('coasv2_db_assessment.student_appraisal.studID', '=', 'coasv2_db_assessment.studpayment.studID')
                    ->on('coasv2_db_assessment.student_appraisal.account', '=', 'coasv2_db_assessment.studpayment.account');
            })
            ->where('coasv2_db_assessment.student_appraisal.studID', $studentowner)
            ->select(
                'coasv2_db_assessment.student_appraisal.schlyear',
                'coasv2_db_assessment.student_appraisal.semester',
                'coasv2_db_assessment.student_appraisal.fundID',
                'coasv2_db_assessment.student_appraisal.account',
                DB::raw('SUM(coasv2_db_assessment.student_appraisal.amount) as total_fee'),
                DB::raw('IFNULL(SUM(coasv2_db_assessment.studpayment.amountpaid), 0) as total_payment'),
                DB::raw('(SUM(coasv2_db_assessment.student_appraisal.amount) - IFNULL(SUM(coasv2_db_assessment.studpayment.amountpaid), 0)) as balance')
            )
            ->groupBy('coasv2_db_assessment.student_appraisal.schlyear', 'coasv2_db_assessment.student_appraisal.semester', 'coasv2_db_assessment.student_appraisal.fundID', 'coasv2_db_assessment.student_appraisal.account')
            ->orderBy('coasv2_db_assessment.student_appraisal.id', 'ASC')
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

    public function preenrolment_searchResult()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;

        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $sy = ConfigureCurrent::select('id', 'schlyear', 'semester')
                ->where('set_status', 3)
                ->orderBy('id', 'DESC')
                ->get()
                ->unique('schlyear');

        return view('student.preenrol.prelistview', compact('studauth', 'sy'));
    }
}
