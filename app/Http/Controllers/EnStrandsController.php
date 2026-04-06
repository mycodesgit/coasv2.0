<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\UniqueStudentID;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\AdmissionDB\Applicant;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\KioskUser;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\SettingDB\ConfigureCurrent;

class EnStrandsController extends Controller
{
    public function index()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.strands.list', compact('sy'));
    }

    public function show()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.strands.listsearch', compact('sy'));
    }

    public function getsearchstudstrandsRead(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');
        $campusArray = array_map('trim', explode(',', $campus));
    
        $data = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->leftJoin('coasv2_db_admission.ad_applicant_admission', 'students.app_id', '=', 'coasv2_db_admission.ad_applicant_admission.id')
                ->join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->select(
                    'students.lname', 
                    'students.fname', 
                    'students.mname', 
                    'students.ext', 
                    'students.address', 
                    'students.bday', 
                    'students.brgy', 
                    'students.city', 
                    'students.province', 
                    'students.region',
                    'students.zcode',
                    'program_en_history.progCod', 
                    'program_en_history.studentID', 
                    'program_en_history.studYear', 
                    'program_en_history.studSec', 
                    'program_en_history.schlyear', 
                    'program_en_history.semester', 
                    'coasv2_db_schedule.programs.progAcronym', 
                    'coasv2_db_schedule.programs.progName', 
                    'students.lstsch_attended', 
                    'students.suc_lst_attended',
                    'coasv2_db_admission.ad_applicant_admission.strand'
                )
                // ->where('program_en_history.campus', '=', $campus)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('program_en_history.campus', 'LIKE', "%$campus%");
                    }
                })
                ->where('students.stud_id', 'NOT LIKE', '%-G')
                ->where('program_en_history.schlyear', '=', $schlyear)
                ->where('program_en_history.semester', '=', $semester)
                ->get();

        return response()->json(['data' => $data]);
    }
}
