<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;
use App\Models\AdmissionDB\AdmissionDate;
use App\Models\AdmissionDB\Time;
use App\Models\AdmissionDB\Venue;

class AdExamineeController extends Controller
{
    public function examinee_list()
    {
        $strand = Strands::all();
        return view('admission.examinee.list', compact('strand'));
    }

    public function srchexamineeList(Request $request)
    {   
        
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = Strands::all();

        return view('admission.examinee.list-search', compact('strand'));
    }

    public function getsrchexamineeList(Request $request)
    {   
        
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');

        $query = Applicant::join('ad_examinee_result', 'ad_applicant_admission.id', '=', 'ad_examinee_result.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_examinee_result.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->where('p_status', '=', 2);
        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }

    // public function srchexamineeList(Request $request)
    // {
    //     $data = Applicant::where('p_status', '=', 2)->get();
    //     if ($request->year){$data = $data->where('year',$request->year);}
    //     if ($request->campus){$data = $data->where('campus',$request->campus);}
    //     if ($request->admission_id){$data = $data->where('admission_id',$request->admission_id);}
    //     if ($request->lname){$data = $data->where('lname',$request->lname);}
    //     if ($request->strand){$data = $data->where('strand',$request->strand);}
    //     $request->session()->put('recent_search', $data);
    //     $totalSearchResults = count($data);
    //     return view('admission.examinee.list-search', ['data' => $data,'totalSearchResults' => $totalSearchResults] );
    // }

    public function examinee_edit_srch($id)
    {
        return redirect()->route('examinee_edit', [encrypt($id)]);
    }

    public function examinee_edit($id)
    {
        $appID = decrypt($id);
        $applicant = Applicant::find($appID);

        $selectedDate = $applicant->dateID;
        $selectedTime = $applicant->time;
        $selectedVenue = $applicant->venue;
        $selectedStrand = $applicant->strand;
        $selectedProgram = $applicant->course;
        $selectedPreference1 = $applicant->preference_1;
        $selectedPreference2 = $applicant->preference_2;

        $currentYear = now()->year;

        $year = Carbon::now()->format('Y');
        $docs = ApplicantDocs::where('app_id', '=', $appID)->get();
        $program = Programs::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $strand = Strands::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $date = AdmissionDate::select('date', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('date')->get();
        $time = Time::select('time', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('time')->get();
        $venue = Venue::select('venue', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('venue')->get();

        $time1 = Time::select('ad_time.*')
                ->where('campus', '=', Auth::user()->campus)
                ->whereYear('created_at', $currentYear)
                ->get();

        $venue1 = Venue::select('venue', DB::raw('count(*) as total'))
                ->where('campus', '=', Auth::user()->campus)
                ->groupBy('venue')
                ->whereYear('created_at', $currentYear)
                ->get();

        return view('admission.examinee.edit')
        ->with('applicant', $applicant)
        ->with('docs', $docs)
        ->with('program', $program)
        ->with('strand', $strand)
        ->with('date', $date)
        ->with('time', $time)
        ->with('venue', $venue)
        ->with('time1', $time1)
        ->with('venue1', $venue1)
        ->with('selectedDate', $selectedDate)
        ->with('time', $selectedTime)
        ->with('venue', $selectedVenue)
        ->with('selectedStrand', $selectedStrand)
        ->with('selectedProgram', $selectedProgram)
        ->with('selectedPreference1', $selectedPreference1)
        ->with('selectedPreference2', $selectedPreference2);
    }

    // public function examinee_delete($id)
    // {
    //     $appID = decrypt($id);
    //     $examinee = Applicant::findOrFail($appID);

    //     if ($examinee == null) {
    //         return redirect()->back()->with('fail', 'The Applicant does not exist.');
    //     }
    //     $examinee->update(['p_status' => 0]);

    //     return redirect()->back()->with('success', 'Applicant status Deleted Successfully.');
    // }

    public function examinee_delete($id){
        $examinee = Applicant::find($id);
        $examinee->fill(['p_status' => 7])->save();

        return response()->json([
            'status'=>200,
            'message'=>'Updated Successfully',
        ]);
    }



    public function assignresult($id)
    {
        $appID = decrypt($id);
        $assignresult = Applicant::findOrFail($appID);
        $assign = ExamineeResult::where('app_id', '=', $assignresult->id)->get();
        $per = ExamineeResult::where('app_id', '=', $assignresult->id)->get();
        return view('admission.examinee.result-assign')->with('assignresult',$assignresult )->with('assign',$assign)->with('per',$per);
    }

    public function examinee_result_save(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'raw_score' => 'required|numeric',
            'percentile' => 'required|numeric',
        ]);
        if($validator->fails()){
            return Redirect::route('assignresult', $id)->withErrors($validator)->withInput()->with('fail', 'Error in saving data. Please check the inputs!');}
        else{

        $examinee = Applicant::findOrFail($id);
        $assign = ExamineeResult::where('admission_id', $examinee->admission_id)
        ->update([
            'raw_score' => $request->input('raw_score'), 
            'percentile' => $request->input('percentile'),
        ]);
        return Redirect::route('assignresult', $id)->with('success','The examinee result has been saved');
        }
    }

    public function examinee_resultmod_save(Request $request) 
    {   
        $request->validate([
            'id' => 'required',
            'raw_score' => 'required|numeric',
            'percentile' => 'required',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->input('id'));
            $appresult = ExamineeResult::where('app_id', $decryptedId)->first();
            $appresult->update([
                'raw_score' => $request->input('raw_score'),
                'percentile' => $request->input('percentile'),
            ]);
            return response()->json(['success' => true, 'message' => 'Examinee Result has been saved'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to assign result!'], 404);
        }
    }

    public function examinee_result_save_nd(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'raw_score' => 'required|numeric',
            'percentile' => 'required',
        ]);
        
        if($validator->fails())
        {
            //return Redirect::back()->withErrors($validator)->withInput()->with('fail', 'Error in saving data. Please check the inputs!');
            //return response()->json(['error' => true, 'message' => 'Error in saving data. Please check the inputs'], 422);
        }
        else
        {
            $examinee = Applicant::findOrFail($id);
            $assign = ExamineeResult::where('app_id', $examinee->id)
            ->update([
                'raw_score' => $request->input('raw_score'), 
                'percentile' => $request->input('percentile'),
            ]);
            //return Redirect::back()->with('success','The examinee result has been updated');
            return response()->json(['success' => true, 'message' => 'The examinee saved Successfully'], 200);
            
        }
    }

    public function examinee_confirmajax(Request $request) 
    {
        $decryptedId = Crypt::decrypt($request->input('id'));
        
        $applicantsWithoutResult = Applicant::leftJoin('ad_examinee_result', 'ad_applicant_admission.id', '=', 'ad_examinee_result.app_id')
            ->where('ad_applicant_admission.p_status', 2)
            ->where('ad_applicant_admission.id', $decryptedId)
            ->where(function ($query) {
                $query->whereNull('ad_examinee_result.raw_score')
                    ->whereNull('ad_examinee_result.percentile');
            })
            ->exists();

        if ($applicantsWithoutResult) {
            return response()->json(['error' => true, 'message' => 'Please assign result before pushing to examination results list.'], 422);
        }
        
        $affectedRows = Applicant::where('p_status', 2)
            ->where('id', $decryptedId)
            ->update(['p_status' => 3]);

        if ($affectedRows > 0) {
            return response()->json(['success' => true, 'message' => 'Examinee has been pushed to Examination Results'], 200);
        } else {
            return response()->json(['error' => true, 'message' => 'No applicant found with the provided ID or the applicant already has a result assigned.'], 422);
        }
    }


    public function examinee_confirm($id)
    {
        $examinee = Applicant::findOrFail($id);
        if ($examinee->result->raw_score == null && $examinee->result->percentile == null)
        {
            return Redirect::route('examinee_edit', encrypt($id))->with('fail','Please assign result before pushing to results');
        }
        else
        {
            $examinee->p_status = 3;
            $dt = Carbon::now();  
            $examinee->updated_at = $dt;
            $examinee->update();
            return Redirect::back()->with('success','Examinee has been push to Examination Results'); 
        }
    }

    public function result_list()
    {
        $strand = Strands::all();
        return view('admission.examinee.result', compact('strand'));
    }

    public function srchexamineeResultList(Request $request)
    {
        $strand = Strands::all();
        return view('admission.examinee.result-search', compact('strand'));
    }

    public function getsrchexamineeResultList(Request $request)
    {   
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand =$request->query('strand');

        $query = Applicant::join('ad_examinee_result', 'ad_applicant_admission.id', '=', 'ad_examinee_result.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_examinee_result.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->where('p_status', '=', 3);
        
        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }

    public function confirmResult($id)
    {
        $appID = decrypt($id);
        $confirmresult = Applicant::findOrFail($appID);
        $assign = ExamineeResult::where('admission_id', '=', $confirmresult->admission_id)->get();
        $per = ExamineeResult::where('admission_id', '=', $confirmresult->admission_id)->get();
        return view('admission.examinee.result-confirm')->with('confirmresult',$confirmresult )->with('assign',$assign)->with('per',$per);
    }

    public function confirmPreEnrolment($id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->p_status = 4;
        $dt = Carbon::now();  
        $applicant->updated_at = $dt;
        $applicant->update();
        return back()->with('success', 'Applicant was officially confirmed for Pre-Enrolment');
    }

    public function examinee_confirmPreEnrolmentajax(Request $request) 
    {
        $decryptedId = Crypt::decrypt($request->input('id'));
        
        $applicantsWithoutResult = Applicant::leftJoin('ad_examinee_result', 'ad_applicant_admission.id', '=', 'ad_examinee_result.app_id')
            ->where('ad_applicant_admission.p_status', 2)
            ->where('ad_applicant_admission.id', $decryptedId)
            ->where(function ($query) {
                $query->whereNull('ad_examinee_result.raw_score')
                    ->whereNull('ad_examinee_result.percentile');
            })
            ->exists();

        if ($applicantsWithoutResult) {
            return response()->json(['error' => true, 'message' => 'Please assign result before pushing to Confirm Applicant list.'], 422);
        }
        
        $affectedRows = Applicant::where('p_status', 3)
            ->where('id', $decryptedId)
            ->update(['p_status' => 4]);

        if ($affectedRows > 0) {
            return response()->json(['success' => true, 'message' => 'Examinee has been pushed to Confirm Applicants List'], 200);
        } else {
            return response()->json(['error' => true, 'message' => 'No applicant found with the provided ID or the applicant already has a result assigned.'], 422);
        }
    }
}
