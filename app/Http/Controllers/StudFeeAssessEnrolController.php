<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Rules\UniqueStudentID;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Traits\PendingAppraisalAssessmentCountTrait;

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
use App\Models\EnrollmentDB\StudHisLog;
use App\Models\EnrollmentDB\StudSubLog;

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
use App\Models\SettingDB\ButtonAccess;
use App\Models\SettingDB\QueueCounter;
use App\Models\SettingDB\QueueCustomer;
use App\Models\SettingDB\QueueMode;

class StudFeeAssessEnrolController extends Controller
{
    use PendingAppraisalAssessmentCountTrait;

    public function index()
    {
        $pendCount = $this->getPendingAllCount();

        $data = [
            'pendCount' => $pendCount, 
        ];

        if (request()->ajax()) {
            return response()->json([
                'pendCount' => $pendCount, 
            ]);
        }
        
        return view('assessment.enrlmentappcheck.checkassess', compact('data'));
    }
    
    public function show()
    {
        $campus = Auth::guard('web')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $sy = ConfigureCurrent::where('set_status', 3)
            ->first(['schlyear', 'semester']);

        $student = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->select(
                'students.fname', 
                'students.lname', 
                'students.mname', 
                'students.ext', 
                'program_en_history.*', 
                'program_en_history.created_at as created_ats', 
                'coasv2_db_schedule.programs.progAcronym'
            )
            ->where('program_en_history.schlyear', $sy->schlyear ?? '')
            ->where('program_en_history.semester', $sy->semester ?? '')
            //->whereRaw("SUBSTRING_INDEX(program_en_history.progCod, '-', 1) = ?", [$dept])
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('program_en_history.campus', 'LIKE', "$campus");
                }
            })
            ->where('program_en_history.status', 4)
            ->orderBy('program_en_history.created_at', 'asc')
            ->get();

        return response()->json(['data' => $student], 200);
    }

    public function store(Request $request)
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

        $preassessenrollreg  = StudEnrolmentHistory::where('studentID', '=', $stud_id)
                    ->where('schlyear', '=', $schlyear)
                    ->where('semester', '=', $semester)
                    ->where('status', 4)
                    ->first();

        $programEnHistory = StudEnrolmentHistory::leftJoin('coasv2_db_admission.users', 'program_en_history.postedBy', '=', 'coasv2_db_admission.users.id')
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

        // Start for studsublogtable
        $subjectsEnIDlog = StudSubLog::join('coasv2_db_schedule.sub_offered', 'studsublog.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studsublog.studID', '=', $programEnHistory->studentID)
                    ->pluck('coasv2_db_schedule.sub_offered.id');
        $subOfferedIdslog = implode(',', $subjectsEnIDlog->toArray());

        $studsubviewlog = StudSubLog::join('coasv2_db_schedule.sub_offered', 'studsublog.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studsublog.studID', '=', $programEnHistory->studentID)
                    ->pluck('studsublog.subjID');
        $studsubenrollIdslog = implode(',', $studsubviewlog->toArray());

        $studsubviewprimIDlog = StudSubLog::join('coasv2_db_schedule.sub_offered', 'studsublog.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', '=', $campus)
                    ->where('studsublog.studID', '=', $programEnHistory->studentID)
                    ->pluck('studsublog.id');
        $studsubenrollIdsprimIDlog = implode(',', $studsubviewprimIDlog->toArray());
        // End for studsublogtable

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
                    ->where('class_enroll.schlyear', '=', $schlyear)
                    ->where('class_enroll.semester', '=', $semester)
                    ->where('class_enroll.campus', '=', $campus)
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

        $pendCount = $this->getPendingAllCount();

        $data = [
            'pendCount' => $pendCount, 
        ];

        if (request()->ajax()) {
            return response()->json([
                'pendCount' => $pendCount, 
            ]);
        }
    
        return view('assessment.enrlmentappcheck.checkassess_search', compact('data', 'studlvl', 'studscholar', 'student', 'semester', 'schlyear', 'program', 'preassessenrollreg', 'classEnrolls', 'mamisub', 'subjOffer', 'subjectCount', 'studstat', 'studtype', 'shiftrans', 'selectedProgValue', 'selectedProgStudLevel', 'selectedStudSch', 'selectedStudMajor', 'selectedStudMinor', 'selectedStudStatus', 'selectedStudType', 'selectedStudTransferee', 'selectedStudFourPs', 'selectedpostedby', 'subjectsEn', 'subOfferedIds', 'studEditfees', 'programEnHistory', 'studsubenrollIds', 'studsubenrollIdsprimID' ,'studsubenrollIdsprimIDitfee', 'studsubenrollIdslog', 'studsubenrollIdsprimIDlog', 'subOfferedIdslog'));
    }

    public function update(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'id' => 'required',
                'schlyear' => 'required',
                'semester' => 'required',
                'campus' => 'required',
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

            $encode = str_replace('-', '', now()->format('Ymd')) .'-'. strtoupper(Str::random(4)) .'-'. str_replace('-', '', $request->input('studentID'));

            try {
                $enrolment = StudEnrolmentHistory::findOrFail($request->input('id'));

                $postedBy = $request->input('postedBy');

                $fndCodes = $request->input('fndCodes');
                $accntNames = $request->input('accntNames');
                $amntFees = $request->input('amntFees');

                $primIDs = $request->input('primIDs');
                
                if ($fndCodes && $accntNames && $amntFees) {
                    foreach ($fndCodes as $index => $fndCode) {
                        $account = $accntNames[$index];
                        $amount = $amntFees[$index];
                        $primID = $primIDs[$index] ?? null; // Handle cases where primID might not be set

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
                                'campus' => Auth::guard('web')->user()->campus,
                                'fundID' => $fndCode,
                                'account' => $account,
                                'dateAssess' => $request->input('postedDate'),
                                'amount' => $amount,
                                'postedBy' => $request->input('postedBy'),
                            ]);
                        }
                    }
                }


                return response()->json(['success' => true, 'message' => 'Student Appraisal Updated Successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Enroll Student'], 404);
            }
        }
    }

    public function pushtoregistrar(Request $request) 
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $enrolment = StudEnrolmentHistory::findOrFail($request->input('id'));
            $enrolment->update([
                'status' => 3,
            ]);

            return response()->json(['success' => true, 'message' => 'Student Appraisal Confirmed Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to change campus!'], 404);
        }
    }
}
