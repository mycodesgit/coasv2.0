<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\DB;

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
        return view('admission.examinee.list');
    }

    public function srchexamineeList(Request $request)
    {
        $data = Applicant::where('p_status', '=', 2)->get();
        if ($request->year){$data = $data->where('year',$request->year);}
        if ($request->campus){$data = $data->where('campus',$request->campus);}
        if ($request->admission_id){$data = $data->where('admission_id',$request->admission_id);}
        if ($request->lname){$data = $data->where('lname',$request->lname);}
        if ($request->strand){$data = $data->where('strand',$request->strand);}
        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);
        return view('admission.examinee.list-search', ['data' => $data,'totalSearchResults' => $totalSearchResults] );
    }

    public function examinee_edit($id)
    {
        $appID = decrypt($id);
        $applicant = Applicant::find($appID);

        $selectedDate = $applicant->d_admission;
        $selectedTime = $applicant->time;
        $selectedVenue = $applicant->venue;
        $selectedStrand = $applicant->strand;
        $selectedProgram = $applicant->course;
        $selectedPreference1 = $applicant->preference_1;
        $selectedPreference2 = $applicant->preference_2;

        $year = Carbon::now()->format('Y');
        $docs = ApplicantDocs::where('app_id', '=', $appID)->get();
        $program = Programs::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $strand = Strands::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $date = AdmissionDate::select('date', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('date')->get();
        $time = Time::select('time', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('time')->get();
        $venue = Venue::select('venue', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('venue')->get();
        return view('admission.examinee.edit')
        ->with('applicant', $applicant)
        ->with('docs', $docs)
        ->with('program', $program)
        ->with('strand', $strand)
        ->with('date', $date)
        ->with('time', $time)
        ->with('venue', $venue)
        ->with('d_admission', $selectedDate)
        ->with('selectedTime', $selectedTime)
        ->with('selectedVenue', $selectedVenue)
        ->with('selectedStrand', $selectedStrand)
        ->with('selectedProgram', $selectedProgram)
        ->with('selectedPreference1', $selectedPreference1)
        ->with('selectedPreference2', $selectedPreference2);
    }

    public function examinee_delete($id)
    {
        $examinee = Applicant::findOrFail($id);
        if ($examinee == null){return redirect('admission/')->with('fail', 'The Applicant does not exist.');}
        if ($examinee->delete())
        {
            $docts = ApplicantDocs::where('admission_id','=', $examinee->admission_id)->delete();

            $docts = ExamineeResult::where('admission_id','=', $examinee->admission_id)->delete();

            return back()->with('success', 'The Applicant was successfully deleted.');}
            else{

            return back()->with('fail', 'An error was occured while deleting the data.');
        }
    }

    public function assignresult($id)
    {
        $appID = decrypt($id);
        $assignresult = Applicant::findOrFail($appID);
        $assign = ExamineeResult::where('admission_id', '=', $assignresult->admission_id)->get();
        $per = ExamineeResult::where('admission_id', '=', $assignresult->admission_id)->get();
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

    public function examinee_result_save_nd(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'raw_score' => 'required|numeric',
            'percentile' => 'required|numeric',
        ]);
        
        if($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput()->with('fail', 'Error in saving data. Please check the inputs!');
        }
        else
        {
            $examinee = Applicant::findOrFail($id);
            $assign = ExamineeResult::where('admission_id', $examinee->admission_id)
            ->update([
                'raw_score' => $request->input('raw_score'), 
                'percentile' => $request->input('percentile'),
            ]);
            return Redirect::back()->with('success','The examinee result has been updated');
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
            return Redirect::back()->with('success','Examinee result has been updated'); 
        }

    }

    public function result_list()
    {
        return view('admission.examinee.result');
    }

    public function srchexamineeResultList(Request $request)
    {
        $data = Applicant::where('p_status', '=', 3)->get();
        if ($request->year){$data = $data->where('year',$request->year);}
        if ($request->campus){$data = $data->where('campus',$request->campus);}
        if ($request->admission_id){$data = $data->where('admission_id',$request->admission_id);}
        if ($request->lname){$data = $data->where('lname',$request->lname);}
        if ($request->strand){$data = $data->where('strand',$request->strand);}
        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);
        return view('admission.examinee.result-search', ['data' => $data,'totalSearchResults' => $totalSearchResults] );
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
}
