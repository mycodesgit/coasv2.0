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

class GradingFacultyAdmissionAcceptedController extends Controller
{
    public function index()
    {
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        $curryear = Year::orderBy('adyear', 'DESC')->get();
        
        $strand = Strands::all();

        return view('grading.gradesheet.faculty.admssion.accepted', compact('authfacdesig', 'curryear', 'strand'));
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

        return view('grading.gradesheet.faculty.admssion.acceptedresult', compact('authfacdesig', 'curryear', 'strand', 'program'));
    }

    public function show(Request $request)
    {   
        $year = $request->query('year');
        $campus = Auth::guard('faculty')->user()->campus;
        $strand = $request->query('strand');
        $user = Auth::guard('faculty')->user()->faccollege;

        $query = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                ->leftJoin('coasv2_db_enrollment.students', 'ad_applicant_admission.id', '=', 'coasv2_db_enrollment.students.app_id')
                ->select(
                    'ad_applicant_admission.*', 
                    'ad_applicant_admission.id as adid', 
                    'ad_applicant_admission.strand as appstrand', 
                    'ad_applicant_dept_rating.*', 
                    'coasv2_db_enrollment.students.stud_id'
                )
                ->where('ad_applicant_admission.year', $year)
                ->where('ad_applicant_admission.campus', $campus)
                //->whereYear('coasv2_db_enrollment.students.created_at', now()->year)
                ->where('ad_applicant_dept_rating.deptcol', $user)
                ->whereIn('ad_applicant_admission.p_status', [5, 6]);

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
}
