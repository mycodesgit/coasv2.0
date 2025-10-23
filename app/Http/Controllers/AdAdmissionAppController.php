<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;
use App\Mail\ApplicantConfirmationMail;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\AdReupload;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;
use App\Models\AdmissionDB\AdmissionDate;
use App\Models\AdmissionDB\Time;
use App\Models\AdmissionDB\Venue;
use App\Models\AdmissionDB\Year;

use App\Models\ScheduleDB\Faculty;

class AdAdmissionAppController extends Controller
{
    public function applicant_list()
    {
        $strand = Strands::all();
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        return view('admission.applicant.list', compact('strand', 'curryear'));
    }

    public function srchappList(Request $request)
    {   
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');
        
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $currentYear = Year::where('status', 'On')->value('adyear');

        $time1 = Time::select('ad_time.*')
                ->where('campus', '=', Auth::user()->campus)
                ->whereYear('date', $currentYear)
                ->get()
                ->map(function ($sched) {
                    // Count applicants with the same date and time
                    $applicantCount = Applicant::where('d_admission', $sched->date)
                        ->where('time', $sched->time)
                        ->count();

                    // Subtract from available slots
                    $sched->slots = max($sched->slots - $applicantCount, 0);
                    return $sched;
                });

        $venue1 = Venue::where('campus', '=', Auth::guard('web')->user()->campus)
                ->where('adyear', $currentYear)
                ->get();

        $strand = Strands::all();
        return view('admission.applicant.list-search', compact('curryear', 'time1', 'venue1', 'strand'));
    }

    public function getsrchappList(Request $request)
    {   
        
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');

        $query  = Applicant::join('ad_applicant_docs', 'ad_applicant_admission.id', '=', 'ad_applicant_docs.app_id')
                        ->leftJoin('ad_reupload', 'ad_applicant_admission.id', '=', 'ad_reupload.appid')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_applicant_docs.*', 'ad_reupload.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->where('p_status', '=', 1);

        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        $data->transform(function ($item) {
            $item->adid = Crypt::encryptString($item->adid);
            return $item;
        });

        return response()->json(['data' => $data]);
    }

    public function getReuploadAccess($id)
    {
        $decryptedId = Crypt::decryptString($id);
        $access = AdReupload::where('appid', $decryptedId)->first();
        
        if ($access) {
            return response()->json([
                'reuploadallow' => $access->reuploadallow
            ]);
        }
        
        return response()->json([
            'reuploadallow' => [] 
        ]);
    }

    public function saveAppUploadAccess(Request $request, $id)
    {
        $decryptedId = Crypt::decryptString($id);
        $reuploadallow = $request->input('reuploadallow', []); 
        
        AdReupload::updateOrCreate(
            ['appid' => $decryptedId],
            [
                'reuploadallow' => $reuploadallow,
                'status' => '2'
            ],
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Applicant re-upload access updated successfully.'
        ]);
    }

    public function applicantUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'lname' => 'required',
            'fname' => 'required',
        ]);

        try {
            $decryptedId = Crypt::decryptString($request->input('id'));
            $applicant = Applicant::find($decryptedId);
            $applicant->update([
                'lname' => $request->input('lname'),
                'fname' => $request->input('fname'),
                'mname' => $request->input('mname'),
                'ext' => $request->input('ext'),
                'gender' => $request->input('gender'),
                'civil_status' => $request->input('civil_status'),
        ]);
            return response()->json(['success' => true, 'message' => 'Applicant Personal Information update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Applicant Personal Information'], 404);
        }
    }

    public function applicant_delete($id) {
        try {
            $decryptedId = Crypt::decryptString($id);
            $applicant = Applicant::find($decryptedId);
            
            if ($applicant) {
                $applicant->update(['p_status' => 7]);

                return response()->json([
                    'success' => true, 
                    'message' => 'Status updated to 7 successfully',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Applicant not found',
                ], 404);
            }
        } catch (\Exception $e) {
           // \Log::error('Error updating applicant status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error. Please contact support.',
            ], 500);
        }
    }

    public function applicant_schedulemod_save(Request $request) 
    {   
        $request->validate([
            'id' => 'required',
            'dateID' => 'required',
            'd_admission' => 'required',
            'time' => 'required',
            'venue' => 'required',
        ]);

        try {
            $decryptedId = Crypt::decryptString($request->input('id'));
            $appsched = Applicant::findOrFail($decryptedId);
            $appsched->update([
                'dateID' => $request->input('dateID'),
                'd_admission' => $request->input('d_admission'),
                'time' => $request->input('time'),
                'venue' => $request->input('venue'),
            ]);
            return response()->json(['success' => true, 'message' => 'Applicant schedule has been saved'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to set Schedule!'], 404);
        }
    }

    public function applicant_confirmajax(Request $request) 
    {
        $decryptedId = Crypt::decryptString($request->input('id'));
        $applicantsWithoutSchedule = Applicant::where('p_status', 1)
            ->where('id', $decryptedId)
            ->where(function ($query) {
                $query->whereNull('d_admission')
                    ->where(function ($query) {
                        $query->whereNull('time')
                            ->orWhere('time', '00:00:00');
                    });
            })->exists();

        $venueEmpty = Applicant::where('id', $decryptedId)
            ->whereNull('venue')
            ->orWhere('venue', '')
            ->exists();

        if ($applicantsWithoutSchedule) {
            return response()->json(['error' => true, 'message' => 'Please assign schedule and time for examination before pushing to examination list.'], 422);
        }

        if ($venueEmpty) {
            return response()->json(['error' => true, 'message' => 'Please assign a venue before pushing to examination list.'], 422);
        }
        $affectedRows = Applicant::where('p_status', 1)
            ->where('id', $decryptedId)
            ->update(['p_status' => 2]);

        if ($affectedRows > 0) {
            $applicant = Applicant::find($decryptedId);
            $formattedDate = \Carbon\Carbon::parse($applicant->d_admission)->format('F d, Y');
            $formattedTime = \Carbon\Carbon::parse($applicant->time)->format('h:i A');         

            // Prepare email data
            $emailData = [
                'date' => $formattedDate,
                'time' => $formattedTime,
                'venue' => $applicant->venue, 
                'applicant_name' => $applicant->fname . ' ' . $applicant->lname,
            ];

            // Send the email
            Mail::to($applicant->email)->send(new \App\Mail\ApplicantConfirmationMail($emailData));
            return response()->json(['success' => true, 'message' => 'Applicant schedule has been pushed to Examinee List'], 200);
        } else {
            return response()->json(['error' => true, 'message' => 'No applicant found with the provided ID or the applicant already has a schedule and time assigned.'], 422);
        }
    }
}
