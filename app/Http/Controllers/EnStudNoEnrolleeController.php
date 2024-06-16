<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\SettingDB\ConfigureCurrent;

class EnStudNoEnrolleeController extends Controller
{
    public function studnoenrollee() 
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.numenrollee.list_numenrollee', compact('sy'));
    }

    public function studnoenrollee_searchList(Request $request) 
    {

        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = Auth::guard('web')->user()->campus;

        $numstudproghisTotal = StudEnrolmentHistory::where('schlyear', '=', $schlyear)
                            ->where('schlyear', '=', $schlyear)
                            ->where('semester', '=', $semester)
                            ->where('campus', '=', $campus)
                            ->where('studentID', 'NOT LIKE', '%-G%')
                            ->count();

        $numstudproghisfirst = StudEnrolmentHistory::where('schlyear', '=', $schlyear)
                            ->where('schlyear', '=', $schlyear)
                            ->where('semester', '=', $semester)
                            ->where('campus', '=', $campus)
                            ->where('studentID', 'NOT LIKE', '%-G%')
                            ->where('studYear', '=', 1)
                            ->count();

        $numstudproghissecond = StudEnrolmentHistory::where('schlyear', '=', $schlyear)
                            ->where('schlyear', '=', $schlyear)
                            ->where('semester', '=', $semester)
                            ->where('campus', '=', $campus)
                            ->where('studentID', 'NOT LIKE', '%-G%')
                            ->where('studYear', '=', 2)
                            ->count();

        $numstudproghisthird = StudEnrolmentHistory::where('schlyear', '=', $schlyear)
                            ->where('schlyear', '=', $schlyear)
                            ->where('semester', '=', $semester)
                            ->where('campus', '=', $campus)
                            ->where('studentID', 'NOT LIKE', '%-G%')
                            ->where('studYear', '=', 3)
                            ->count();

        $numstudproghistfourth = StudEnrolmentHistory::where('schlyear', '=', $schlyear)
                            ->where('schlyear', '=', $schlyear)
                            ->where('semester', '=', $semester)
                            ->where('campus', '=', $campus)
                            ->where('studentID', 'NOT LIKE', '%-G%')
                            ->where('studYear', '=', 4)
                            ->count();

        //return view('enrollment.reports.numenrollee.list_numenrollee', compact('sy', 'numstudproghis'));
        return response()->json([
                                'numstudproghisTotal' => $numstudproghisTotal,
                                'numstudproghisfirst' => $numstudproghisfirst,
                                'numstudproghissecond' => $numstudproghissecond,
                                'numstudproghisthird' => $numstudproghisthird,
                                'numstudproghistfourth' => $numstudproghistfourth,
                            ]);
    }
}
