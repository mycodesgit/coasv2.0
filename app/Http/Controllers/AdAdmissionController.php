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
use App\Models\AdmissionDB\Year;

use App\Models\ScheduleDB\Faculty;

class AdAdmissionController extends Controller
{
    public function countApplicantsByCampus()
    {
        $currentYear = Year::where('status', 'On')->value('adyear');
        $campuses = ['MC', 'VC', 'SCC', 'MP', 'HC', 'IC', 'CA', 'CC', 'SC', 'HinC'];

        $counts = [];
        $totalCount = 0;

        foreach ($campuses as $campus) {
            $count = Applicant::where('campus', $campus)->where('year', $currentYear)->count();
            $counts[$campus] = $count;
            $totalCount += $count;
        }

        $counts['total'] = $totalCount;
        return $counts;
    }

    private function shareCounts()
    {
        $currentYear = Year::where('status', 'On')->value('adyear');

        $MainRegCount = Applicant::where('campus', 'MC')->where('p_status', 1)->where('year', $currentYear)->count();
        $MainSchedCount = Applicant::where('campus', 'MC')->where('p_status', 2)->where('year', $currentYear)->count();


        $IlogRegCount = Applicant::where('campus', 'IC')->where('p_status', '1')->where('year', $currentYear)->count();
        $IlogSchedCount = Applicant::where('campus', 'IC')->where('p_status', '2')->where('year', $currentYear)->count();

        $CauayanRegCount = Applicant::where('campus', 'CC')->where('p_status', '1')->where('year', $currentYear)->count();
        $CauayanSchedCount = Applicant::where('campus', 'CC')->where('p_status', '2')->where('year', $currentYear)->count();

        $CandoniRegCount = Applicant::where('campus', 'CA')->where('p_status', '1')->where('year', $currentYear)->count();
        $CandoniSchedCount = Applicant::where('campus', 'CA')->where('p_status', '2')->where('year', $currentYear)->count();

        $SipalayRegCount = Applicant::where('campus', 'SC')->where('p_status', '1')->where('year', $currentYear)->count();
        $SipalaySchedCount = Applicant::where('campus', 'SC')->where('p_status', '2')->where('year', $currentYear)->count();

        $HinobaanRegCount = Applicant::where('campus', 'HinC')->where('p_status', '1')->where('year', $currentYear)->count();
        $HinobaanSchedCount = Applicant::where('campus', 'HinC')->where('p_status', '2')->where('year', $currentYear)->count();

        $HinigaranRegCount = Applicant::where('campus', 'HC')->where('p_status', '1')->where('year', $currentYear)->count();
        $HinigaranSchedCount = Applicant::where('campus', 'HC')->where('p_status', '2')->where('year', $currentYear)->count();

        $MoisesRegCount = Applicant::where('campus', 'MP')->where('p_status', '1')->where('year', $currentYear)->count();
        $MoisesSchedCount = Applicant::where('campus', 'MP')->where('p_status', '2')->where('year', $currentYear)->count();

        $SancarlosRegCount = Applicant::where('campus', 'SCC')->where('p_status', '1')->where('year', $currentYear)->count();
        $SancarlosSchedCount = Applicant::where('campus', 'SCC')->where('p_status', '2')->where('year', $currentYear)->count();

        $VictoriasRegCount = Applicant::where('campus', 'VC')->where('p_status', '1')->where('year', $currentYear)->count();
        $VictoriasSchedCount = Applicant::where('campus', 'VC')->where('p_status', '2')->where('year', $currentYear)->count();

        view()->share(compact('MainRegCount', 'MainSchedCount', 'IlogRegCount', 'IlogSchedCount', 'CauayanRegCount', 'CauayanSchedCount', 'CandoniRegCount', 'CandoniSchedCount', 'SipalayRegCount', 'SipalaySchedCount', 'HinobaanRegCount', 'HinobaanSchedCount', 'HinigaranRegCount', 'HinigaranSchedCount', 'MoisesRegCount', 'MoisesSchedCount', 'SancarlosRegCount', 'SancarlosSchedCount', 'VictoriasRegCount', 'VictoriasSchedCount'));
    }

