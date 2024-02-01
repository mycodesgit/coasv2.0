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
    //
    public function studInfo() {
        return view('enrollment.reports.studentinfo.studInfo');
    }

    public function studInfo_search(Request $request) {
        $data = Student::where('en_status', '=', 2)->get();
        if ($request->year){$data = $data->where('year',$request->year);}
        if ($request->campus){$data = $data->where('campus',$request->campus);}
        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);
        return view('enrollment.reports.studentinfo.studInfo_search',  ['data' => $data,'totalSearchResults' => $totalSearchResults]);
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
}
