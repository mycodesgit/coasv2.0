<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Rules\UniqueStudentID;


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
        $data = Student::where('status', '=', 1)->get();
        return view('enrollment.studenroll.index', compact('data'));
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
}