    public function index()
    {
        $this->shareCounts();
        $applicantCounts = $this->countApplicantsByCampus();

        $currentYear = Year::where('status', 'On')->value('adyear');
        $user = Auth::guard('web')->user()->dept;
        $campus = Auth::guard('web')->user()->campus;

        $applyapp = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                    ->where('ad_applicant_admission.year', $currentYear)
                    ->where('ad_applicant_admission.campus', $campus)
                    ->where('p_status', '=', 1)
                    ->count();

        $examineesapp = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                    ->where('ad_applicant_admission.year', $currentYear)
                    ->where('ad_applicant_admission.campus', $campus)
                    ->where('p_status', '=', 2)
                    ->count();

        $resultapp = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                    ->where('ad_applicant_admission.year', $currentYear)
                    ->where('ad_applicant_admission.campus', $campus)
                    ->where('p_status', '=', 3)
                    ->count();

        $cnfrmapp = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                    ->where('ad_applicant_admission.year', $currentYear)
                    ->where('ad_applicant_admission.campus', $campus)
                    ->where('p_status', '=', 4)
                    ->count();

        $acptapp = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                    ->where('ad_applicant_admission.year', $currentYear)
                    ->where('ad_applicant_admission.campus', $campus)
                    ->where('ad_applicant_dept_rating.deptcol', $user)
                    ->whereIn('p_status', [5, 6])
                    ->count();

        $acptapppushen = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                    ->where('ad_applicant_admission.year', $currentYear)
                    ->where('ad_applicant_admission.campus', $campus)
                    ->where('ad_applicant_dept_rating.deptcol', $user)
                    ->where('p_status', '=', 6)
                    ->count();

        $acptappnotpushen = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                    ->where('ad_applicant_admission.year', $currentYear)
                    ->where('ad_applicant_admission.campus', $campus)
                    ->where('ad_applicant_dept_rating.deptcol', $user)
                    ->where('p_status', '=', 5)
                    ->count();

        return view('admission.index', compact('applicantCounts', 'currentYear', 'applyapp', 'examineesapp', 'resultapp', 'cnfrmapp', 'acptapp', 'acptapppushen', 'acptappnotpushen'));
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



    public function applicantCreate(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
            ]);

            $existingApplicantValidator = Validator::make([], []);

            $existingApplicant = Applicant::where('lname', $request->input('lname'))
                ->where('fname', $request->input('fname'))
                ->whereYear('created_at', Carbon::now()->year)
                ->first();

            if ($existingApplicant) {
                $existingApplicantValidator->errors()->add('existing_applicant', 'Error: You have already registered this year.');
            }

            if ($existingApplicantValidator->fails()) {
                return Redirect::route('admission-apply')
                    ->withErrors($existingApplicantValidator)
                    ->withInput()
                    ->with('fail', $existingApplicantValidator->errors()->first('existing_applicant'));
            }

            $todayRegistrations = Applicant::whereDate('created_at', today())->count();

            if ($todayRegistrations >= 500) {
                return Redirect::route('admission-apply')->withInput()->with('fail', 'Error: Daily registration limit reached!');
            }

            $campus = Auth::guard('web')->user()->campus;
            // $year = Carbon::now()->format('Y');
            $year = Year::where('status', 'On')->value('adyear');

            $latestApplicant = Applicant::where('campus', $campus)->latest('created_at')->first();
            $latestId = empty($latestApplicant) || date('Y', strtotime($latestApplicant->created_at)) < $year
                ? 0
                : (int)substr($latestApplicant->admission_id, -4);

            $newId = $latestId + 1;
            $paddedValue = str_pad($newId, 4, '0', STR_PAD_LEFT);
            $admissionid = $year . $paddedValue;

            $existingAdID = Applicant::where('admission_id', $admissionid)->where('campus', $campus)->first();

            if ($existingAdID) {
                $admissionid = $existingAdID->admission_id + 1;
            }

