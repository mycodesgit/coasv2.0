<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Crypt;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;
use App\Models\AdmissionDB\AdmissionDate;
use App\Models\AdmissionDB\Time;
use App\Models\AdmissionDB\Venue;
use App\Models\ScheduleDB\Faculty;

class AdAdmissionController extends Controller
{
    public function countApplicantsByCampus()
    {
        $campuses = ['MC', 'VC', 'SCC', 'MP', 'HC', 'IC', 'CA', 'CC', 'SC', 'HinC'];

        $counts = [];
        $totalCount = 0;

        foreach ($campuses as $campus) {
            $count = Applicant::where('campus', $campus)->count();
            $counts[$campus] = $count;
            $totalCount += $count;
        }

        $counts['total'] = $totalCount;
        return $counts;
    }

    private function shareCounts()
    {
        $MainRegCount = Applicant::where('campus', 'MC')->where('p_status', 1)->count();
        $MainSchedCount = Applicant::where('campus', 'MC')->where('p_status', 2)->count();


        $IlogRegCount = Applicant::where('campus', 'IC')->where('p_status', '1')->count();
        $IlogSchedCount = Applicant::where('campus', 'IC')->where('p_status', '2')->count();

        $CauayanRegCount = Applicant::where('campus', 'CC')->where('p_status', '1')->count();
        $CauayanSchedCount = Applicant::where('campus', 'CC')->where('p_status', '2')->count();

        $SipalayRegCount = Applicant::where('campus', 'SC')->where('p_status', '1')->count();
        $SipalaySchedCount = Applicant::where('campus', 'SC')->where('p_status', '2')->count();

        $HinobaanRegCount = Applicant::where('campus', 'HinC')->where('p_status', '1')->count();
        $HinobaanSchedCount = Applicant::where('campus', 'HinC')->where('p_status', '2')->count();

        $HinigaranRegCount = Applicant::where('campus', 'HC')->where('p_status', '1')->count();
        $HinigaranSchedCount = Applicant::where('campus', 'HC')->where('p_status', '2')->count();

        $MoisesRegCount = Applicant::where('campus', 'MP')->where('p_status', '1')->count();
        $MoisesSchedCount = Applicant::where('campus', 'MP')->where('p_status', '2')->count();

        $SancarlosRegCount = Applicant::where('campus', 'SCC')->where('p_status', '1')->count();
        $SancarlosSchedCount = Applicant::where('campus', 'SCC')->where('p_status', '2')->count();

        $VictoriasRegCount = Applicant::where('campus', 'VC')->where('p_status', '1')->count();
        $VictoriasSchedCount = Applicant::where('campus', 'VC')->where('p_status', '2')->count();

        view()->share(compact('MainRegCount', 'MainSchedCount', 'IlogRegCount', 'IlogSchedCount', 'CauayanRegCount', 'CauayanSchedCount', 'SipalayRegCount', 'SipalaySchedCount', 'HinobaanRegCount', 'HinobaanSchedCount', 'HinigaranRegCount', 'HinigaranSchedCount', 'MoisesRegCount', 'MoisesSchedCount', 'SancarlosRegCount', 'SancarlosSchedCount', 'VictoriasRegCount', 'VictoriasSchedCount'));
    }

    public function index()
    {
        $this->shareCounts();
        $applicantCounts = $this->countApplicantsByCampus();
        $currentYear = Carbon::now()->year;
        
        return view('admission.index', compact('applicantCounts', 'currentYear'));
    }

