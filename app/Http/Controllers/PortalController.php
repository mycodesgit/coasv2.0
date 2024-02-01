<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;


class PortalController extends Controller
{
    public function index()
    {
        return view('portal.index');
    }

    public function admission_apply()
    {
        $admissionid = Applicant::orderBy('admission_id', 'desc')->first();
        $program = Programs::orderBy('id', 'asc')->get();
        $strand = Strands::orderBy('code', 'asc')->get();

        //$todayRegistrations = Applicant::whereDate('created_at', today())->count();

        return view('portal.apply')
        ->with('admissionid', $admissionid)
        ->with('program', $program)
        ->with('strand', $strand);
        //->with('todayRegistrations', $todayRegistrations);
    }

    public function post_admission_apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'admissionid' => 'required|unique:ad_applicant_admission,admission_id|numeric',
            'type' => 'required',
            'campus' => 'required',
            'lastname' => 'required|max:191',
            'firstname' => 'required|max:191',
            'email' => 'required|unique:ad_applicant_admission,email|max:191',
            'gender' => 'required',
            'age' => 'required',
            'contact' => 'required|numeric|min:11',
            'preference_1' => 'required',
            'preference_2' => 'required',
        ]);

        if($validator->fails()){
            return Redirect::route('admission-apply')->withErrors($validator)->withInput()->with('fail', 'Error in saving applicant data. Please check the inputs!');}
        else{

            $todayRegistrations = Applicant::whereDate('created_at', today())->count();

            if ($todayRegistrations >= 500) {
                return Redirect::route('admission-apply')->withErrors($validator)->withInput()->with('fail', 'Error: Daily registration limit reached!');
            }

            do {
                $currentYear = now()->year;
                $latestApplicant = Applicant::latest('created_at')->first();

                if (empty($latestApplicant) || date('Y', strtotime($latestApplicant->created_at)) < $currentYear) {
                    $latestId = 0; 
                } else {
                    $latestId = substr($latestApplicant->admission_id, -4);
                }

                $newId = $latestId + 1;
                $paddedValue = str_pad($newId, 4, '0', STR_PAD_LEFT);
                $admissionid = $currentYear.$paddedValue;

                $existingAdID = Applicant::where('admission_id', $admissionid)->first();
    
            } while ($existingAdID);

            $existingApplicant = Applicant::where('admission_id', $request->input('admissionid'))
                ->orWhere(function ($query) use ($request) {
                    $query->where('fname', $request->input('fname'))
                        ->where('mname', $request->input('mname'))
                        ->where('lname', $request->input('lname'));
                })
                ->first();

            if ($existingApplicant) {
                return Redirect::route('admission-apply')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('fail', 'Error: Name is already exists!');
            }


            $year = Carbon::now()->format('Y');
            $applicant = new Applicant;
            $applicant->year = $year;
            $applicant->campus = $request->input('campus');
            $applicant->admission_id = $admissionid;
            $applicant->type = $request->input('type');
            $applicant->lname = $request->input('lastname');
            $applicant->fname = $request->input('firstname');
            $applicant->mname = $request->input('mname');
            $applicant->ext = $request->input('ext');
            $applicant->gender = $request->input('gender');
            $applicant->address = $request->input('address');
            $applicant->bday = $request->input('bday');
            $applicant->age = $request->input('age');
            $applicant->contact = $request->input('contact');
            $applicant->email = $request->input('email');
            $applicant->civil_status = $request->input('civil_status');
            $applicant->religion = $request->input('religion'); 
            $applicant->monthly_income = $request->input('monthly_income'); 
            $applicant->strand = $request->input('strand');  
            $applicant->lstsch_attended = $request->input('lstsch_attended');
            $applicant->strand = $request->input('strand');
            $applicant->suc_lst_attended = $request->input('suc_lst_attended');
            $applicant->course = $request->input('course');
            $applicant->preference_1 = $request->input('preference_1');
            $applicant->preference_2 = $request->input('preference_2');
            $dt = Carbon::now();  
            $applicant->created_at = $dt;
            $applicant->save();

            if ($applicant->save())
            {
                $docs = new ApplicantDocs;
                $docs->admission_id = $admissionid;
                $docs->r_card = $request->input('r_card');
                $docs->g_moral = $request->input('g_moral');
                $docs->t_record = $request->input('t_record');
                $docs->b_cert = $request->input('b_cert');
                $docs->h_dismissal = $request->input('h_dismissal');
                $docs->m_cert = $request->input('m_cert');
                $docs->created_at = $dt;
                if ($request->hasFile('doc_image')) {
                    $file = $request->file('doc_image');
                    $filename = time() . '_' . $file->getClientOriginalName();
                    $path = $file->storeAs('doc_images', $filename, 'public');
                    $docs->doc_image = $path;
                }
                $docs->save();

                $examinee = new ExamineeResult;
                $examinee->admission_id =  $admissionid;
                $examinee->raw_score = $request->input('raw_score');
                $examinee->percentile = $request->input('percentile');
                $examinee->created_at = $dt;
                $examinee->save();

                $examinee = new DeptRating;
                $examinee->admission_id =  $admissionid;
                $examinee->created_at = $dt;
                $examinee->save();

                return Redirect::route('admission-apply')->withInput()->with('success', 'Application was successfully submitted. Check status in the (Track) Admission Page. Admission ID served as username!')->with('admission_id' ,$admissionid);
            }
            else{
                return Redirect::route('admission-apply')->withErrors($validator)->withInput();}
        }
    }

    public function admission_track()
    {
        return view('portal.track');
    }

    public function admission_data_current()
    {
        $applicant = Applicant::orderBy('admission_id', 'desc')->first();
        return view('portal.track')->with('applicant', $applicant);
    }

    public function admission_track_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'admission_id' => 'required|numeric|min:7',
        ]);

        if($validator->fails()){
            return Redirect::route('admission_track')->withErrors($validator)->withInput()->with('fail', 'Please check the inputs! (e.g. 202300001)');}
        else
        {

            $data = Applicant::get();
            if (Applicant::where('admission_id', '=', request('admission_id'))->exists())
            {
                $data = $data->where('admission_id', '=', $request->admission_id);
                $request->session()->put('recent_search', $data);
                return view('portal.track_status', ['data' => $data]);
                
            }
            else
            {
                return back()->withInput()->with('fail', 'Admission ID not found!');
            }
        
        }
    }
}
