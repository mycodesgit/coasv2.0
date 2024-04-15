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

    public function getProgramsByCampus(Request $request)
    {
        $selectedCampus = $request->input('campus');

        $programs = Programs::whereRaw("FIND_IN_SET('$selectedCampus', REPLACE(campus, ' ', '')) > 0")->get(['code', 'program']);

        return response()->json(['programs' => $programs]);
    }

    public function post_admission_apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
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

        $existingApplicantValidator = Validator::make([], []);

        $existingApplicant = Applicant::where('lname', $request->input('lastname'))
            ->where('fname', $request->input('firstname'))
            ->whereYear('created_at', Carbon::now()->year)
            ->where(function ($query) {
                $query->where('p_status', '!=', 7)
                      ->orWhereNull('p_status');
            })
            ->first();

        if ($existingApplicant) {
            $existingApplicantValidator->errors()->add('existing_applicant', 'Error: You have already Registered this year.');
        }

        $validator->errors()->merge($existingApplicantValidator->errors());

        if ($validator->fails()) {
            if ($existingApplicantValidator->errors()->has('existing_applicant')) {
                return Redirect::route('admission-apply')
                    ->withErrors($existingApplicantValidator)
                    ->withInput()
                    ->with('fail', $existingApplicantValidator->errors()->first('existing_applicant'));
            } else {
                return Redirect::route('admission-apply')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('fail', 'Error in saving applicant data. Please check the inputs!');
            }
        }

        $todayRegistrations = Applicant::whereDate('created_at', today())->count();

        if ($todayRegistrations >= 500) {
            return Redirect::route('admission-apply')->withErrors($validator)->withInput()->with('fail', 'Error: Daily registration limit reached!');
        }
            
        $campus = $request->input('campus');
        $year = Carbon::now()->format('Y');
        $admissionid = '';

        $latestApplicant = Applicant::where('campus', $campus)->latest('created_at')->first();

        if (empty($latestApplicant) || date('Y', strtotime($latestApplicant->created_at)) < $year) {
            $latestId = 0;
        } else {
            $latestId = (int)substr($latestApplicant->admission_id, -4);
        }

        $newId = $latestId + 1;
        $paddedValue = str_pad($newId, 4, '0', STR_PAD_LEFT);
        $admissionid = $year . $paddedValue;

        $existingAdID = Applicant::where('admission_id', $admissionid)->where('campus', $campus)->first();

        if ($existingAdID) {
            $admissionid = $existingAdID->admission_id + 1;
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
            $appid = $applicant->id; 
            $camp = $applicant->campus; 

            $docs = new ApplicantDocs;
            $docs->app_id = $appid;
            $docs->camp = $camp;
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
                $filename = $request->input('lastname') . '_' . $request->input('firstname') . '_' . $admissionid;
                $extension = $file->getClientOriginalExtension();
                $filenameWithExtension = $filename . '.' . $extension;
                $path = $file->storeAs('doc_images', $filenameWithExtension, 'public');
                $docs->doc_image = $path;
            }
            $docs->save();

            $examinee = new ExamineeResult;
            $examinee->app_id = $appid;
            $examinee->camp = $camp;
            $examinee->admission_id =  $admissionid;
            $examinee->raw_score = $request->input('raw_score');
            $examinee->percentile = $request->input('percentile');
            $examinee->created_at = $dt;
            $examinee->save();

            $examinee = new DeptRating;
            $examinee->app_id = $appid;
            $examinee->camp = $camp;
            $examinee->admission_id =  $admissionid;
            $examinee->created_at = $dt;
            $examinee->save();

            return Redirect::route('admission-apply')->withInput()->with('success', 'Application was successfully submitted. Check status in the (Track) Admission Page.')->with('admission_id' ,$admissionid);
        }
        else{
            return Redirect::route('admission-apply')->withErrors($validator)->withInput();
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
            'lname' => 'required|string',
            'fname' => 'required|string',
        ]);

        if($validator->fails()){
            return Redirect::route('admission_track')->withErrors($validator)->withInput()->with('fail', 'Please check the inputs! Lastname and Firstname is required');}
        else
        {

            $data = Applicant::where('lname', $request->lname)
                ->where('fname', $request->fname)
                ->where('p_status', '!=', 7)
                ->get();

            if ($data->isNotEmpty()) {
                $request->session()->put('recent_search', $data);
                return view('portal.track_status', ['data' => $data]);
            } else {
                return back()->withInput()->with('fail', 'No matching records found!');
            }
        
        }
    }
}
