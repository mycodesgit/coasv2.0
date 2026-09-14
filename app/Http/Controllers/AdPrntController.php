<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

use Carbon\Carbon;
use Storage;
use PDF;

use ZipArchive;

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

use App\Models\SettingDB\SignatoriesEmp;

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
        return view('admission.examinee.printPreEnrolmentView1')->with('examinee', $examinee);
    }

    public function genPreEnrolment(Request $request, $id)
    {
        $decryptedId = Crypt::decryptString($id);
        $examinee = Applicant::findOrFail($decryptedId); 

        // view()->share('examinee',$examinee);
        $rawScoreValue = $examinee->result->raw_score;

        $stanine = '';
        $remarks = '';

        if ($rawScoreValue >= 1 && $rawScoreValue <= 12) {
            $stanine = 1;
            $remarks = 'Failed';
        } else if ($rawScoreValue <= 18) {
            $stanine = 2;
            $remarks = 'Failed';
        } else if ($rawScoreValue <= 23) {
            $stanine = 3;
            $remarks = 'Passed';
        } else if ($rawScoreValue <= 29) {
            $stanine = 4;
            $remarks = 'Passed';
        } else if ($rawScoreValue <= 36) {
            $stanine = 5;
            $remarks = 'Passed';
        } else if ($rawScoreValue <= 43) {
            $stanine = 6;
            $remarks = 'Passed';
        } else if ($rawScoreValue <= 49) {
            $stanine = 7;
            $remarks = 'Passed';
        } else if ($rawScoreValue <= 56) {
            $stanine = 8;
            $remarks = 'Passed';
        } else if ($rawScoreValue <= 72) {
            $stanine = 9;
            $remarks = 'Passed';
        }
        
        $counselor = SignatoriesEmp::where('position', 'Guidance Counselor')
                            ->where('schlyear', '=', '2026-2027')
                            ->where('semester', '=', '1')
                            ->where('campus', Auth::guard('web')->user()->campus)
                            ->first();

        $pdf = PDF::loadView('admission.examinee.genPreEnrolment', compact('examinee', 'stanine', 'remarks', 'counselor'))->setPaper('Legal', 'portrait');
        return $pdf->stream();
        //return view('admission.examinee.printPreEnrolmentView')->with('examinee', $examinee);
    }

    public function applicant_printing()
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $currentYear = Year::where('status', 'On')->value('adyear');

        $repdates = AdmissionDate::groupBy('date')->pluck('date');
        $time = Time::whereYear('date', $currentYear)->get();
        $strand = Strands::orderBy('id', 'asc')->get();

        return view('admission.reports.applicants', compact('repdates', 'time', 'curryear', 'strand'));
    }

    public function applicant_reports(Request $request)
    {
        $selectedYear = $request->query('year');
        $selectedCampus = $request->query('campus');
        $selectedStrand = $request->query('strand');

        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $strand = Strands::orderBy('id', 'asc')->get();

        $data = Applicant::where('p_status', '!=', 7)
                        ->where('year', $selectedYear)
                        ->where('campus', $selectedCampus)
                        ->where('strand', $selectedStrand)
                        ->get();

        return view('admission.reports.applicantsgen', compact('selectedYear', 'curryear', 'strand', 'data'));
    }

    public function getapplicantreportsRead(Request $request) 
    {
        $selectedYear = $request->query('year');
        $selectedCampus = $request->query('campus');
        $selectedStrand = $request->query('strand');

        $data = Applicant::where('p_status', '!=', 7)
                        ->where('year', $selectedYear)
                        ->where('campus', $selectedCampus)
                        ->where('strand', $selectedStrand)
                        ->get();

        return response()->json(['data' => $data]);
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

            $data = $query->where('p_status', '!=', 7)->get();

            $totalSearchResults = count($data);

            $pdf = PDF::loadView('admission.reports.pdf.applicantPDF', ['data' => $data, 'totalSearchResults' => $totalSearchResults])->setPaper('Legal', 'landscape');
            return $pdf->stream();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function viewAdslip($id)
    {
        $applicant = Applicant::findOrFail($id);

        return view('admission.reports.partials.viewadslip', compact('applicant'));
    }
    
    public function applicantadslipPDF_reports(Request $request, $id)
    {
        try {
            $applicant = Applicant::findOrFail($id);

            $pdf = PDF::loadView('admission.reports.pdf.admissionslipPDF', compact('applicant'))
                    ->setPaper('Legal', 'portrait');
            
            // Clean name components to ensure valid filenames
            $lastName = preg_replace('/[^A-Za-z0-9_\- ]/', '', $applicant->lname);
            $firstName = preg_replace('/[^A-Za-z0-9_\- ]/', '', $applicant->fname);

            // Format: LASTNAME, FIRSTNAME.pdf
            $fileName = strtoupper($lastName . ', ' . $firstName) . '.pdf';

            return $pdf->stream('admission_slip_' . $applicant->id . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error: ' . $e->getMessage()], 500);
        }
    }

    public function bulkDownloadAdSlipPDF(Request $request) 
    {
        // Set higher limits for batch processing
        ini_set('memory_limit', '512M');
        set_time_limit(300); // 5 minutes max

        $selectedYear   = $request->query('year');
        $selectedCampus = $request->query('campus');
        $selectedStrand = $request->query('strand');
        
        $offset = (int) $request->query('offset', 0);
        $limit  = (int) $request->query('limit', 100); 
        // Query with explicit ordering to avoid duplicates across chunks
        $applicants = Applicant::where('p_status', '!=', 7)
            ->when($selectedYear, function ($q) use ($selectedYear) {
                return $q->where('year', $selectedYear);
            })
            ->when($selectedCampus, function ($q) use ($selectedCampus) {
                return $q->where('campus', $selectedCampus);
            })
            ->when($selectedStrand, function ($q) use ($selectedStrand) {
                return $q->where('strand', $selectedStrand);
            })
            ->orderBy('id', 'asc') // CRITICAL: Ensures consistent pagination without missing/duplicating records
            ->offset($offset)
            ->limit($limit)
            ->get();

        if ($applicants->isEmpty()) {
            return back()->with('error', 'No records found for this chunk batch.');
        }

        $zip = new ZipArchive();
        $chunkStart = $offset + 1;
        $chunkEnd   = $offset + $applicants->count();
        $zipFileName = "Applicant_Slips_{$chunkStart}_to_{$chunkEnd}.zip";
        $zipPath = storage_path('app/public/' . $zipFileName);

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            $usedFileNames = [];

            foreach ($applicants as $applicant) {
                $pdf = PDF::loadView('admission.reports.pdf.admissionslipPDF', compact('applicant'))
                    ->setPaper('Legal', 'portrait');

                $lastName  = preg_replace('/[^A-Za-z0-9_\- ]/', '', $applicant->lname);
                $firstName = preg_replace('/[^A-Za-z0-9_\- ]/', '', $applicant->fname);
                $baseFileName = strtoupper(trim($lastName . ', ' . $firstName));

                // Prevent filename collisions within the same ZIP file
                $fileName = $baseFileName . '.pdf';
                $counter = 1;
                while (in_array($fileName, $usedFileNames)) {
                    $fileName = $baseFileName . " ({$counter}) [ID-{$applicant->id}].pdf";
                    $counter++;
                }
                $usedFileNames[] = $fileName;

                $zip->addFromString($fileName, $pdf->output());
                
                // Clear memory per loop iteration
                unset($pdf);
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function applicantperschool_printing()
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $currentYear = Year::where('status', 'On')->value('adyear');

        return view('admission.reports.applicantschool', compact('curryear'));
    }

    public function applicantperschool_reports(Request $request)
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();

        $year = $request->query('year');
        $campus = $request->query('campus');

        $appschool = Applicant::where('year', $year)->where('campus', $campus)->where('p_status', '!=', 7)->get();

        return view('admission.reports.applicantschoolgen', compact('curryear', 'appschool'));
    }

    public function getapplicantperschool_reports(Request $request)
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();

        $year = $request->query('year');
        $campus = $request->query('campus');

        $data = Applicant::where('year', $year)->where('campus', $campus)->where('p_status', '!=', 7)->get();

        return response()->json(['data' => $data]);
    }

    public function schedules_printing()
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $currentYear = Year::where('status', 'On')->value('adyear');

        $time = Time::whereYear('date', $currentYear)->where('campus', Auth::guard('web')->user()->campus)->get();
        
        return view('admission.reports.schedules', compact('time', 'curryear'));
    }


    public function schedules_reports(Request $request)
    {
        $repdates = AdmissionDate::orderBy('date', 'desc')->groupBy('date')->pluck('date');
        $time = Time::all();
        $strand = Strands::orderBy('id', 'asc')->get();
        $venue = Venue::select('venue', DB::raw('count(*) as total'))->groupBy('venue')->get();

        $selectedYear = $request->query('year');
        $selectedCampus = $request->query('campus');
        $selectedDates = $request->query('date');

        $data = Applicant::leftJoin('ad_time', 'ad_applicant_admission.dateID', '=', 'ad_time.id')
                        ->where('ad_applicant_admission.year', $selectedYear)
                        ->where('ad_applicant_admission.campus', $selectedCampus)
                        ->where('ad_time.id', $selectedDates)
                        ->select('ad_applicant_admission.*', 'ad_time.*')
                        ->get();

        $totalSearchResults = count($data);

        return view('admission.reports.schedulesgen', compact('data', 'totalSearchResults', 'strand', 'time', 'venue', 'repdates'));
    }

    public function getschedulesreportsRead(Request $request) 
    {
        $selectedYear = $request->query('year');
        $selectedCampus = $request->query('campus');
        $selectedDates = $request->query('date');

        $data = Applicant::leftJoin('ad_time', 'ad_applicant_admission.dateID', '=', 'ad_time.id')
                        ->where('ad_applicant_admission.year', $selectedYear)
                        ->where('ad_applicant_admission.campus', $selectedCampus)
                        ->where('ad_time.id', $selectedDates)
                        ->where('ad_applicant_admission.p_status', '!=', 7)
                        ->where('ad_applicant_admission.venue', '!=', NULL)
                        ->select('ad_applicant_admission.*', 'ad_time.*')
                        ->get();

        return response()->json(['data' => $data]);
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

        $data = Applicant::leftJoin('ad_time', 'ad_applicant_admission.dateID', '=', 'ad_time.id')
                        ->where('ad_applicant_admission.year', $selectedYear)
                        ->where('ad_applicant_admission.campus', $selectedCampus)
                        ->where('ad_time.id', $selectedDates)
                        ->where('ad_applicant_admission.p_status', '!=', 7)
                        ->where('ad_applicant_admission.venue', '!=', NULL)
                        ->select('ad_applicant_admission.*', 'ad_time.*')
                        ->get();

        $totalSearchResults = count($data);

        $pdf = PDF::loadView('admission.reports.pdf.schedulesPDF', ['data' => $data, 'totalSearchResults' => $totalSearchResults, 'strand' => $strand, 'time' => $time, 'venue' => $venue, 'repdates' => $repdates])->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function nosched_printing()
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();

        return view('admission.reports.nosched', compact('curryear'));
    }

    public function nosched_reports(Request $request)
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        
        return view('admission.reports.noschedgen', compact('curryear'));
    }

    public function getnoschedulesreportsRead(Request $request) 
    {
        $selectedYear = $request->query('year');
        $selectedCampus = $request->query('campus');

        $data = Applicant::whereNull('venue')
                ->whereNull('d_admission')
                ->where('p_status', '!=', 7)
                ->where('campus', $selectedCampus)
                ->where('year', $selectedYear)
                ->get();

        return response()->json(['data' => $data]);
    }

    public function noschedPDF_reports(Request $request)
    {
        try {
            $selectedYear = $request->query('year');
            $selectedCampus = $request->query('campus');

            $data = Applicant::whereNull('venue')
                    ->whereNull('d_admission')
                    ->where('p_status', '!=', 7)
                    ->where('campus', $selectedCampus)
                    ->where('year', $selectedYear)
                    ->get();

            $pdf = PDF::loadView('admission.reports.pdf.noschedPDF', ['data' => $data])->setPaper('Legal', 'landscape');
            return $pdf->stream();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function examination_printing()
    {  
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $strand = Strands::orderBy('id', 'asc')->get();
        return view('admission.reports.examination', compact('strand', 'curryear'));
    }

    public function examination_reports(Request $request)
    {  
        $curryear = Year::orderBy('adyear', 'DESC')->get();
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

        return view('admission.reports.examinationgen', compact('strand', 'curryear', 'data', 'totalSearchResults'));
    }

    public function getexaminationreportsRead(Request $request) 
    {
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');

        $query = Applicant::leftJoin('ad_examinee_result', 'ad_applicant_admission.id', '=', 'ad_examinee_result.app_id')
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

    public function examinationPDF_reports(Request $request)
    {
        try {
            $year = $request->query('year');
            $campus = $request->query('campus');
            $strand = $request->query('strand');

            $query = Applicant::leftJoin('ad_examinee_result', 'ad_applicant_admission.id', '=', 'ad_examinee_result.app_id')
                            ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_examinee_result.*')
                            ->where('ad_applicant_admission.year', $year)
                            ->where('ad_applicant_admission.campus', $campus)
                            ->where('p_status', '=', 3);

            if ($strand && $strand !== 'All') {
                $query->where('ad_applicant_admission.strand', $strand);
            }

            $data = $query->get();

            $totalSearchResults = count($data);

            $pdf = PDF::loadView('admission.reports.pdf.examinationPDF', ['data' => $data, 'totalSearchResults' => $totalSearchResults])->setPaper('Legal', 'portrait');
            return $pdf->stream();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function qualified_printing()
    {
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $currentYear = Year::where('status', 'On')->value('adyear');
        $strand = Strands::orderBy('id', 'asc')->get();

        $repdates = AdmissionDate::orderBy('date', 'desc')->where('campus', '=', Auth::user()->campus)->groupBy('date')->pluck('date');
        $time = Time::whereYear('date', $currentYear)->get();
        $strand = Strands::orderBy('id', 'asc')->get();
        return view('admission.reports.qualified', compact('repdates', 'time', 'strand', 'curryear'));
    }

    public function qualified_reports(Request $request)
    {
        $currentYear = Year::where('status', 'On')->value('adyear');
        $curryear = Year::orderBy('adyear', 'DESC')->get();
        $time = Time::whereYear('date', $currentYear)->get();
        
        return view('admission.reports.qualifiedgen', compact('curryear', 'time'));
    }

    public function getqualifiedreportsRead(Request $request) 
    {
        $year = $request->query('year');
        $campus = $request->query('campus');
        $date = $request->query('date');

        $query = Applicant::leftJoin('ad_examinee_result', 'ad_applicant_admission.id', '=', 'ad_examinee_result.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_examinee_result.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->where('ad_applicant_admission.dateID', $date)
                        ->where('p_status', '=', 4);

        $data = $query->get();
        
        return response()->json(['data' => $data]);
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
