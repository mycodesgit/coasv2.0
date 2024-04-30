<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\Strands;

class AdAcceptedController extends Controller
{
    public function applicant_accepted()
    {
        $strand = Strands::all();
        return view('admission.acceptedapp.index', compact('strand'));
    }

    public function srchacceptedList(Request $request)
    {
        $strand = Strands::all();
        return view('admission.acceptedapp.acceptedlist_search', compact('strand'));
    }

    public function getsrchacceptedListapp(Request $request)
    {   
        
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');

        $query = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_applicant_dept_rating.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->where('p_status', '=', 5);
        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
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
