<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\ExamineeResult;

class AdAcceptedController extends Controller
{
    public function applicant_accepted()
    {
        $confirm = Applicant::orderBy('admission_id', 'desc')->where('p_status', '=', 5)->get();
        return view('admission.acceptedapp.index')
        ->with('confirm', $confirm);
    }

    public function srchacceptedList(Request $request)
    {
        $data = Applicant::where('p_status', '=', 5)->get();
        if ($request->year){$data = $data->where('year',$request->year);}
        if ($request->campus){$data = $data->where('campus',$request->campus);}
        if ($request->admission_id){$data = $data->where('admission_id',$request->admission_id);}
        if ($request->lname){$data = $data->where('lname',$request->lname);}
        if ($request->strand){$data = $data->where('strand',$request->strand);}
        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);
        return view('admission.acceptedapp.acceptedlist_search', ['data' => $data,'totalSearchResults' => $totalSearchResults] );
    }

    // public function applicant_enrolled()
    // {
    //     $confirm = Applicant::orderBy('admission_id', 'desc')->where('p_status', '=', 6)->get();
    //     return view('admission.enrolled.list_enrolled')
    //     ->with('confirm', $confirm);
    // }

    // public function srchacceptedEnrolledList(Request $request)
    // {
    //     $data = Applicant::where('p_status', '=', 6)->get();
    //     if ($request->year){$data = $data->where('year',$request->year);}
    //     if ($request->campus){$data = $data->where('campus',$request->campus);}
    //     if ($request->admission_id){$data = $data->where('admission_id',$request->admission_id);}
    //     if ($request->lname){$data = $data->where('lname',$request->lname);}
    //     if ($request->strand){$data = $data->where('strand',$request->strand);}
    //     $request->session()->put('recent_search', $data);
    //     $totalSearchResults = count($data);
    //     return view('admission.enrolled.list_enrolled_search', ['data' => $data,'totalSearchResults' => $totalSearchResults] );
    // }
}
