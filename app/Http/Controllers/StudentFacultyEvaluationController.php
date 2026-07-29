<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helpers\EncryptionHelper;

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
use App\Models\EvaluationDB\QCEfevalrate;
use App\Models\EvaluationDB\QCEsetting;

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

    public function evalselect()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $setevalmode = QCEsetting::first();
        $sy = ConfigureCurrent::where('set_status', 2)->first(['schlyear', 'semester']);

        $enrollmentHistory = StudEnrolmentHistory::join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('program_en_history.studentID', $studauth->stud_id)
            ->where('program_en_history.campus', $studauth->campus)
            ->whereIn('program_en_history.schlyear', ['2025-2026'])
            ->whereIn('program_en_history.semester', [1,2])
            ->select('program_en_history.*', 'coasv2_db_schedule.programs.progAcronym')
            ->orderBy('schlyear', 'ASC')
            ->get();

        return view('student.services.facultyevaluation.evalselectschlyearsem', compact('guard', 'studentowner', 'studauth', 'setevalmode', 'enrollmentHistory'));
    }

    public function index()
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $setevalmode = QCEsetting::first();

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
                        ->where('coasv2_db_schedule.sub_offered.semester', 1)
                        ->where('coasv2_db_schedule.sub_offered.schlyear', '=', '2025-2026')
                        ->where('studgrades.studID', $studauth->stud_id)
                        ->groupBy('studgrades.subjID')
                        ->get();

        $disabledsubj = QCEfevalrate::where('qceformevalrate.studidno', $studauth->stud_id)
                        ->whereIn('qceformevalrate.statprint', [1,2])
                        ->get();

        return view('student.services.facultyevaluation.evalselectsubject', compact('guard', 'studentowner', 'studauth', 'mysubj', 'disabledsubj', 'setevalmode'));
    }

    public function show(Request $request)
    {
        $guard= $this->getGuard();
        $studentowner = Auth::guard($guard)->user()->studid;
        $studauth = Student::where('stud_id', '=', $studentowner)->first();

        $encryptedId = $request->query('id');
        $encryptedFacID = $request->query('qcefacID');

        // DECRYPT all encrypted values
        $subjsIDselected = EncryptionHelper::decryptUrl($encryptedId);
        $qcefacID = EncryptionHelper::decryptUrl($encryptedFacID);

        $ratingscale = QCEratingscale::orderBy('inst_scale', 'DESC')->where('instratingscalestat', 1)->get();
        $inst = QCEinstruction::where('instructcat', 1)->get();
        $sy = ConfigureCurrent::where('set_status', 2)->first(['schlyear', 'semester']);
        $currsem = QCEsemester::where('qcesemstat', 2)
            ->get([
                'qceschlyear',
                'qcesemester',
                'qceratingfrom',
                'qceratingto',
                'id'
            ]);

        $question = QCEquestion::join('qcecategory', 'qcequestion.catName_id', '=', 'qcecategory.id')
                ->select('qcecategory.catName', 'qcequestion.id', 'qcequestion.questiontext')
                ->where('qcecategory.catstatus', 2)
                ->where('qcequestion.questcat', 2)
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
                        ->where('coasv2_db_schedule.sub_offered.semester', $currsem->first()->qcesemester)
                        ->where('coasv2_db_schedule.sub_offered.schlyear', $currsem->first()->qceschlyear)
                        ->where('studgrades.studID', '=', Auth::guard('kioskstudent')->user()->studid)
                        ->where('studgrades.subjID', $subjsIDselected)
                        ->groupBy('studgrades.subjID')
                        ->get();

        return view('student.services.facultyevaluation.evalselectsubjectrate', compact('studauth', 'inst', 'ratingscale',  'currsem', 'question', 'facdetail', 'mysubjstarteval', 'subjsIDselected', 'qcefacID'));
    }

    public function create(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                // 'qceschlyearsemID' => 'required',
                // 'schlyear' => 'required',
                // 'semester' => 'required',
                // 'ratingfromto' => 'required',
                // 'question' => 'required|array',
                'question_rate' => 'required|array',
                // 'evaluatorname' => 'required',
                // 'evaluatorID' => 'required',
                // 'qcecomments' => [
                //     'required',
                //     function ($attribute, $value, $fail) {
                //         if (str_word_count($value) > 50) {
                //             $fail("The $attribute must not be more than 50 words.");
                //         }
                //     }
                // ],
            ]);
            
            try {
                $existingSurvey = QCEfevalrate::where('campus', $request->input('campus'))
                        ->where('semester', $request->input('semester'))
                        ->where('schlyear', $request->input('schlyear'))
                        ->where('qcefacname', $request->input('qcefacname'))
                        ->where('subjidrate', $request->input('subjidrate'))
                        ->where('evaluatorname', $request->input('evaluatorname'))
                        ->first();

                if ($existingSurvey) {
                    return redirect()->route('formRead')->with('error', 'You already submitted a survey for this subject and faculty');
                }

                $latestRateCount = QCEfevalrate::where('campus', $request->input('campus'))
                    ->where('semester', $request->input('semester'))
                    ->where('schlyear', $request->input('schlyear'))
                    ->where('qcefacname', $request->input('qcefacname'))
                    // ->where('subjidrate', $request->input('subjidrate'))
                    ->max('ratecount');

                // Increment the latest count or start from 1 if no previous record exists
                $newRateCount = $latestRateCount ? $latestRateCount + 1 : 1;

                QCEfevalrate::create([
                    'ratecount' => $newRateCount,
                    'campus' => $request->input('campus'),
                    'qceschlyearsemID' => $request->input('qceschlyearsemID'),
                    'schlyear' => $request->input('schlyear'),
                    'semester' => $request->input('semester'),
                    'ratingfromto' => $request->input('ratingfromto'),
                    'qcefacID' => $request->input('qcefacID'),
                    'qcefacname' => $request->input('qcefacname'),
                    'qceevaluator' => $request->input('qceevaluator'),
                    'question' => json_encode($request->input('question')),
                    'question_rate' => json_encode($request->input('question_rate')),
                    'qcecomments' => $request->input('qcecomments'),
                    'evaluatorname' => $request->input('evaluatorname'),
                    'evaluatorID' => $request->input('evaluatorID'),
                    'studidno' => $request->input('studidno'),
                    'prog' => $request->input('prog'),
                    'subjidrate' => $request->input('subjidrate'),
                ]);

                return redirect()->route('index.evaluation')->with('success', 'Survey Submitted Successfully');
            } catch (\Exception $e) {
                return back()->with('error', 'Failed to Submit Survey');
            }
        }
    }
}
