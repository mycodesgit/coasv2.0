<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;
use App\Models\AdmissionDB\AdmissionDate;

use App\Models\SettingDB\ConfigureCurrent;

class AdBillingController extends Controller
{
    public function adbillingRead()
    {

        return view('admission.reports.billing');
    }

    public function adbillingRead_search(Request $request)
    {

        $year = $request->query('year');

        $admsnstud = Applicant::where('year', $year)
                      ->whereIn('p_status', ['3', '4', '5', '6'])
                      ->get();


        return view('admission.reports.billing_search', compact('admsnstud'));
    }
}
