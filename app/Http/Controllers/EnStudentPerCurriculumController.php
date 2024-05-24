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

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\SettingDB\ConfigureCurrent;

class EnStudentPerCurriculumController extends Controller
{
    public function studCurr()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.studentcurr.list_studcurr', compact('sy'));
    }

    public function studCurrsearch(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
            
        return view('enrollment.reports.studentcurr.listsearch_studcurr', compact('sy'));
    }

    public function getstudCurrSearch(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('web')->user()->campus;

        $data = StudEnrolmentHistory::leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', $semester)
                ->where('program_en_history.campus', $campus)
                ->groupBy('program_en_history.progCod', 'program_en_history.studYear', 'program_en_history.studSec')
                ->select('coasv2_db_schedule.programs.progCod', 'coasv2_db_schedule.programs.progName', 'coasv2_db_schedule.programs.progAcronym', 'program_en_history.studYear', 'program_en_history.studYear', 'program_en_history.studSec', 'students.gender', 'program_en_history.id', 'program_en_history.schlyear', 'program_en_history.semester')
                ->selectRaw('program_en_history.progCod,
                            program_en_history.studYear, 
                            program_en_history.studSec, 
                            COUNT(DISTINCT students.stud_id) as studentCount,
                            COUNT(DISTINCT CASE WHEN students.gender = "Male" THEN students.stud_id END) as maleCount,
                            COUNT(DISTINCT CASE WHEN students.gender = "Female" THEN students.stud_id END) as femaleCount')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function fetchStudEnrollmentlist(Request $request)
    {
        $progCode = $request->input('progCod');
        $studYear = $request->input('studYear');
        $studSec = $request->input('studSec');
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');

        $enrolledstud = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('program_en_history.progCod', $progCode)
            ->where('program_en_history.studYear', $studYear)
            ->where('program_en_history.studSec', $studSec)
            ->where('program_en_history.schlyear', $schlyear)
            ->where('program_en_history.semester', $semester)
            ->select('program_en_history.*', 'students.*')
            ->select('program_en_history.*', 'students.*', 'coasv2_db_schedule.programs.progAcronym')
            ->orderBy('students.lname', 'ASC')
            ->get();

        return response()->json(['data' => $enrolledstud]);
    }

    public function exportEnrollmentPDF(Request $request)
    {
        $progCode = $request->input('progCod');
        $studYear = $request->input('studYear');
        $studSec = $request->input('studSec');
        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');

        $enrolledstud = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('program_en_history.progCod', $progCode)
            ->where('program_en_history.studYear', $studYear)
            ->where('program_en_history.studSec', $studSec)
            ->where('program_en_history.schlyear', $schlyear)
            ->where('program_en_history.semester', $semester)
            ->select('program_en_history.*', 'students.*', 'coasv2_db_schedule.programs.progAcronym', 'coasv2_db_schedule.programs.progName')
            ->orderBy('students.lname', 'ASC')
            ->get();

        $pdf = PDF::loadView('enrollment.reports.studentcurr.studcoursepdf', compact('enrolledstud'))->setPaper('Legal', 'portrait');

        return $pdf->stream('enrollment_history.pdf');
    }
}
