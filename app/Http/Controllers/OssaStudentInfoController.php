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
use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentInfoGrad;
use App\Models\EnrollmentDB\StudentCvlStatus;
use App\Models\EnrollmentDB\StudentGnderStatus;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\ApplicantDocs;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\Region;
use App\Models\SettingDB\Province;
use App\Models\SettingDB\City;
use App\Models\SettingDB\Barangay;

class OssaStudentInfoController extends Controller
{
    public function index() 
    {
        $civilStatuses = StudentCvlStatus::all();
        $genderStatuses = StudentGnderStatus::all();
        $regions = Region::all();

        return view('ossas.studinfo.viewstudinfo', compact('civilStatuses', 'genderStatuses', 'regions'));
    }
}
