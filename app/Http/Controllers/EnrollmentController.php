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
use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScholarshipDB\Scholar;

use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

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

        $mamisub = MajorMinor::all();

        $classEnrolls = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                ->join('coasv2_db_enrollment.yearlevel', function($join) {
                    $join->on(\DB::raw('SUBSTRING_INDEX(class_enroll.classSection, "-", 1)'), '=', 'coasv2_db_enrollment.yearlevel.yearsection');
                })
                ->select('class_enroll.*', 'programs.progAcronym', 'programs.progName', 'coasv2_db_enrollment.yearlevel.*')
                ->where('schlyear', '=', $schlyear)
                ->where('semester', '=', $semester)
                ->where('campus', '=', $campus)
                ->orderBy('programs.progAcronym', 'ASC')
                ->orderBy('class_enroll.classSection', 'ASC')
                ->get();

    
        return view('enrollment.studenroll.enrollStudent', compact('data', 'studlvl', 'studscholar', 'student', 'semester', 'schlyear', 'program', 'selectedProgram', 'classEnrolls', 'mamisub'));
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

    public function studrf_print()
    {
        // $applicant = Applicant::find($id); 
        // view()->share('applicant',$applicant);
        $pdf = PDF::loadView('enrollment.studenroll.pdfrf.studRF')->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
}
