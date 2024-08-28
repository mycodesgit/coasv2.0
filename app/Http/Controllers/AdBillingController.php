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

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\EnPrograms;

use App\Models\AssessmentDB\AccountAppraisal;
use App\Models\AssessmentDB\StudentAppraisal;

use App\Models\SettingDB\ConfigureCurrent;

class AdBillingController extends Controller
{
    public function adbillingRead()
    {

        return view('admission.reports.billing');
    }

    public function adbillingRead_search(Request $request)
    {

        $year = $request->query('year');

        $admsnstud = Applicant::join('coasv2_db_enrollment.students', 'ad_applicant_admission.id', '=', 'coasv2_db_enrollment.students.app_id')
                      ->join('coasv2_db_enrollment.program_en_history as en_history1', 'coasv2_db_enrollment.students.stud_id', '=', 'en_history1.studentID')
                      ->join('coasv2_db_schedule.programs', 'en_history1.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                      ->select('ad_applicant_admission.lname', 'ad_applicant_admission.fname', 'ad_applicant_admission.mname', 'ad_applicant_admission.gender', 'ad_applicant_admission.bday', 'ad_applicant_admission.contact', 'coasv2_db_schedule.programs.progAcronym')
                      ->where('ad_applicant_admission.year', $year)
                      ->whereIn('ad_applicant_admission.p_status', ['3', '4', '5', '6'])
                      ->get();



        return view('admission.reports.billing_search', compact('admsnstud'));
    }
}
