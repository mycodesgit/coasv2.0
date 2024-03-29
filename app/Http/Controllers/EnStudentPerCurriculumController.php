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

class EnStudentPerCurriculumController extends Controller
{
    public function studCurr()
    {
        return view('enrollment.reports.studentcurr.list_studcurr');
    }

    public function studCurrsearch(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('web')->user()->campus;

        $data = StudEnrolmentHistory::join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->where('program_en_history.schlyear', '=', $schlyear)
                ->where('program_en_history.semester', '=', $semester)
                ->where('program_en_history.campus', '=', $campus)
                ->groupBy('program_en_history.progCod', 'program_en_history.studYear', 'program_en_history.studSec')
                ->select('coasv2_db_schedule.programs.*')
                ->selectRaw('program_en_history.progCod,
                            program_en_history.studYear, 
                            program_en_history.studSec, 
                            COUNT(DISTINCT students.stud_id) as studentCount,
                            COUNT(DISTINCT CASE WHEN students.gender = "Male" THEN students.stud_id END) as maleCount,
                            COUNT(DISTINCT CASE WHEN students.gender = "Female" THEN students.stud_id END) as femaleCount')
                ->get();

    return view('enrollment.reports.studentcurr.listsearch_studcurr', compact('data'));
    }

    public function getstudCurrSearch(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('web')->user()->campus;

        $data = StudEnrolmentHistory::join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->where('program_en_history.schlyear', '=', $schlyear)
                ->where('program_en_history.semester', '=', $semester)
                ->where('program_en_history.campus', '=', $campus)
                ->groupBy('program_en_history.progCod', 'program_en_history.studYear', 'program_en_history.studSec')
                ->select('coasv2_db_schedule.programs.*')
                ->selectRaw('program_en_history.progCod,
                            program_en_history.studYear, 
                            program_en_history.studSec, 
                            COUNT(DISTINCT students.stud_id) as studentCount,
                            COUNT(DISTINCT CASE WHEN students.gender = "Male" THEN students.stud_id END) as maleCount,
                            COUNT(DISTINCT CASE WHEN students.gender = "Female" THEN students.stud_id END) as femaleCount')
                ->get();

        return response()->json(['data' => $data]);
    }
}
