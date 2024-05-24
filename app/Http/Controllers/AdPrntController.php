<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
use PDF;

class AdPrntController extends Controller
{
    public function applicant_print(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id);
        return view('admission.applicant.printView')->with('applicant', $applicant);
    }

    public function applicant_genPDF(Request $request, $id)
    {
        $applicant = Applicant::find($id); 
        view()->share('applicant',$applicant);
        $pdf = PDF::loadView('admission.applicant.print');
        return $pdf->stream();
    }

    public function applicant_permit(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id); 
        return view('admission.applicant.printPermit')->with('applicant', $applicant);
    }

    public function applicant_genPermit(Request $request, $id)
    {
        $applicant = Applicant::findOrFail($id); 
        view()->share('applicant',$applicant);
        $pdf = PDF::loadView('admission.applicant.printViewPermit');
        return $pdf->stream();
        return view('admission.applicant.printPermit')->with('applicant', $applicant);
    }

    public function pre_enrolment_print_srch($id)
    {
        return redirect()->route('pre_enrolment_print', [encrypt($id)]);
    }

    public function conpre_enrolment_print_srch($id)
    {
        return redirect()->route('pre_enrolment_print', [encrypt($id)]);
    }

    public function pre_enrolment_print(Request $request, $id)
    {
        $appID = decrypt($id);
        $examinee = Applicant::findOrFail($appID); 
        return view('admission.examinee.printPreEnrolmentView')->with('examinee', $examinee);
    }

    public function genPreEnrolment(Request $request, $id)
    {
        $examinee = Applicant::findOrFail($id); 
        view()->share('examinee',$examinee); 
        $pdf = PDF::loadView('admission.examinee.genPreEnrolment')->setPaper('Legal', 'portrait');
        return $pdf->stream();
        return view('admission.examinee.printPreEnrolmentView')->with('examinee', $examinee);
    }

    public function applicant_printing()
    {
        $repdates = AdmissionDate::groupBy('date')->pluck('date');
        $time = Time::all();
        return view('admission.reports.applicants', compact('repdates', 'time'));
    }

    public function applicant_reports(Request $request)
    {
        $strand = Strands::orderBy('id', 'asc')->get();

        $data = Applicant::where('p_status', '!=', 6);

        if ($request->year) {
            $data = $data->where('year', $request->year);
        }

        if ($request->campus) {
            $data = $data->where('campus', $request->campus);
        }

        if ($request->strand && $request->strand !== 'All') {
            $data = $data->where('strand', $request->strand);
        }

        $data = $data->get();

        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);

        return view('admission.reports.applicantsgen', ['data' => $data, 'totalSearchResults' => $totalSearchResults])
            ->with('strand', $strand);
    }

    public function applicantPDF_reports(Request $request)
    {
        try {
            $selectedYear = $request->query('year', []);
            $selectedCampus = $request->query('campus', []);
            $selectedStrand = $request->query('strand', []);

            $selectedYear = is_array($selectedYear) ? $selectedYear : [$selectedYear];
            $selectedCampus = is_array($selectedCampus) ? $selectedCampus : [$selectedCampus];
            $selectedStrand = is_array($selectedStrand) ? $selectedStrand : [$selectedStrand];

            $query = Applicant::select('ad_applicant_admission.*')
                            ->whereIn('ad_applicant_admission.year', $selectedYear)
                            ->whereIn('ad_applicant_admission.campus', $selectedCampus);

            if ($selectedStrand && $selectedStrand[0] !== 'All') {
                $query->whereIn('ad_applicant_admission.strand', $selectedStrand);
            }

            $data = $query->where('p_status', '!=', 6)->get();

            $totalSearchResults = count($data);

            $pdf = PDF::loadView('admission.reports.pdf.applicantPDF', ['data' => $data, 'totalSearchResults' => $totalSearchResults])->setPaper('Legal', 'landscape');
            return $pdf->stream();
        } catch (\Exception $e) {
            \Log::error('Error in applicantPDF_reports: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function schedules_printing()
    {
        $repdates = AdmissionDate::orderBy('date', 'desc')->where('campus', '=', Auth::user()->campus)->groupBy('date')->pluck('date');
        $time = Time::all();
        $sortedTime = $time
            ->where('campus', '=', Auth::user()->campus)
            ->filter(function ($data) {
                return Carbon::parse($data->date . ' ' . $data->time)->isCurrentYear();
        })
        ->sortBy(function ($data) {
            return Carbon::parse($data->date . ' ' . $data->time)->format('YmH:i');
        });
        $strand = Strands::orderBy('id', 'asc')->get();
        $date = AdmissionDate::select('date', DB::raw('count(*) as total'))->groupBy('date')->get();
        $venue = Venue::select('venue', DB::raw('count(*) as total'))->groupBy('venue')->get();
        
        return view('admission.reports.schedules', compact('strand', 'date', 'time', 'sortedTime', 'venue', 'repdates'));
    }


    public function schedules_reports(Request $request)
    {
        $repdates = AdmissionDate::orderBy('date', 'desc')->groupBy('date')->pluck('date');
        $time = Time::all();
        $strand = Strands::orderBy('id', 'asc')->get();
        $venue = Venue::select('venue', DB::raw('count(*) as total'))->groupBy('venue')->get();

        $selectedYear = $request->query('year', []);
        $selectedCampus = $request->query('campus', []);
        $selectedDates = $request->query('date', []);

        $selectedYear = is_array($selectedYear) ? $selectedYear : [$selectedYear];
        $selectedCampus = is_array($selectedCampus) ? $selectedCampus : [$selectedCampus];
        $selectedDates = is_array($selectedDates) ? $selectedDates : [$selectedDates];

        $data = Applicant::select('ad_applicant_admission.*', 'ad_time.*')
                        ->join('ad_time', 'ad_applicant_admission.d_admission', '=', 'ad_time.date')
                        ->whereIn('ad_applicant_admission.year', $selectedYear)
                        ->whereIn('ad_applicant_admission.campus', $selectedCampus)
                        ->whereIn('ad_time.id', $selectedDates)
                        ->whereIn('p_status', [1, 2])
                        ->get();

        $totalSearchResults = count($data);

        return view('admission.reports.schedulesgen', compact('data', 'totalSearchResults', 'strand', 'time', 'venue', 'repdates'));
    }

    public function schedulesPDF_reports(Request $request)
    {
        $repdates = AdmissionDate::orderBy('date', 'desc')->groupBy('date')->pluck('date');
        $time = Time::all();
        $strand = Strands::orderBy('id', 'asc')->get();
        $venue = Venue::select('venue', DB::raw('count(*) as total'))->groupBy('venue')->get();

        $selectedYear = $request->query('year', []);
        $selectedCampus = $request->query('campus', []);
        $selectedDates = $request->query('date', []);

        $selectedYear = is_array($selectedYear) ? $selectedYear : [$selectedYear];
        $selectedCampus = is_array($selectedCampus) ? $selectedCampus : [$selectedCampus];
        $selectedDates = is_array($selectedDates) ? $selectedDates : [$selectedDates];

        $data = Applicant::select('ad_applicant_admission.*', 'ad_time.*')
                        ->join('ad_time', 'ad_applicant_admission.d_admission', '=', 'ad_time.date')
                        ->whereIn('ad_applicant_admission.year', $selectedYear)
                        ->whereIn('ad_applicant_admission.campus', $selectedCampus)
                        ->whereIn('ad_time.id', $selectedDates)
                        ->whereIn('p_status', [1, 2])
                        ->get();

        $totalSearchResults = count($data);

        $pdf = PDF::loadView('admission.reports.pdf.schedulesPDF', ['data' => $data, 'totalSearchResults' => $totalSearchResults, 'strand' => $strand, 'time' => $time, 'venue' => $venue, 'repdates' => $repdates])->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function nosched_printing()
    {
        $repdates = AdmissionDate::groupBy('date')->pluck('date');
        $time = Time::all();
        return view('admission.reports.nosched', compact('repdates', 'time'));
    }

    public function nosched_reports(Request $request)
    {
        $data = Applicant::whereNull('dateID')->whereNull('d_admission')->where('campus', '=', Auth::user()->campus);

        if ($request->year) {
            $data = $data->where('year', $request->year);
        }

        if ($request->campus) {
            $data = $data->where('campus', $request->campus);
        }

        $data = $data->get();

        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);
        
        return view('admission.reports.noschedgen', compact('totalSearchResults', 'data'));
    }

    public function noschedPDF_reports(Request $request)
    {
        try {
            $selectedYear = $request->query('year', []);
            $selectedCampus = $request->query('campus', []);

            $selectedYear = is_array($selectedYear) ? $selectedYear : [$selectedYear];
            $selectedCampus = is_array($selectedCampus) ? $selectedCampus : [$selectedCampus];

            $query = Applicant::select('ad_applicant_admission.*')
                            ->whereIn('ad_applicant_admission.year', $selectedYear)
                            ->whereIn('ad_applicant_admission.campus', $selectedCampus);

            $data = $query->whereNull('dateID')->whereNull('d_admission')->where('campus', '=', Auth::user()->campus)->orderBy('admission_id','ASC')->get();

            $totalSearchResults = count($data);

            $pdf = PDF::loadView('admission.reports.pdf.noschedPDF', ['data' => $data, 'totalSearchResults' => $totalSearchResults])->setPaper('Legal', 'landscape');
            return $pdf->stream();
        } catch (\Exception $e) {
            \Log::error('Error in applicantPDF_reports: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function examination_printing()
    {  
        $strand = Strands::orderBy('id', 'asc')->get();
        return view('admission.reports.examination', compact('strand'));
    }

    public function examination_reports(Request $request)
    {  
        $strand = Strands::orderBy('id', 'asc')->get();

        $data = Applicant::where('p_status', '=', 3);

        if ($request->year) {
            $data = $data->where('year', $request->year);
        }

        if ($request->campus) {
            $data = $data->where('campus', $request->campus);
        }

        if ($request->strand && $request->strand !== 'All') {
            $data = $data->where('strand', $request->strand);
        }

        $data = $data->get();

        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);

        return view('admission.reports.examinationgen', ['data' => $data, 'totalSearchResults' => $totalSearchResults])
            ->with('strand', $strand);
    }

    public function examinationPDF_reports(Request $request)
    {
        try {
            $selectedYear = $request->query('year', []);
            $selectedCampus = $request->query('campus', []);
            $selectedStrand = $request->query('strand', []);

            $selectedYear = is_array($selectedYear) ? $selectedYear : [$selectedYear];
            $selectedCampus = is_array($selectedCampus) ? $selectedCampus : [$selectedCampus];
            $selectedStrand = is_array($selectedStrand) ? $selectedStrand : [$selectedStrand];

            $query = Applicant::select('ad_applicant_admission.*')
                            ->whereIn('ad_applicant_admission.year', $selectedYear)
                            ->whereIn('ad_applicant_admission.campus', $selectedCampus);

            if ($selectedStrand && $selectedStrand[0] !== 'All') {
                $query->whereIn('ad_applicant_admission.strand', $selectedStrand);
            }

            $data = $query->where('p_status', '=', 3)->get();

            $totalSearchResults = count($data);

            $pdf = PDF::loadView('admission.reports.pdf.examinationPDF', ['data' => $data, 'totalSearchResults' => $totalSearchResults])->setPaper('Legal', 'landscape');
            return $pdf->stream();
        } catch (\Exception $e) {
            \Log::error('Error in applicantPDF_reports: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function qualified_printing()
    {
        $repdates = AdmissionDate::orderBy('date', 'desc')->where('campus', '=', Auth::user()->campus)->groupBy('date')->pluck('date');
        $time = Time::all();
        $strand = Strands::orderBy('id', 'asc')->get();
        return view('admission.reports.qualified', compact('repdates', 'time', 'strand'));
    }

    public function qualified_reports(Request $request)
    {
        $min = strtotime($request->input('min_date'));
        $new_min = date('Y-m-d H:i:s', $min); 

        $max = strtotime($request->input('max_date'));
        $new_max = date('Y-m-d H:i:s', $max); 
        $strand = Strands::orderBy('id', 'asc')->get();

        $data = Applicant::where('p_status', '=', 3)->get();
        if ($request->year){$data = $data->where('year',$request->year);}
        if ($request->campus){$data = $data->where('campus',$request->campus);}
        if ($request->strand){$data = $data->where('strand',$request->strand);}
        if ($request->min_date){$data = $data->whereBetween('updated_at', [$new_min , $new_max]);}
        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);
        return view('admission.reports.qualifiedgen', ['data' => $data,'totalSearchResults' => $totalSearchResults])
        ->with('strand', $strand);
    }

    public function accepted_printing()
    {
        $strand = Strands::all();
        return view('admission.reports.acceptedapp', compact('strand'));
    }

    public function accepted_reports(Request $request)
    {
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');
        $user = Auth::guard('web')->user()->dept;

        $query = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_applicant_dept_rating.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->where('ad_applicant_dept_rating.deptcol', $user)
                        ->where('p_status', '=', 6);
        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        $strand = Strands::all();

        return view('admission.reports.acceptedappgen', compact('strand', 'data'));
    }

    public function acceptedPDF_reports(Request $request)
    {
        try {
            $selectedYear = $request->query('year', []);
            $selectedCampus = $request->query('campus', []);
            $selectedStrand = $request->query('strand', []);

            $selectedYear = is_array($selectedYear) ? $selectedYear : [$selectedYear];
            $selectedCampus = is_array($selectedCampus) ? $selectedCampus : [$selectedCampus];
            $selectedStrand = is_array($selectedStrand) ? $selectedStrand : [$selectedStrand];
            $user = Auth::guard('web')->user()->dept;

            $query = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_applicant_dept_rating.*')
                        ->whereIn('ad_applicant_admission.year', $selectedYear)
                        ->whereIn('ad_applicant_admission.campus', $selectedCampus)
                        ->where('ad_applicant_dept_rating.deptcol', $user);

            if ($selectedStrand && $selectedStrand[0] !== 'All') {
                $query->whereIn('ad_applicant_admission.strand', $selectedStrand);
            }

            $data = $query->where('p_status', '=', 6)->get();

            $totalSearchResults = count($data);

            $pdf = PDF::loadView('admission.reports.pdf.acceptedPDF', ['data' => $data, 'totalSearchResults' => $totalSearchResults])->setPaper('Legal', 'landscape');
            return $pdf->stream();
        } catch (\Exception $e) {
            \Log::error('Error in applicantPDF_reports: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

}
