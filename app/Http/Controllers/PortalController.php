<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\AdReupload;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;
use App\Models\AdmissionDB\Time;
use App\Models\AdmissionDB\Year;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\Region;
use App\Models\SettingDB\Province;
use App\Models\SettingDB\City;
use App\Models\SettingDB\Barangay;
use App\Models\SettingDB\AdmissionMode;

class PortalController extends Controller
{
    public function index()
    {
        $admissionmode = AdmissionMode::first();

        return view('portal.index', compact('admissionmode'));
    }

    public function admission_apply()
    {
        $admissionid = Applicant::orderBy('admission_id', 'desc')->first();
        $program = Programs::orderBy('id', 'asc')->get();
        $strand = Strands::orderBy('code', 'asc')->get();
        $time = Time::whereYear('date', '2026')->get();

        //$todayRegistrations = Applicant::whereDate('created_at', today())->count();
        $regions = Region::all();
        $admissionmode = AdmissionMode::first();

        return view('portal.apply', compact('admissionid','program', 'strand', 'time', 'regions', 'admissionmode'));
        //->with('todayRegistrations', $todayRegistrations);
    }

    public function getProgramsByCampus(Request $request)
    {
        $selectedCampus = $request->input('campus');

        $programs = Programs::whereRaw("FIND_IN_SET('$selectedCampus', REPLACE(campus, ' ', '')) > 0")->get(['code', 'program']);

        return response()->json(['programs' => $programs]);
    }

    public function getExamSchedCampus(Request $request)
    {
        $selectedCampus = $request->input('campus');
        $curryear = Year::where('status', 'On')->value('adyear');

        $schedtest = Time::where('campus', $selectedCampus)
                    ->whereYear('date', $curryear)
                    ->get(['date', 'time', 'slots', 'id'])
                    ->map(function ($sched) {
                    // Count applicants with the same date and time
                    $applicantCount = Applicant::where('d_admission', $sched->date)
                        ->where('time', $sched->time)
                        ->where('p_status', '!=', 7) // Exclude cancelled applicants
                        ->count();

                    // Subtract from available slots
                    $sched->slots = max($sched->slots - $applicantCount, 0); // Prevent negative slots
                    return $sched;
                });

        return response()->json(['schedtest' => $schedtest]);
    }

    // public function checkEmail(Request $request)
    // {
    //     $email = $request->input('email');
    //     $apiKey = '34af5b04efbe14fc8999278e56521863b9909719'; // Replace with your actual Hunter.io API key

    //     // Hunter.io API URL
    //     $url = "https://api.hunter.io/v2/email-verifier?email={$email}&api_key={$apiKey}";

    //     // Making a GET request to Hunter.io
    //     $response = Http::get($url);

    //     if ($response->successful()) {
    //         $data = $response->json();

    //         // Check if the email is valid based on Hunter.io response
    //         if ($data['data']['result'] === 'deliverable') {
    //             return response()->json(['valid' => true]);
    //         } else {
    //             return response()->json(['valid' => false]);
    //         }
    //     } else {
    //         // Handle any error that occurs during API request
    //         return response()->json(['valid' => false], 500);
    //     }
    // }

    // public function checkEmail(Request $request)
    // {
    //     $email = $request->input('email');

    //     $apiKey = 'af0087ae-c398-4a9e-8c1d-b6f29ec2de8f';
    //     $url = 'https://api.mails.so/v1/validate?email=' . $email;

    //     $response = Http::withHeaders([
    //         'x-mails-api-key' => $apiKey,
    //     ])->get($url);

    //     if ($response->successful()) {
    //         $data = $response->json();

    //         if ($data['data']['result'] === 'deliverable') {
    //             return response()->json(['valid' => true]);
    //         } else {
    //             return response()->json(['valid' => false]);
    //         }
    //     } else {
    //         return response()->json(['valid' => false], 500);
    //     }
    // }

