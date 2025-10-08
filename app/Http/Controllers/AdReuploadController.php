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
use App\Models\AdmissionDB\AdReupload;
use App\Models\AdmissionDB\Year;

class AdReuploadController extends Controller
{
    public function repup()
    {
        return view('portal.reuploadfile');
    }

    public function searchApplicant(Request $request)
    {
        $request->validate([
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'campus' => 'required|string',
        ]);

        $year = Year::where('status', 'On')->value('adyear');
        $applicant = Applicant::leftJoin('ad_reupload', 'ad_applicant_admission.id', '=', 'ad_reupload.appid')
            ->where('ad_applicant_admission.lname', $request->lastname)
            ->where('ad_applicant_admission.fname', $request->firstname)
            ->where('ad_applicant_admission.campus', $request->campus)
            ->where('ad_applicant_admission.year', $year)
            ->where('ad_reupload.status', 2)
            ->select('ad_applicant_admission.*', 'ad_reupload.reuploadallow')
            ->first();

        if ($applicant) {
            return response()->json([
                'success' => true,
                'applicant' => [
                    'admission_id' => $applicant->admission_id,
                    'lname' => $applicant->lname,
                    'fname' => $applicant->fname,
                    'primaryid' => $applicant->id,
                    'reuploadallow' => $applicant->reuploadallow, 
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