    public function applicant_add()
    {
        $admissionid = Applicant::orderBy('admission_id', 'desc')->first();
        // $program = Programs::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $program = Programs::orderBy('id', 'asc')->get();
        $strand = Strands::orderBy('id', 'asc')->get();
        $date = AdmissionDate::select('date', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('date')->get();
        $time = Time::select('time', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('time')->get();
        $venue = Venue::select('venue', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('venue')->get();
        return view('admission.applicant.add')
        ->with('admissionid', $admissionid)
        ->with('program', $program)
        ->with('strand', $strand)
        ->with('date', $date)
        ->with('time', $time)
        ->with('venue', $venue);
    }

    public function applicant_list()
    {
        return view('admission.applicant.list');
    }

    public function applicant_list_search()
    {
        return view('admission.applicant.list-search');
    }

    public function srchappList(Request $request)
    {   
        
        $year = $request->query('year');
        $campus = $request->query('campus');

        $currentYear = now()->year;

        $time1 = Time::select('ad_time.*')
                ->where('campus', '=', Auth::user()->campus)
                ->whereYear('created_at', $currentYear)
                ->get();
        $venue1 = Venue::select('venue', DB::raw('count(*) as total'))
                ->where('campus', '=', Auth::user()->campus)
                ->groupBy('venue')
                ->whereYear('created_at', $currentYear)
                ->get();

        return view('admission.applicant.list-search', compact('time1', 'venue1'));
    }

    public function getsrchappList(Request $request)
    {   
        
        $year = $request->query('year');
        $campus = $request->query('campus');

        $data = Applicant::join('ad_applicant_docs', 'ad_applicant_admission.id', '=', 'ad_applicant_docs.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_docs.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->where('p_status', '=', 1)
                        ->get();

        return response()->json(['data' => $data]);
    }

    public function post_applicant_add(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                //'admissionid' => 'required|unique:ad_applicant_admission,admission_id|numeric',
                'type' => 'required',
                'lastname' => 'required|max:191',
                'firstname' => 'required|max:191',
                'email' => 'required|unique:ad_applicant_admission,email|max:191',
                'gender' => 'required',
                'age' => 'required',
                'contact' => 'required|numeric|min:11',
                'preference_1' => 'required',
                'preference_2' => 'required',
            ]);

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


            $existingApplicant = Applicant::where('admission_id', $admissionid)
                ->orWhere(function ($query) use ($request) {
                    $query->where('fname', $request->input('firstname'))
                        ->where('mname', $request->input('mname'))
                        ->where('lname', $request->input('lastname'));
                })
                ->first();

            if ($existingApplicant) {
                return Redirect::route('applicant-add')
                    ->withErrors($validator)
                    ->withInput()
                    ->with('fail', 'Error: Name is already exists!');
            }

            $year = Carbon::now()->format('Y');
            $applicant = new Applicant;
            $applicant->year = $year;
            $applicant->campus = Auth::user()->campus;
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
            $applicant->lstsch_attended = $request->input('lstsch_attended');
            $applicant->strand = $request->input('strand');
            $applicant->suc_lst_attended = $request->input('suc_lst_attended');
            $applicant->course = $request->input('course');
            $applicant->preference_1 = $request->input('preference_1');
            $applicant->preference_2 = $request->input('preference_2');
            $applicant->d_admission = $request->input('d_admission');
            $applicant->time = $request->input('time');
            $applicant->venue = $request->input('venue');
            $dt = Carbon::now();  
            $applicant->created_at = $dt;
            $applicant->save();

            if ($applicant->save()){
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

                return redirect('emp/admission/applicant/add')->with('success', 'Applicant has been successfully created.')->with('admission_id' ,$admissionid);
            }
        } catch (\Exception $e) {
            return redirect()->route('applicant-add')->withErrors($validator)->withInput()->with('fail', $e->getMessage());
        }
    }

    public function applicant_edit_srch($id)
    {
        return redirect()->route('applicant_edit', [encrypt($id)]);
    }

    public function applicant_edit($id)
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
        $admissionid = Applicant::orderBy('admission_id', 'desc')->first();
        $program = Programs::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $strand = Strands::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $date = AdmissionDate::select('date', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('date')->get();
        $time = Time::select('time', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('time')->get();
        $venue = Venue::select('venue', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('venue')->get();
        $date1 = AdmissionDate::select('date', DB::raw('count(*) as total'))->where('campus', '=', Auth::user()->campus)->groupBy('date')->whereYear('created_at', $currentYear)->get();
        
        $time1 = Time::select('ad_time.*')
                ->where('campus', '=', Auth::user()->campus)
                ->whereYear('created_at', $currentYear)
                ->get();

        $venue1 = Venue::select('venue', DB::raw('count(*) as total'))
                ->where('campus', '=', Auth::user()->campus)
                ->groupBy('venue')
                ->whereYear('created_at', $currentYear)
                ->get();

        $docs = ApplicantDocs::where('app_id', '=', $appID)->get();
        foreach ($docs as $doc) {
            if ($doc->doc_image) {
                $imagePath = public_path('storage/' . $doc->doc_image);

                if (file_exists($imagePath)) {
                    $size = File::size($imagePath);
                    $doc->formattedSize = $this->formatSizeUnits($size);
                } else {
                    // Handle the case where the file doesn't exist
                    $doc->formattedSize = 'File not found';
                }

            }
        }
        return view('admission.applicant.edit')
        ->with('applicant', $applicant)
        ->with('docs', $docs)
        ->with('program', $program)
        ->with('strand', $strand)
        ->with('date', $date)
        ->with('date1', $date1)
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

    private function formatSizeUnits($bytes) {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');
        for ($i = 0; $bytes > 1024; $i++) $bytes /= 1024;
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function applicant_update(Request $request, $id)
    {
        $data = request()->all();
        $applicant = Applicant::findOrFail($id);
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
        $applicant->lstsch_attended = $request->input('lstsch_attended');
        $applicant->strand = $request->input('strand');
        $applicant->suc_lst_attended = $request->input('suc_lst_attended');
        $applicant->course = $request->input('course');
        $applicant->preference_1 = $request->input('preference_1');
        $applicant->preference_2 = $request->input('preference_2');
        // $applicant->d_admission = $request->input('d_admission');
        // $applicant->time = $request->input('time');
        // $applicant->venue = $request->input('venue');
        $applicant->update($data);

        $docs = ApplicantDocs::where('app_id', $applicant->id)
        ->update([
            'r_card' => $request->input('r_card'), 
            'g_moral' => $request->input('g_moral'),
            'b_cert' => $request->input('b_cert'),
            'm_cert' => $request->input('m_cert'),
            't_record' => $request->input('t_record'),
            'h_dismissal' => $request->input('h_dismissal'),
        ]);

        return Redirect::route('applicant_edit', encrypt($id))->with('success','Applicant data has been updated');
    }

    // public function applicant_delete($id)
    // {
    //     $applicant = Applicant::findOrFail($id);
    //     if ($applicant == null){return redirect('admission/')->with('fail', 'The Applicant does not exist.');}
    //     if ($applicant->delete()){$docts = ApplicantDocs::where('admission_id','=', $applicant->admission_id)->delete();return back()->with('success', 'The Applicant was successfully deleted.');}else{return back()->with('fail', 'An error was occured while deleting the data.');}
    // }

    public function applicant_delete($id){
        $applicant = Applicant::find($id);
        $applicant->fill(['p_status' => 7])->save();

        return response()->json([
            'status'=>200,
            'message'=>'Updated Successfully',
        ]);
    }
    
    public function applicant_confirm($id)
    {
        $applicant = Applicant::findOrFail($id);

        if ($applicant->d_admission == null && $applicant->time == '00:00:00')
        {
            return Redirect::route('applicant_edit', encrypt($id))->with('fail','Please assign schedule and time for examination before pushing to examination list');
        }
        else
        {
            $applicant->p_status = 2;
            $dt = Carbon::now();  
            $applicant->updated_at = $dt;
            $applicant->update();
            return Redirect::route('examinee_edit', encrypt($id))->with('success','Applicant data has been updated'); 
        }
        
    }

    public function applicant_confirmajax(Request $request) 
    {
        $decryptedId = Crypt::decrypt($request->input('id'));
        $applicantsWithoutSchedule = Applicant::where('p_status', 1)
            ->where('id', $decryptedId)
            ->where(function ($query) {
                $query->whereNull('d_admission')
                    ->where(function ($query) {
                        $query->whereNull('time')
                            ->orWhere('time', '00:00:00');
                    });
            })->exists();

        if ($applicantsWithoutSchedule) {
            return response()->json(['error' => true, 'message' => 'Please assign schedule and time for examination before pushing to examination list.'], 422);
        }
        $affectedRows = Applicant::where('p_status', 1)
            ->where('id', $decryptedId)
            ->update(['p_status' => 2]);

        if ($affectedRows > 0) {
            return response()->json(['success' => true, 'message' => 'Applicant schedule has been pushed to Examinee List'], 200);
        } else {
            return response()->json(['error' => true, 'message' => 'No applicant found with the provided ID or the applicant already has a schedule and time assigned.'], 422);
        }
    }

    public function applicant_schedule($id)
    {
        $applicant = Applicant::find($id);
        $docs = ApplicantDocs::where('app_id', '=', $applicant)->get();
        $date = AdmissionDate::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $time = Time::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $venue = Venue::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        return view('admission.applicant.schedule')
        ->with('applicant', $applicant)
        ->with('docs', $docs)
        ->with('date', $date)
        ->with('time', $time)
        ->with('venue', $venue);
    }

    public function applicant_schedule_save(Request $request, $id)
    {   
        $dateID = $request->input('dateID');
        $d_admission = $request->input('d_admission');
        $time = $request->input('time');
        $venue = $request->input('venue');

        if(empty($dateID) || empty($d_admission) || empty($time) || empty($venue)) {
            return response()->json(['error' => true, 'message' => 'Please fill in all required fields.'], 422);
        }
        $applicant = Applicant::findOrFail($id);
        $applicant->dateID = $request->input('dateID');
        $applicant->d_admission = $request->input('d_admission');
        $applicant->time = $request->input('time');
        $applicant->venue = $request->input('venue');
        $applicant->update();
        //return redirect()->back()->with('success', 'Applicant schedule has been saved');
        return response()->json(['success' => true, 'message' => 'Applicant schedule has been saved'], 200);
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
            $decryptedId = Crypt::decrypt($request->input('id'));
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
    
    public function slots()
    {
        return view('admission.applicant.slots');
    }

    public function slots_search(Request $request)
    {
        $admissionid = Applicant::orderBy('admission_id', 'desc')->first();

        $selectedYear = $request->input('date');

        $dateAd = DB::table('ad_time')
                ->select('date', 'campus', DB::raw('count(*) as total'))
                ->whereYear('date', $selectedYear)
                ->where('campus', $request->input('campus'))
                ->groupBy('date', 'campus')
                ->get();
        $totalSearchResults = count($dateAd);
        return view('admission.applicant.slot_search', compact('dateAd', 'admissionid', 'totalSearchResults'));
    }

    public function configure_admission()
    {
        $currentYear = now()->year;

        $program = Programs::orderBy('id', 'asc')->get();
        $strand = Strands::orderBy('id', 'asc')->get();
        $date = AdmissionDate::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->whereYear('created_at', $currentYear)->get();
        $dates = AdmissionDate::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->whereYear('created_at', $currentYear)->get();
        $time = Time::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->whereYear('created_at', $currentYear)->get();
        $venue = Venue::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->whereYear('created_at', $currentYear)->get();
        return view('admission.configure.index')
        ->with('program', $program)
        ->with('strand', $strand)
        ->with('date', $date)
        ->with('dates', $dates)
        ->with('time', $time)
        ->with('venue', $venue);
    }

    public function add_Program(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'code' => 'required',
                'program' => 'required',
            ]);

            $codeName = $request->input('code'); 
            $existingCode = Programs::where('code', $codeName)->first();

            if ($existingCode) {
                return redirect()->route('configure_admission')->with('fail', 'Code already exists!');
            }

            $dt = Carbon::now();

            try {
                Programs::create([
                    'campus' => Auth::user()->campus,
                    'code' => $codeName,
                    'program' => $request->input('program'),
                    'created_at' => $dt,
                ]);

                return redirect()->route('configure_admission')->with('success', 'Program stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route('configure_admission')->with('fail', 'Failed to store program!');
            }
        }
    }

    public function add_Strand(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'code' => 'required',
                'strand' => 'required',
            ]);

            $codeName = $request->input('code'); 
            $existingCode = Strands::where('code', $codeName)->first();

            if ($existingCode) {
                return redirect()->route('configure_admission')->with('fail', 'Code already exists!');
            }

            $dt = Carbon::now();

            try {
                Strands::create([
                    'campus' => Auth::user()->campus,
                    'code' => $codeName,
                    'strand' => $request->input('strand'),
                    'created_at' => $dt,
                ]);

                return redirect()->route('configure_admission')->with('success', 'Strand stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route('configure_admission')->with('fail', 'Failed to store strand!');
            }
        }
    }

    public function add_admission_date(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'date' => 'required',
            ]);

            $dateName = $request->input('date'); 
            $existingDate = AdmissionDate::where('date', $dateName)->first();

            if ($existingDate) {
                return redirect()->route('configure_admission')->with('fail', 'Date already exists!');
            }

            $dt = Carbon::now();

            try {
                AdmissionDate::create([
                    'campus' => Auth::user()->campus,
                    'date' => $dateName,
                    'created_at' => $dt,
                ]);

                return redirect()->route('configure_admission')->with('success', 'Date stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route('configure_admission')->with('fail', 'Failed to store date!');
            }
        }
    }

