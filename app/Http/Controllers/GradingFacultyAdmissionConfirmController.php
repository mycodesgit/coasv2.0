<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\Year;

use App\Models\EnrollmentDB\Student;

use App\Models\SettingDB\ConfigureCurrent;

class GradingFacultyAdmissionConfirmController extends Controller
{
    public function index()
    {
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        $curryear = Year::orderBy('adyear', 'DESC')->get();
        
        $strand = Strands::all();

        return view('grading.gradesheet.faculty.admssion.confirm', compact('authfacdesig', 'curryear', 'strand'));
    }

    public function store(Request $request)
    {
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        $curryear = Year::orderBy('adyear', 'DESC')->get();

        $strand = Strands::all();

        $preference_1 = $applicant->preference_1 ?? null;
        $preference_2 = $applicant->preference_2 ?? null;

        $program = Programs::where('code', $preference_1)
            ->orWhere('code', $preference_2)
            ->orderBy('id', 'asc')
            ->get();

        return view('grading.gradesheet.faculty.admssion.confirmresult', compact('authfacdesig', 'curryear', 'strand', 'program'));
    }

    public function show(Request $request)
    {
        $year = $request->query('year');
        $strand = $request->query('strand');
        $campus = Auth::guard('faculty')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $query  = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_applicant_dept_rating.*')
                        ->where('ad_applicant_admission.year', $year)
                        // ->where('ad_applicant_admission.campus', $campus)
                        ->where(function ($q) use ($campusArray) {
                            foreach ($campusArray as $campus) {
                                $q->orWhere('ad_applicant_admission.campus', 'LIKE', "%$campus%");
                            }
                        })
                        ->where('p_status', '=', 4);

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

    public function getFacCampPrograms(Request $request)
    {
        $campus = $request->input('campus');
        $programs = Programs::where('campus', 'like', '%' . $campus . '%')->get();

        return response()->json($programs);
    }

    public function savefacapplicantmod_rating(Request $request) 
    {   
        $request->validate([
            'id' => 'required',
            'rating' => 'required|numeric',
            'remarks' => 'required',
            'course' => 'required',
        ]);

        try {
            $decryptedId = Crypt::decryptString($request->input('id'));
            $appresult = DeptRating::where('app_id', $decryptedId)->first();
            $appresult->update([
                'interviewer' => Auth::guard('faculty')->user()->fname . ' ' .Auth::guard('faculty')->user()->lname,
                'rating' => $request->input('rating'),
                'remarks' => $request->input('remarks'),
                'course' => $request->input('course'),
                'reason' => $request->input('reason'),
            ]);
            return response()->json(['success' => true, 'message' => 'Applicant Interview Result has been saved'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to assign result!'], 404);
        }
    }

    public function examineefacpushAcceptajax(Request $request) 
    {
        $decryptedId = Crypt::decryptString($request->input('id'));
        
        $applicantsWithoutResult = Applicant::leftJoin('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
            ->where('ad_applicant_admission.p_status', 2)
            ->where('ad_applicant_admission.id', $decryptedId)
            ->where(function ($query) {
                $query->whereNull('ad_applicant_dept_rating.rating')
                    ->whereNull('ad_applicant_dept_rating.remarks');
            })
            ->exists();

        if ($applicantsWithoutResult) {
            return response()->json(['error' => true, 'message' => 'Please assign result before pushing to Accepted Applicant list.'], 422);
        }
        
        $affectedRows = Applicant::where('p_status', 4)
            ->where('id', $decryptedId)
            ->update(['p_status' => 5]);

        $affectedRowsDeptRating = DeptRating::where('app_id', $decryptedId)
            ->update([
                'deptcol' => Auth::guard('faculty')->user()->faccollege,
                'interviewerid' => Auth::guard('faculty')->user()->id,
            ]);

        if ($affectedRows > 0) {
            return response()->json(['success' => true, 'message' => 'Examinee has been pushed to Accepted Applicants List'], 200);
        } else {
            return response()->json(['error' => true, 'message' => 'No applicant found with the provided ID or the applicant already has a result assigned.'], 422);
        }
    }
}
