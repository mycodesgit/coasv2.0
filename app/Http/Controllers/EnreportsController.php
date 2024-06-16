<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


use Storage;
use Carbon\Carbon;
use App\Models\EnrollmentDB\Student;

use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\ApplicantDocs;


class EnreportsController extends Controller
{
    public function studInfo() 
    {
        return view('enrollment.reports.studentinfo.studInfo');
    }

    public function studInfo_search(Request $request) 
    {
        $campus = Auth::guard('web')->user()->campus;

        $studlist = Student::where('campus', '=', $campus)->where('stud_id', 'NOT LIKE', '%-G%')->get();

        return view('enrollment.reports.studentinfo.studInfo_search', compact('studlist'));
    }

    public function getstudInfo_search(Request $request) 
    {
        $campus = Auth::guard('web')->user()->campus;

        $data = Student::where('campus', '=', $campus)
                        ->where('stud_id', 'NOT LIKE', '%-G%')
                        ->orderBy('lname', 'ASC')
                        ->get();
        
        return response()->json(['data' => $data]);
    }

    public function studInfo_view($id)
    {
        $student = Student::find($id);

        $selectedProgram = $student->course;

        $year = Carbon::now()->format('Y');
        $admissionid = Student::orderBy('admission_id', 'desc')->first();
        $program = Programs::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $docs = ApplicantDocs::where('admission_id', '=', $student->admission_id)->get();
        return view('enrollment.reports.studentinfo.studInfo_view')
        ->with('student', $student)
        ->with('program', $program)
        ->with('docs', $docs)
        ->with('selectedProgram', $selectedProgram);
    }

    public function studInfograduated() {
        return view('enrollment.reports.graduated.studinfograd');
    }

    public function studInfograduated_search(Request $request) 
    {
        $campus = Auth::guard('web')->user()->campus;

        $studlistgrad = Student::where('campus', '=', $campus)
                    ->where('en_status', '=', 3)
                    ->get();

        return view('enrollment.reports.graduated.studinfograd_listsearch', compact('studlistgrad'));
    }

    public function getstudInfograduated_search(Request $request) 
    {
        $campus = Auth::guard('web')->user()->campus;

        $data = Student::where('campus', '=', $campus)
                        ->where('en_status', '=', 3)
                        ->orderBy('lname', 'ASC')
                        ->get();
        
        return response()->json(['data' => $data]);
    }
}
