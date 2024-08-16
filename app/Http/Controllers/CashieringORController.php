<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\AssessmentDB\Funds;
use App\Models\AssessmentDB\AccountCoa;
use App\Models\AssessmentDB\AccountAppraisal;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\SettingDB\ConfigureCurrent;

class CashieringORController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->year;
        $userCampus = Auth::guard('web')->user()->campus;

        $schlyearactive = ConfigureCurrent::where('set_status', 2)->first()->schlyear;
        $schlyearactiveYear = explode('-', $schlyearactive)[0];
        $semesteractive = ConfigureCurrent::where('set_status', 2)->first()->semester;

        $collegesFirstSemester = College::join('coasv2_db_enrollment.program_en_history', function($join) {
                            $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
                        })
                        ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
                        ->where(function ($query) use ($userCampus) {
                            $campuses = explode(', ', $userCampus);
                            foreach ($campuses as $campus) {
                                $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                            }
                        })
                        ->where('coasv2_db_enrollment.program_en_history.semester', '=', 1)
                        ->where(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.schlyear, '-', 1)"), $schlyearactiveYear)
                        ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
                        ->orderBy('college_name', 'ASC')
                        ->select('college.*', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
                        ->groupBy('college.id')
                        ->get();

        $collegesSecondSemester = College::join('coasv2_db_enrollment.program_en_history', function($join) {
                            $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
                        })
                        ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
                        ->where(function ($query) use ($userCampus) {
                            $campuses = explode(', ', $userCampus);
                            foreach ($campuses as $campus) {
                                $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                            }
                        })
                        ->where('coasv2_db_enrollment.program_en_history.semester', '=', 2)
                        ->where(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.schlyear, '-', 1)"), $schlyearactiveYear)
                        ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
                        ->orderBy('college_name', 'ASC')
                        ->select('college.*', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
                        ->groupBy('college.id')
                        ->get();

        return view('cashier.index', compact('collegesFirstSemester', 'collegesSecondSemester', 'currentYear', 'previousYear'));
    }

    public function list_orRead()
    {
        return view('cashier.officialreceipt.list_or');
    }
}
