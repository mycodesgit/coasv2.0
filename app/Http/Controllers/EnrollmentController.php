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
use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScholarshipDB\Scholar;

use App\Models\AdmissionDB\Programs;

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
        $semester = $request->semester;
        $schlyear = $request->schlyear;
        $student = Student::find($id);
        
        $course = $student->course ?? null;

        $selectedProgram = $student->course;

        $program = Programs::where('code', $course)
            ->orWhere('code', $course)
            ->orderBy('id', 'asc')
            ->get();

        $classEnrolls = ClassEnroll::where('class', $selectedProgram)
        ->where('schlyear', $schlyear)
        ->where('semester', $semester)
        ->get();

        $yrSections = $classEnrolls->pluck('class_section')->unique();
    
        return view('enrollment.studenroll.enrollStudent', compact('data', 'studlvl', 'studscholar', 'student', 'semester', 'schlyear', 'program', 'selectedProgram', 'yrSections'));
    }

    public function studrf_print()
    {
        // $applicant = Applicant::find($id); 
        // view()->share('applicant',$applicant);
        $pdf = PDF::loadView('enrollment.studenroll.pdfrf.studRF')->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
}