            try {
                $applicantID = Applicant::create([
                    // 'year' => Carbon::now()->format('Y'),
                    'year' => Year::where('status', 'On')->value('adyear'),
                    'campus' => Year::where('status', 'On')->value('adyear'),
                    'admission_id' => $admissionid,
                    'type' => $request->input('type'),
                    'lname' => $request->input('lname'),
                    'fname' => $request->input('fname'),
                    'mname' => $request->input('mname'),
                    'ext' => $request->input('ext'),
                    'gender' => $request->input('gender'),
                    'bday' => $request->input('bday'),
                    'age' => $request->input('age'),
                    'contact' => $request->input('contact'),
                    'email' => $request->input('email'),
                    'civil_status' => $request->input('civil_status'),
                    'address' => $request->input('address'),
                    'hnum' => $request->input('hnum'),
                    'brgy' => $request->input('brgy'),
                    'city' => $request->input('city'),
                    'province' => $request->input('province'),
                    'region' => $request->input('region'),
                    'zcode' => $request->input('zcode'),
                    'religion' => $request->input('religion'),
                    'monthly_income' => $request->input('monthly_income'),
                    'lstsch_attended' => $request->input('lstsch_attended'),
                    'strand' => $request->input('strand'),
                    'suc_lst_attended' => $request->input('suc_lst_attended'),
                    'course' => $request->input('course'),
                    'preference_1' => $request->input('preference_1'),
                    'preference_2' => $request->input('preference_2'),
                ]);

                ApplicantDocs::create([
                    'app_id' => $applicantID->id,
                    'campus' => $request->input('campus'),
                    'admission_id' => $admissionid,
                    'r_card' => $request->input('r_card'),
                    'g_moral' => $request->input('g_moral'),
                    't_record' => $request->input('t_record'),
                    'b_cert' => $request->input('b_cert'),
                    'h_dismissal' => $request->input('h_dismissal'),
                    'm_cert' => $request->input('m_cert'),
                ]);

                ExamineeResult::create([
                    'app_id' => $applicantID->id,
                    'campus' => $request->input('campus'),
                    'admission_id' => $admissionid,
                ]);

                DeptRating::create([
                    'app_id' => $applicantID->id,
                    'campus' => $request->input('campus'),
                    'admission_id' => $admissionid,
                ]);
                return redirect()->route('applicant-add')->with(['success' => true, 'message' => 'Applicant Added Successfully'], 200);
                //return response()->json(['success' => true, 'message' => 'Applicant Added Successfully'], 200);
            } catch (\Exception $e) {
                //return response()->json(['error' => true, 'message' => 'Failed to add Applicant'], 404);
                return redirect()->route('applicant-add')->with('error', 'Failed to store user!');
            }
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



