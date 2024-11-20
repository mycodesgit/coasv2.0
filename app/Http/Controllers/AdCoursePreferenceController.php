<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\Strands;

use App\Models\EnrollmentDB\Student;

use App\Models\SettingDB\ConfigureCurrent;

class AdCoursePreferenceController extends Controller
{
    public function indexcoursepref()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $strand = Strands::all();
        return view('admission.acceptedapp.studcoursepref', compact('strand', 'sy'));
    }

    public function indexcoursepref_search(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $strand = Strands::all();
        return view('admission.acceptedapp.studcoursepref_searchlist', compact('strand', 'sy'));
    }

    public function getindexcourseprefAll(Request $request)
    {   
        
        $year = $request->query('schlyear');
        $campus = $request->query('campus');
        $strand = $request->query('strand');
        $user = Auth::guard('web')->user()->dept;

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
                ->whereIn('ad_applicant_admission.p_status', [5, 6]);

        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }
}
