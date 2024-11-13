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
        // Validate inputs (ensure the files are being uploaded)
        $request->validate([
            'studiddoc_image' => 'nullable|mimes:jpeg,png,jpg,pdf|max:10240',
            'proofdoc_image' => 'nullable|mimes:jpeg,png,jpg,pdf|max:10240',
            'lname' => 'required',
            'fname' => 'required',
        ]);

        // Retrieve the application ID or admission ID
        $app_id = $request->input('app_id'); // or app_id if you're using it
        $admissionid = $request->input('admissionid');
        // Create or update ApplicantDoc model for the current applicant
        $docs = ApplicantDocs::firstOrNew(['app_id' => $app_id]);

        // Handle the student ID document upload
        if ($request->hasFile('studiddoc_image')) {
            $file = $request->file('studiddoc_image');
            $filename = $request->input('lname') . '_' . $request->input('fname') . '_' . $admissionid;
            $extension = $file->getClientOriginalExtension();
            $filenameWithExtension = $filename . '.' . $extension;
            $path = $file->storeAs('studentIDfolder', $filenameWithExtension, 'public');
            $docs->studiddoc_image = $path;
        }

        // Handle the proof document upload
        if ($request->hasFile('proofdoc_image')) {
            $file = $request->file('proofdoc_image');
            $filename = $request->input('lname') . '_' . $request->input('fname') . '_' . $admissionid;
            $extension = $file->getClientOriginalExtension();
            $filenameWithExtension = $filename . '.' . $extension;
            $path = $file->storeAs('prooffolder', $filenameWithExtension, 'public');
            $docs->proofdoc_image = $path;
        }

        // Save the document paths to the database
        $docs->save();

        // Return response
        return response()->json([
            'success' => true,
            'message' => 'Documents uploaded successfully.',
            'data' => $docs
        ]);
    }
}