    public function slots()
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $currentYear = Year::where('status', 'On')->value('adyear');
        return view('admission.applicant.slots', compact('curryear'));
    }

    public function slots_search(Request $request)
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();

        $selectedYear = $request->input('date');
        $selectedCampus = $request->input('campus');

        return view('admission.applicant.slot_search', compact('curryear'));
    }

    public function slots_ajax(Request $request)
    {
        $selectedCampus = $request->input('campus');
        $selectedYear = $request->input('date');

        // 1. Get all slots for the year
        $slots = Time::whereYear('date', $selectedYear)
            ->where('campus', $selectedCampus)
            ->orderBy('date')
            ->orderBy('time')
            ->select('date', 'time', 'campus as timecampus', 'slots')
            ->get();

        // 2. Get bookings grouped
        $bookings = Applicant::selectRaw('DATE(d_admission) as date, time, COUNT(*) as total')
            ->whereYear('d_admission', $selectedYear)
            ->where('campus', $selectedCampus)
            ->where('p_status', '!=', 7)
            ->groupBy('date', 'time')
            ->get()
            ->keyBy(function ($item) {
                return $item->date . '_' . $item->time;
            });

        // 3. Group slots by date
        $groupedSlots = $slots->groupBy(function ($slot) {
            return \Carbon\Carbon::parse($slot->date)->format('Y-m-d');
        });

        return view('admission.applicant.partials.slotstable', compact('groupedSlots', 'bookings'))->render();
    }

    public function configure_admission()
    {
        $currentYear = now()->year;

        //$program = Programs::orderBy('id', 'asc')->get();
        // $strand = Strands::orderBy('id', 'asc')->get();
        // $date = AdmissionDate::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->whereYear('created_at', $currentYear)->get();
        $dates = AdmissionDate::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->whereYear('created_at', $currentYear)->get();
        // $time = Time::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->whereYear('created_at', $currentYear)->get();
        $venue = Venue::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->whereYear('created_at', $currentYear)->get();
        $curryear = Year::where('status', '=', 'On')->get();
        return view('admission.configure.index', compact('dates', 'venue', 'curryear'));
    }

    public function fetchDates()
    {
        $currentYear = Year::where('status', 'On')->value('adyear');
        $dates = AdmissionDate::orderBy('id', 'asc')
                    ->where('campus', Auth::user()->campus)
                    // ->whereYear('date', $currentYear)
                    ->get();

        return response()->json(['dates' => $dates]);
    }

    public function configure_admissionajax()
    {
        $currentYear = now()->year;

        $program = Programs::orderBy('id', 'asc')->get();

        return response()->json([
            'data' => $program,
        ]);
    }

    public function configure_admissionstrandajax()
    {
        $currentYear = now()->year;

        $strand = Strands::orderBy('id', 'asc')->get();

        return response()->json([
            'data' => $strand
        ]);
    }

    public function configure_admissiondateajax()
    {
        $currentYear = Year::where('status', 'On')->value('adyear'); // Fetches only the 'year' column for the active year

        $date = AdmissionDate::orderBy('id', 'asc')
            ->where('campus', Auth::user()->campus)
            ->whereYear('date', $currentYear)
            ->get();

        return response()->json([
            'data' => $date,
        ]);
    }

    public function configure_admissiondatetimeajax()
    {
        //$currentYear = now()->year;
        $currentYear = Year::where('status', 'On')->value('adyear');

        $time = Time::orderBy('id', 'asc')
            ->where('campus', Auth::user()->campus)
            ->whereYear('date', $currentYear)
            ->get();

        return response()->json([
            'data' => $time,
        ]);
    }

    public function configure_admissionvenueajax()
    {
        $currentYear = Year::where('status', 'On')->value('adyear');

        $venue = Venue::orderBy('id', 'asc')
            ->where('campus', Auth::user()->campus)
            ->where('adyear', $currentYear)
            ->get();

        return response()->json([
            'data' => $venue,
        ]);
    }

    public function configure_admissionyearajax()
    {
        $curryear = Year::orderBy('updated_at', 'DESC')->get();

        return response()->json([
            'data' => $curryear,
        ]);
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
                //return redirect()->route('configure_admission')->with('fail', 'Code already exists!');
                return response()->json(['error' => true, 'message' => 'Code already exists']);
            }

            $dt = Carbon::now();

            try {
                Programs::create([
                    'campus' => Auth::user()->campus,
                    'code' => $codeName,
                    'program' => $request->input('program'),
                    'created_at' => $dt,
                ]);

                //return redirect()->route('configure_admission')->with('success', 'Program stored successfully!');
                return response()->json(['success' => true, 'message' => 'Program stored successfully']);
            } catch (\Exception $e) {
                //return redirect()->route('configure_admission')->with('fail', 'Failed to store program!');
                return response()->json(['error' => true, 'message' => 'Failed to store program!']);
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
                return response()->json(['erro' => true, 'message' => 'Code already exist!']);
            }

            $dt = Carbon::now();

            try {
                Strands::create([
                    'campus' => Auth::user()->campus,
                    'code' => $codeName,
                    'strand' => $request->input('strand'),
                    'created_at' => $dt,
                ]);

                return response()->json(['success' => true, 'message' => 'Strand stored successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to stored strand']);
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
            $existingDate = AdmissionDate::where('date', $dateName)->where('campus', Auth::guard('web')->user()->campus)->first();

            if ($existingDate) {
                return response()->json(['error' => true, 'message' => 'Date already exists!']);
            }

            $dt = Carbon::now();

            try {
                AdmissionDate::create([
                    'campus' => Auth::user()->campus,
                    'date' => $dateName,
                    'created_at' => $dt,
                ]);

                return response()->json(['success' => true, 'message' => 'Date stored successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store date']);
            }
        }
    }

    public function programUpdate(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'program' => 'required',
        ]);

        try {
            $codeName = $request->input('code');
            $existingCode = Programs::where('code', $codeName)->where('id', '!=', $request->input('id'))->first();

            if ($existingCode) {
                //return redirect()->back()->with('fail', 'Program Code already exists!');
                return response()->json(['error' => true, 'message' => 'Program Code exist!']);
            }

            $program = Programs::findOrFail($request->input('id'));
            $program->update([
                'code' => $request->input('code'),
                'program' => $request->input('program'),
            ]);

            //return redirect()->route('edit_program', ['id' => encrypt($program->id)])->with('success', 'Updated Successfully');
            return response()->json(['success' => true, 'message' => 'Program Update successfully']);
        } catch (\Exception $e) {
            //return redirect()->back()->with('fail', 'Failed to update Program!');
            return response()->json(['error' => true, 'message' => 'Failed to update Program']);
        }
    }

    public function programDelete($id)
    {
        $prgrm = Programs::find($id);
        $prgrm->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

    public function strandUpdate(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'strand' => 'required',
        ]);

        try {
            $codeName = $request->input('code');
            $existingCode = Strands::where('code', $codeName)->where('id', '!=', $request->input('id'))->first();

            if ($existingCode) {
                return response()->json(['error' => true, 'message' => 'Strand Code exist!']);
            }

            $strand = Strands::findOrFail($request->input('id'));
            $strand->update([
                'code' => $request->input('code'),
                'strand' => $request->input('strand'),
            ]);

            return response()->json(['success' => true, 'message' => 'Strand Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Strand!']);
        }
    }

    public function strandDelete($id)
    {
        $strnd = Strands::find($id);
        $strnd->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

    public function dateUpdate(Request $request)
    {
        $request->validate([
            'date' => 'required',
        ]);

        try {
            $dateName = $request->input('date');
            $existingDate = AdmissionDate::where('date', $dateName)->where('id', '!=', $request->input('id'))->first();

            if ($existingDate) {
                return response()->json(['error' => true, 'message' => 'Admission Date already exists!']);
            }

            $dates = AdmissionDate::findOrFail($request->input('id'));
            $dates->update([
                'date' => $request->input('date'),
            ]);

            return response()->json(['success' => true, 'message' => 'Admission Date Updated Successfully']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Admission Date!']);
        }
    }

    public function dateDelete($id)
    {
        $dte = AdmissionDate::find($id);
        $dte->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
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
                return response()->json(['error' => true, 'message' => 'Datetime already exists!']);
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

                return response()->json(['success' => true, 'message' => 'Datetime stored successfully']);
            } catch (\Exception $e) {
                return response()->json(['success' => true, 'message' => 'Failed to store Datetime!']);
            }
        }
    }

    public function timeUpdate(Request $request)
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
                    return response()->json(['error' => true, 'message' => 'Admission Time already exists!']);
                }
            }

            $dates->update([
                'date' => $request->input('date'),
                'time' => $request->input('time'),
                'slots' => $request->input('slots'),
            ]);

            return response()->json(['success' => true, 'message' => 'Admission Date and Time Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Admission Date and Time!']);
        }
    }


    public function timeDelete($id)
    {
        $dtime = Time::find($id);
        $dtime->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

    public function add_admission_venue(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'adyear' => 'required',
                'venue' => 'required',
            ]);

            $currentYear = Year::where('status', 'On')->value('adyear');

            $venueName = $request->input('venue');
            $existingVenue = Venue::where('venue', $venueName)->where('adyear', $currentYear)->first();

            if ($existingVenue) {
                return response()->json(['error' => true, 'message' => 'Venue already exists!']);
            }


            try {
                Venue::create([
                    'campus' => Auth::user()->campus,
                    'adyear' => $request->input('adyear'),
                    'venue' => $request->input('venue'),
                ]);

                return response()->json(['success' => true, 'message' => 'Venue stored successfully!']);
            } catch (\Exception $e) {
                return redirect()->route('configure_admission')->with('fail', 'Failed to store Venue!');
                return response()->json(['error' => true, 'message' => 'Date already exists!']);
            }
        }
    }

    public function venueUpdate(Request $request)
    {
        $request->validate([
            'venue' => 'required',
        ]);

        try {
            $venueName = $request->input('venue');
            $existingVenue = Venue::where('venue', $venueName)->where('id', '!=', $request->input('id'))->first();

            if ($existingVenue) {
                return response()->json(['error' => true, 'message' => 'Admission Venue already exists!']);
            }

            $venues = Venue::findOrFail($request->input('id'));
            $venues->update([
                'venue' => $request->input('venue'),
            ]);

            return response()->json(['success' => true, 'message' => 'Admission Venue Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Admission Venue!']);
        }
    }

    public function venueDelete($id)
    {
        $dvenue = Venue::find($id);
        $dvenue->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

    public function add_admission_year(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'adyear' => 'required',
            ]);

            $yearName = $request->input('adyear');
            $existingYear = Year::where('adyear', $yearName)->first();

            if ($existingYear) {
                return response()->json(['error' => true, 'message' => 'Year already exists!']);
            }

            try {
                Year::create([
                    'adyear' => $yearName,
                    'status' => 'Off',
                ]);

                return response()->json(['success' => true, 'message' => 'Year stored successfully']);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store year']);
            }
        }
    }

    public function yearUpdate(Request $request)
    {
        $request->validate([
            'adyear' => 'required',
            'status' => 'required',
        ]);

        try {
            $yearName = $request->input('adyear');
            $existingYear = Year::where('adyear', $yearName)->where('id', '!=', $request->input('id'))->first();

            if ($existingYear) {
                return response()->json(['error' => true, 'message' => 'Year already exist!']);
            }

            $curryear = Year::findOrFail($request->input('id'));
            $curryear->update([
                'adyear' => $request->input('adyear'),
                'status' => $request->input('status'),
            ]);

            return response()->json(['success' => true, 'message' => 'Year Updated Successfully!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Year!']);
        }
    }

    public function yearDelete($id)
    {
        $dyear = Year::find($id);
        $dyear->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }
}