    // public function checkEmail(Request $request)
    // {
    //     $request->validate([
    //         'email' => [
    //             'required',
    //             'email',
    //             function ($attribute, $value, $fail) {
    //                 if (!str_ends_with(strtolower($value), '@gmail.com')) {
    //                     $fail('Only Gmail addresses are allowed.');
    //                 }
    //             }
    //         ],
    //     ]);

    //     $email = strtolower(trim($request->input('email')));
    //     $domain = substr(strrchr($email, "@"), 1);

    //     // Check MX (gmail.com must exist)
    //     if (!checkdnsrr($domain, "MX")) {
    //         return response()->json(['valid' => false, 'reason' => 'Invalid MX records']);
    //     }

    //     // If passes, we consider it valid
    //     return response()->json(['valid' => true]);
    // }

    public function post_admission_apply(Request $request)
    {
        $yearOn = Year::where('status', 'On')->value('adyear');
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'campus' => 'required',
            'lastname' => 'required',
            'firstname' => 'required',
            // 'email' => 'required|unique:ad_applicant_admission,email|max:191',
            'email' => [
                'required',
                'max:191',
                Rule::unique('ad_applicant_admission', 'email')
                    ->where(function ($query) use ($yearOn) {
                        return $query->where('year', $yearOn);
                    }),
            ],
            'gender' => 'required',
            'age' => 'required',
            'contact' => 'required|numeric|min:11',
            'preference_1' => 'required',
            'preference_2' => 'required',
            'proofdoc_image' => 'required',
            'studiddoc_image' => 'required',
        ]);

        $yearOn = Year::where('status', 'On')->value('adyear');

        $existingApplicantValidator = Validator::make([], []);

        $existingApplicant = Applicant::where('lname', $request->input('lastname'))
            ->where('fname', $request->input('firstname'))
            ->where('year', $yearOn)
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
                    ->with('fail', 'Error in saving applicant data. Your Email already exist. Please check the inputs!');
            }
        }

        $todayRegistrations = Applicant::whereDate('created_at', today())->count();

        if ($todayRegistrations >= 2000) {
            return Redirect::route('admission-apply')->withErrors($validator)->withInput()->with('fail', 'Error: Daily registration limit reached!');
        }

        // $campus = $request->input('campus');
        // $year = Year::where('status', 'On')->value('adyear');
        // $admissionid = '';

        // $latestApplicant = Applicant::where('campus', $campus)->latest('created_at')->first();

        // if (empty($latestApplicant) || date('Y', strtotime($latestApplicant->created_at)) < $year) {
        //     $latestId = 0;
        // } else {
        //     $latestId = (int)substr($latestApplicant->admission_id, -4);
        // }

        // $newId = $latestId + 1;
        // $paddedValue = str_pad($newId, 4, '0', STR_PAD_LEFT);
        // $admissionid = $year . $paddedValue;

        $campus = $request->input('campus');
        $year = Year::where('status', 'On')->value('adyear');

        // Find the latest applicant for the campus
        $latestApplicant = Applicant::where('campus', $campus)
            ->where('admission_id', 'like', $year . '%')
            ->orderByDesc('admission_id')
            ->first();

        if (!$latestApplicant) {
            // No previous admission ID for this campus and year, so start from 1
            $latestId = 1;
        } else {
            // Extract the numeric part of the last admission ID and increment it
            $latestId = (int)substr($latestApplicant->admission_id, -4) + 1;
        }

        // Pad the new ID to ensure it has 4 digits and concatenate with the year
        $paddedValue = str_pad($latestId, 4, '0', STR_PAD_LEFT);
        $admissionid = $year . $paddedValue;

        $existingAdID = Applicant::where('admission_id', $admissionid)->where('campus', $campus)->first();

        if ($existingAdID) {
            $admissionid = $existingAdID->admission_id + 1;
        }

        // $year = Carbon::now()->format('Y');
        //$year = '2025';
        $year = Year::where('status', 'On')->value('adyear');
        $applicant = new Applicant;
        $applicant->studagree = $request->input('studagree');
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
        $applicant->hnum = $request->input('hnum');
        $applicant->brgy = $request->input('brgy');
        $applicant->city = $request->input('city');
        $applicant->province = $request->input('province');
        $applicant->region = $request->input('region');
        $applicant->zcode = $request->input('zcode');
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
        $applicant->dateID = $request->input('dateID');
        $applicant->d_admission = $request->input('d_admission');
        $applicant->time = $request->input('time');
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
            $docs->qstion1 = $request->input('qstion1');
            $docs->qstion2 = $request->input('qstion2');
            $docs->typefileproofupload = $request->input('typefileproofupload');
            $docs->created_at = $dt;
            if ($request->hasFile('studiddoc_image')) {
                $file = $request->file('studiddoc_image');
                $filename = $request->input('lastname') . '_' . $request->input('firstname') . '_' . $admissionid;
                $extension = $file->getClientOriginalExtension();
                $filenameWithExtension = $filename . '.' . $extension;
                $folderPath = $year . '/studentIDfolder';
                $path = $file->storeAs($folderPath, $filenameWithExtension, 'public');
                $docs->studiddoc_image = $path;
            }
            if ($request->hasFile('proofdoc_image')) {
                $file = $request->file('proofdoc_image');
                $filename = $request->input('lastname') . '_' . $request->input('firstname') . '_' . $admissionid;
                $extension = $file->getClientOriginalExtension();
                $filenameWithExtension = $filename . '.' . $extension;
                $folderPath = $year . '/prooffolder';
                $path = $file->storeAs($folderPath, $filenameWithExtension, 'public');
                $docs->proofdoc_image = $path;
            }
            if ($request->hasFile('grade12File')) {
                $file = $request->file('grade12File');
                $filename = $request->input('lastname') . '_' . $request->input('firstname') . '_' . $admissionid;
                $extension = $file->getClientOriginalExtension();
                $filenameWithExtension = $filename . '.' . $extension;
                $folderPath = $year . '/applcntrequirement';
                $path = $file->storeAs($folderPath, $filenameWithExtension, 'public');
                $docs->grade12File = $path;
            }
            if ($request->hasFile('shsFile')) {
                $file = $request->file('shsFile');
                $filename = $request->input('lastname') . '_' . $request->input('firstname') . '_' . $admissionid;
                $extension = $file->getClientOriginalExtension();
                $filenameWithExtension = $filename . '.' . $extension;
                $folderPath = $year . '/applcntrequirement';
                $path = $file->storeAs($folderPath, $filenameWithExtension, 'public');
                $docs->shsFile = $path;
            }
            if ($request->hasFile('transfereeFile')) {
                $file = $request->file('transfereeFile');
                $filename = $request->input('lastname') . '_' . $request->input('firstname') . '_' . $admissionid;
                $extension = $file->getClientOriginalExtension();
                $filenameWithExtension = $filename . '.' . $extension;
                $folderPath = $year . '/applcntrequirement';
                $path = $file->storeAs($folderPath, $filenameWithExtension, 'public');
                $docs->transfereeFile = $path;
            }
            if ($request->hasFile('alsFile')) {
                $file = $request->file('alsFile');
                $filename = $request->input('lastname') . '_' . $request->input('firstname') . '_' . $admissionid;
                $extension = $file->getClientOriginalExtension();
                $filenameWithExtension = $filename . '.' . $extension;
                $folderPath = $year . '/applcntrequirement';
                $path = $file->storeAs($folderPath, $filenameWithExtension, 'public');
                $docs->alsFile = $path;
            }
            if ($request->hasFile('lifelongFile')) {
                $file = $request->file('lifelongFile');
                $filename = $request->input('lastname') . '_' . $request->input('firstname') . '_' . $admissionid;
                $extension = $file->getClientOriginalExtension();
                $filenameWithExtension = $filename . '.' . $extension;
                $folderPath = $year . '/applcntrequirement';
                $path = $file->storeAs($folderPath, $filenameWithExtension, 'public');
                $docs->lifelongFile = $path;
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

            $examinee = new AdReupload;
            $examinee->appid = $appid;
            $examinee->camp = $camp;
            $examinee->save();

            return Redirect::route('submitsucapply')->withInput()->with('success', 'Application was successfully submitted. Check status in the (Track) Admission Page.')->with('admission_id' ,$admissionid)->with('email', $applicant->email);
        }
        else{
            return Redirect::route('admission-apply')->withErrors($validator)->withInput();
        }
    }

    public function submitsucapply()
    {
        $year = Year::where('status', 'On')->value('adyear');
        $email = session('email');
        return view('portal.applysubmit', compact('email', 'year'));
    }

    public function sendThankYouEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $year = $request->input('year');
        $email = $request->input('email');


        Mail::raw("
            Dear Applicant,

            Congratulations! You have successfully registered for the {$year} Admission Test.

            Please note that your application is currently under review. You will receive a confirmation email once it has been approved.

            Thank you for your interest and patience.

            This is a system-generated message. Please do not reply.
            ", function ($message) use ($email) {
                $message->to($email)
                        ->subject('Admission Test Application - System Notification');
        });


        return response()->json(['message' => 'Email sent successfully']);
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
            return Redirect::route('admission_track')->withErrors($validator)->withInput()->with('error', 'Please check the inputs! Lastname and Firstname is required');}
        else
        {

            $data = Applicant::join('ad_examinee_result', 'ad_applicant_admission.id', '=', 'ad_examinee_result.app_id')
                ->join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                ->where('ad_applicant_admission.lname', $request->lname)
                ->where('ad_applicant_admission.fname', $request->fname)
                ->where('ad_applicant_admission.p_status', '!=', 7)
                ->where('ad_applicant_admission.year', '=', '2026')
                ->select(
                    'ad_applicant_admission.admission_id',
                    'ad_applicant_admission.p_status',
                    'ad_applicant_admission.campus',
                    'ad_applicant_admission.fname',
                    'ad_applicant_admission.mname',
                    'ad_applicant_admission.lname',
                    'ad_applicant_admission.campus',
                    'ad_applicant_admission.bday',
                    'ad_applicant_admission.email',
                    'ad_applicant_admission.contact',
                    'ad_applicant_admission.address',
                    'ad_applicant_admission.d_admission',
                    'ad_applicant_admission.time as adtime',
                    'ad_applicant_admission.venue',
                    'ad_applicant_admission.created_at',
                    'ad_applicant_admission.updated_at',
                    'ad_examinee_result.raw_score',
                    'ad_examinee_result.percentile',
                    'ad_examinee_result.rawscoredate',
                    'ad_examinee_result.updated_at as examresultdate',
                    'ad_applicant_dept_rating.rating',
                    'ad_applicant_dept_rating.remarks',
                    'ad_applicant_dept_rating.course',
                    'ad_applicant_dept_rating.deptcol',
                    'ad_applicant_dept_rating.updated_at as deptratingdate',
                )
                ->get();

            if ($data->isNotEmpty()) {
                $request->session()->put('recent_search', $data);
                return view('portal.track_status', ['data' => $data]);
            } else {
                return back()->withInput()->with('error', 'We couldn’t find any records matching your criteria.');
            }
        }
    }

    public function getPortalProvinces($region_id)
    {
        return response()->json(Province::where('region_id', $region_id)->get());
    }

    public function getPortalCities($province_id)
    {
        return response()->json(City::where('province_id', $province_id)->get());
    }

    public function getPortalBarangays($city_id)
    {
        return response()->json(Barangay::where('city_id', $city_id)->get());
    }
}