    public function edit_program($id)
    {
        $progID = decrypt($id);
        $program = Programs::find($progID);

        return view('admission.configure.editProgram', compact('program'));
    }

    public function programEdit(Request $request)
    {   
        $request->validate([
            'code' => 'required',
            'program' => 'required',
        ]);

        try {
            $codeName = $request->input('code');
            $existingCode = Programs::where('code', $codeName)->where('id', '!=', $request->input('id'))->first();

            if ($existingCode) {
                return redirect()->back()->with('fail', 'Program Code already exists!');
            }

            $program = Programs::findOrFail($request->input('id'));
            $program->update([
                'code' => $request->input('code'),
                'program' => $request->input('program'),
            ]);

            return redirect()->route('edit_program', ['id' => encrypt($program->id)])->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Failed to update Program!');
        }
    }

    public function programDelete($id)
    {
        $program = Programs::findOrFail($id);

        if ($program == null)
        {
            return back()->with('fail', 'The program data does not exist.');
        }
        if ($program->delete())
        {
            return Redirect::route('configure_admission')->with('success', 'The program data was successfully deleted.');
        }
        else
        {
            return back()->with('fail', 'An error was occured while deleting the data.');
        }
    }

    public function edit_strand($id)
    {
        $strandID = decrypt($id);
        $strand = Strands::find($strandID);

        return view('admission.configure.editStrand', compact('strand'));
    }

