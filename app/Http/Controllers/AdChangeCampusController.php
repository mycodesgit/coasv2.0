<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;
use App\Models\AdmissionDB\AdmissionDate;
use App\Models\AdmissionDB\Time;
use App\Models\AdmissionDB\Venue;
use App\Models\AdmissionDB\Year;


class AdChangeCampusController extends Controller
{
    public function alllistappRead()
    {
        $strand = Strands::all();
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $currentYear = Year::where('status', 'On')->value('adyear');

        return view('admission.configure.changecmp', compact('strand', 'curryear'));
    }

    public function alllistappRead_search(Request $request)
    {
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');

        $strand = Strands::all();

        return view('admission.configure.changecamp', compact('strand'));
    }

    public function getalllistappRead_search(Request $request)
    {   
        
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');

        $query  = Applicant::join('ad_applicant_docs', 'ad_applicant_admission.id', '=', 'ad_applicant_docs.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_applicant_docs.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->whereIn('p_status', [3, 4]);

        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }

    public function alllistappUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'campus' => 'required',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->input('id'));

            $applicant = Applicant::findOrFail($decryptedId);
            $applicant->update(['campus' => $request->input('campus')]);

            ApplicantDocs::where('app_id', $decryptedId)->update(['camp' => $request->input('campus')]);

            ExamineeResult::where('app_id', $decryptedId)->update(['camp' => $request->input('campus')]);

            DeptRating::where('app_id', $decryptedId)->update(['camp' => $request->input('campus')]);

            return response()->json(['success' => true, 'message' => 'Applicant campus changed successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to change campus!'], 404);
        }
    }

}
