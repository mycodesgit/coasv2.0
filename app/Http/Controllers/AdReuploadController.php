<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
                    'lname' => $applicant->lname,
                    'fname' => $applicant->fname,
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

    public function uploadDocuments(Request $request)
    {
        $request->validate([
            'studiddoc_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'proofdoc_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $app_id = $request->input('id');
        $admissionid = $request->input('admissionid');
        $lname = $request->input('lname');
        $fname = $request->input('fname');

        // Fetch the applicant record by `app_id`
        $applicant = ApplicantDocs::where('app_id', $app_id)->first();

        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        // Generate a base filename using lname, fname, and admission ID
        $baseFilename = $lname . '_' . $fname . '_' . $admissionid;

        // Check if the `studiddoc_image` is already uploaded (not null, empty, or 0)
        if ($applicant->studiddoc_image && $applicant->studiddoc_image != '0' && !empty($applicant->studiddoc_image)) {
            return response()->json(['error' => 'You have already uploaded the student ID document'], 400);
        }

        // Check if the `proofdoc_image` is already uploaded (not null, empty, or 0)
        if ($applicant->proofdoc_image && $applicant->proofdoc_image != '0' && !empty($applicant->proofdoc_image)) {
            return response()->json(['error' => 'You have already uploaded the proof document'], 400);
        }

        // Update the `studiddoc_image` if a new file is uploaded
        if ($request->hasFile('studiddoc_image')) {
            $extension = $request->file('studiddoc_image')->getClientOriginalExtension();
            $filename = $baseFilename . '_studid.' . $extension;
            $studIdPath = $request->file('studiddoc_image')->storeAs('studentIDfolder', $filename, 'public');
            $applicant->studiddoc_image = $studIdPath;
        }

        // Update the `proofdoc_image` if a new file is uploaded
        if ($request->hasFile('proofdoc_image')) {
            $extension = $request->file('proofdoc_image')->getClientOriginalExtension();
            $filename = $baseFilename . '_proof.' . $extension;
            $proofPath = $request->file('proofdoc_image')->storeAs('prooffolder', $filename, 'public');
            $applicant->proofdoc_image = $proofPath;
        }

        // Save changes to the database
        $applicant->save();

        return response()->json([
            'success' => true,
            'message' => 'Files updated successfully',
            'data' => [
                'studiddoc_image' => $applicant->studiddoc_image,
                'proofdoc_image' => $applicant->proofdoc_image,
            ]
        ]);
    }

    public function repupredirectexpire()
    {
        return view('portal.uploadsuccesspage');
    }
}