    public function strandEdit(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'strand' => 'required',
        ]);

        try {
            $codeName = $request->input('code');
            $existingCode = Programs::where('code', $codeName)->where('id', '!=', $request->input('id'))->first();

            if ($existingCode) {
                return redirect()->back()->with('fail', 'Strand Code already exists!');
            }

            $strand = Strands::findOrFail($request->input('id'));
            $strand->update([
                'code' => $request->input('code'),
                'strand' => $request->input('strand'),
            ]);

            return redirect()->route('edit_strand', ['id' => encrypt($strand->id)])->with('success', 'Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Failed to update Strand!');
        }
    }

    public function strandDelete($id)
    {
        $strand = Strands::findOrFail($id);

        if ($strand == null)
        {
            return back()->with('fail', 'The strand data does not exist.');
        }
        if ($strand->delete())
        {
            return Redirect::route('configure_admission')->with('success', 'The strand data was successfully deleted.');
        }
        else
        {
            return back()->with('fail', 'An error was occured while deleting the data.');
        }
    }

    public function edit_date($id)
    {
        $dateID = decrypt($id);
        $dates = AdmissionDate::find($dateID);

        return view('admission.configure.editDate', compact('dates'));
    }

    public function dateEdit(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);

        try {
            $dateName = $request->input('date');
            $existingDate = AdmissionDate::where('date', $dateName)->where('id', '!=', $request->input('id'))->first();

            if ($existingDate) {
                return redirect()->back()->with('fail', 'Admission Date already exists!');
            }

            $dates = AdmissionDate::findOrFail($request->input('id'));
            $dates->update([
                'date' => $request->input('date'),
            ]);

            return redirect()->route('edit_date', ['id' => encrypt($dates->id)])->with('success', 'Admission Date Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Failed to update Admission Date!');
        }
    }

    public function dateDelete($id)
    {
        $date = AdmissionDate::findOrFail($id);

        if ($date == null)
        {
            return back()->with('fail', 'The strand data does not exist.');
        }
        if ($date->delete())
        {
            return Redirect::route('configure_admission')->with('success', 'The date data was successfully deleted.');
        }
        else
        {
            return back()->with('fail', 'An error was occured while deleting the data.');
        }
    }

    public function add_admission_time(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'date' => 'required',
                'time' => 'required',
                'slots' => 'required',
            ]);

            $dateTime = $request->input('date') . ' ' . $request->input('time');
            $existingTime = Time::where('campus', Auth::user()->campus)
                                ->where('date', $request->input('date'))
                                ->where('time', $request->input('time'))
                                ->first();

            if ($existingTime) {
                return redirect()->route('configure_admission')->with('fail', 'Datetime already exists!');
            }

            $dt = Carbon::now();

            try {
                Time::create([
                    'campus' => Auth::user()->campus,
                    'date' => $request->input('date'),
                    'time' => $request->input('time'),
                    'slots' => $request->input('slots'),
                    'created_at' => $dt,
                ]);

                return redirect()->route('configure_admission')->with('success', 'Datetime stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route('configure_admission')->with('fail', 'Failed to store Datetime!');
            }
        }
    }

    public function edit_time($id)
    {
        $timeID = decrypt($id);
        $times = Time::find($timeID);

        return view('admission.configure.editTime', compact('times'));
    }

    public function timeEdit(Request $request)
    {
        $request->validate([
            'date' => 'required',
            'time' => 'required',
            'slots' => 'required',
        ]);

        try {
            $dates = Time::findOrFail($request->input('id'));

            if ($dates->date !== $request->input('date')) {
                $existingTime = Time::where('date', $request->input('date'))
                    ->where('id', '!=', $request->input('id'))
                    ->first();

                if ($existingTime) {
                    return redirect()->back()->with('fail', 'Admission Time already exists!');
                }
            }

            $dates->update([
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'slots' => $request->input('slots'),
            ]);

            return redirect()->route('edit_time', ['id' => encrypt($dates->id)])->with('success', 'Admission Time Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Failed to update Admission Time!');
        }
    }


    public function timeDelete($id)
    {
        $time = Time::findOrFail($id);

        if ($time == null)
        {
            return back()->with('fail', 'The time data does not exist.');
        }
        if ($time->delete())
        {
            return Redirect::route('configure_admission')->with('success', 'The time data was successfully deleted.');
        }
        else
        {
            return back()->with('fail', 'An error was occured while deleting the data.');
        }
    }

    public function add_admission_venue(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'venue' => 'required',
            ]);

            $venueName = $request->input('venue'); 
            $existingVenue = Venue::where('venue', $venueName)->first();

            if ($existingVenue) {
                return redirect()->route('configure_admission')->with('fail', 'Venue already exists!');
            }

            $dt = Carbon::now();

            try {
                Venue::create([
                    'campus' => Auth::user()->campus,
                    'venue' => $request->input('venue'),
                    'created_at' => $dt,
                ]);

                return redirect()->route('configure_admission')->with('success', 'Venue stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route('configure_admission')->with('fail', 'Failed to store Venue!');
            }
        }
    }

    public function edit_venue($id)
    {
        $venueID = decrypt($id);
        $venues = Venue::find($venueID);

        return view('admission.configure.editVenue', compact('venues'));
    }

    public function venueEdit(Request $request)
    {
        $request->validate([
            'venue' => 'required',
        ]);

        try {
            $venueName = $request->input('venue');
            $existingVenue = Venue::where('venue', $venueName)->where('id', '!=', $request->input('id'))->first();

            if ($existingVenue) {
                return redirect()->back()->with('fail', 'Admission Venue already exists!');
            }

            $venues = Venue::findOrFail($request->input('id'));
            $venues->update([
                'venue' => $request->input('venue'),
            ]);

            return redirect()->route('edit_venue', ['id' => encrypt($venues->id)])->with('success', 'Admission Venue Updated Successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('fail', 'Failed to update Admission Venue!');
        }
    }

    public function venueDelete($id)
    {
        $venue = Venue::findOrFail($id);

        if ($venue == null)
        {
            return back()->with('fail', 'The venue data does not exist.');
        }
        if ($venue->delete())
        {
            return Redirect::route('configure_admission')->with('success', 'The venue data was successfully deleted.');
        }
        else
        {
            return back()->with('fail', 'An error was occured while deleting the data.');
        }
    }


}
