<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;

class AdReuploadController extends Controller
{
    public function repup()
    {
        return view('portal.reuploadfile');
    }

    public function searchApplicant(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'campus' => 'required|string',
        ]);

        // Search for the applicant based on the provided criteria
        $applicant = Applicant::where('lname', $request->lastname)
            ->where('fname', $request->firstname)
            ->where('campus', $request->campus)
            ->first();

        // Check if an applicant was found
        if ($applicant) {
            return response()->json([
                'success' => true,
                'applicant' => [
                    'admission_id' => $applicant->admission_id,
                    'primaryid' => $applicant->id // Assuming 'id' is the primary key
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No applicant found.'
            ]);
        }
    }
}
