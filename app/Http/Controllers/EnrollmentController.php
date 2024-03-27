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
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

use App\Models\ScholarshipDB\Scholar;


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

    public function liveSearchStudent(Request $request)
    {
        $search = $request->input('search');
        $enStatus = $request->input('en_status');

        $students = Student::where('status', 1)
            ->where('campus', '=', Auth::user()->campus)
            ->where('en_status', $enStatus) // Filter by en_status
            ->where(function ($query) use ($search) {
                $query->where('stud_id', 'LIKE', '%' . $search . '%')
                    ->orWhere('lname', 'LIKE', '%' . $search . '%')
                    ->orWhere('mname', 'LIKE', '%' . $search . '%')
                    ->orWhere('fname', 'LIKE', '%' . $search . '%');
            })
            ->get();
        return response()->json($students);
    }


    public function searchStudEnroll(Request $request)
    {
        $studlvl = StudentLevel::all();
        $studscholar = Scholar::all();
        $mamisub = MajorMinor::all();
        $studstat = StudentStatus::all();
        $studtype = StudentType::all();
        $shiftrans = StudentShifTrans::all();

        $data = Student::where('en_status', '=', 1)->get();
        $id = $request->stud_id;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
        $student = Student::find($id);
        
        $course = $student->course ?? null;

        $selectedProgram = $student->course;

        $program = EnPrograms::where('progCod', $course)
            ->orWhere('progCod', $course)
            ->orderBy('id', 'asc')
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
        
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
        $subjOffer = SubjectOffered::join('subjects', 'sub_offered.subCode', 'subjects.sub_code')
                        ->select('subjects.*', 'sub_offered.*',)
                        ->where('schlyear', $schlyear)
                        ->where('semester', $semester)
                        ->where('campus', $campus)
                        ->orderBy('subjects.sub_name', 'ASC')
                        ->get();
                        
        $subjectCount = $subjOffer->count();
    
        return view('enrollment.studenroll.enrollStudent', compact('data', 'studlvl', 'studscholar', 'student', 'semester', 'schlyear', 'program', 'selectedProgram', 'classEnrolls', 'mamisub', 'subjOffer', 'subjectCount', 'studstat', 'studtype', 'shiftrans'));
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
                        ->select('subjects.*', 'sub_offered.*')
                        ->where('subSec', $course)
                        ->where('isTemp', 'Yes')
                        ->where('schlyear', $schlyear)
                        ->where('semester', $semester)
                        ->where('campus', $campus)
                        ->orderBy('sub_offered.subCode', 'ASC')
                        ->get();

        return response()->json($subjects);
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
                'studStatus' => 'required',
                'studSch' => 'required',
                'studClassID' => 'required',
                'studType' => 'required',
                'transferee' => 'required',
                'fourPs' => 'required',
            ]);

            // $campus = $request->input('campus');
            // $schlyear = $request->input('schlyear');
            // $semester = $request->input('semester');
            // $progCode = $request->input('prog_Code');
            // $studentID = $request->input('studentID');
            // $yrlevel = $request->input('yrlevel');

            // $studentID = $request->input('studentID'); 
            // $existingStudFee = StudEnrolmentHistory::where('accountName', $studentID)
            //                 ->where('campus', $campus)
            //                 ->where('schlyear', $schlyear)
            //                 ->where('semester', $semester)
            //                 ->where('prog_Code', $progCode)
            //                 ->where('yrlevel', $yrlevel)
            //                 ->first();

            // if ($existingStudFee) {
            //     return response()->json(['error' => true, 'message' => 'Account Name in Student Fee already exists'], 404);
            // }

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
