<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\UniqueStudentID;

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

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

use App\Models\ScholarshipDB\Scholar;

use App\Models\AssessmentDB\StudentFee;


class EnrollmentController extends Controller
{
    public function index()
    {   
        $grdCode = GradeCode::all();
        return view('enrollment.index', compact('grdCode'));
    }

    public function searchStud()
    {   
        return view('enrollment.studenroll.index');
    }

    public function searchStudEnroll(Request $request)
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

        $student = Student::where('stud_id', $stud_id)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }

        $enrollmentHistory = StudEnrolmentHistory::where('studentID', $stud_id)
            ->where('schlyear', $schlyear)
            ->where('semester', $semester)
            ->where('campus', $campus)
            ->first();

        if ($enrollmentHistory) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> is already enrolled in this semester.');
        }

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
                        ->select('subjects.*', 'sub_offered.*',)
                        ->where('schlyear', $schlyear)
                        ->where('semester', $semester)
                        ->where('campus', $campus)
                        ->orderBy('subjects.sub_name', 'ASC')
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

    public function fetchFeeSubjects(Request $request)
    {
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = $request->input('campus');
        $programCode = $request->input('programCode');
        $numericPart = $request->input('numericPart');

        $data = StudentFee::where('prog_Code', $programCode)
                    ->where('yrlevel', $numericPart)
                    ->where('schlyear', $schlyear)
                    ->where('semester', $semester)
                    ->where('campus', $campus)
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

            //$data = json_decode($request->getContent(), true);

            $studentID = $request->input('studentID');
            if (empty($studentID)) {
                return response()->json(['error' => true, 'message' => 'Student ID is required'], 400);
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
                    ]);
                }

                return response()->json(['success' => true, 'message' => 'Student Enrolled successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Enroll Student'], 404);
            }
        }
    }

    public function studrf_print()
    {
        // $applicant = Applicant::find($id); 
        // view()->share('applicant',$applicant);
        $pdf = PDF::loadView('enrollment.studenroll.pdfrf.studRF')->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
}
