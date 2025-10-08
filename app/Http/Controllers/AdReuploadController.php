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
use App\Models\AdmissionDB\Year;

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

        $year = Year::where('status', 'On')->value('adyear');
        // Search for the applicant based on the provided criteria
        $applicant = Applicant::where('lname', $request->lastname)
            ->where('fname', $request->firstname)
            ->where('campus', $request->campus)
            ->where('year', $year)
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
        
        $year = Year::where('status', 'On')->value('adyear');

        $app_id = $request->input('id');
        $admissionid = $request->input('admissionid');
        $lname = $request->input('lname');
        $fname = $request->input('fname');
        
        $applicant = ApplicantDocs::where('app_id', $app_id)->first();

        if (!$applicant) {
            return response()->json(['error' => 'Applicant not found'], 404);
        }

        $baseFilename = $lname . '_' . $fname . '_' . $admissionid;

        if ($request->hasFile('studiddoc_image')) {
            $extension = $request->file('studiddoc_image')->getClientOriginalExtension();
            $filename = $baseFilename . '_studid.' . $extension;
            $studIdPath = $request->file('studiddoc_image')->storeAs('studentIDfolder', $filename, 'public');
            $applicant->studiddoc_image = $studIdPath;
        }

        if ($request->hasFile('proofdoc_image')) {
            $extension = $request->file('proofdoc_image')->getClientOriginalExtension();
            $filename = $baseFilename . '_proof.' . $extension;
            $proofPath = $request->file('proofdoc_image')->storeAs('prooffolder', $filename, 'public');
            $applicant->proofdoc_image = $proofPath;
        }

        if ($request->hasFile('grade12File')) {
            $extension = $request->file('grade12File')->getClientOriginalExtension();
            $filename = $baseFilename . '.' . $extension;
            $folderPath = $year . '/applcntrequirement';
            $path = $request->file('grade12File')->storeAs($folderPath, $filename, 'public');
            $applicant->grade12File = $path;
        }

        if ($request->hasFile('shsFile')) {
            $extension = $request->file('shsFile')->getClientOriginalExtension();
            $filename = $baseFilename . '.' . $extension;
            $folderPath = $year . '/applcntrequirement';
            $path = $request->file('shsFile')->storeAs($folderPath, $filename, 'public');
            $applicant->shsFile = $path;
        }

        if ($request->hasFile('transfereeFile')) {
            $extension = $request->file('transfereeFile')->getClientOriginalExtension();
            $filename = $baseFilename . '.' . $extension;
            $folderPath = $year . '/applcntrequirement';
            $path = $request->file('transfereeFile')->storeAs($folderPath, $filename, 'public');
            $applicant->transfereeFile = $path;
        }

        if ($request->hasFile('alsFile')) {
            $extension = $request->file('alsFile')->getClientOriginalExtension();
            $filename = $baseFilename . '.' . $extension;
            $folderPath = $year . '/applcntrequirement';
            $path = $request->file('alsFile')->storeAs($folderPath, $filename, 'public');
            $applicant->alsFile = $path;
        }

        if ($request->hasFile('lifelongFile')) {
            $extension = $request->file('lifelongFile')->getClientOriginalExtension();
            $filename = $baseFilename . '.' . $extension;
            $folderPath = $year . '/applcntrequirement';
            $path = $request->file('lifelongFile')->storeAs($folderPath, $filename, 'public');
            $applicant->lifelongFile = $path;
        }

        $applicant->save();

        return response()->json([
            'success' => true,
            'message' => 'Files updated successfully',
            // 'data' => [
            //     'studiddoc_image' => $applicant->studiddoc_image,
            //     'proofdoc_image' => $applicant->proofdoc_image,
            // ]
        ]);
    }

    public function repupredirectexpire()
    {
        return view('portal.uploadsuccesspage');
    }
}
