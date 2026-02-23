<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\UniqueStudentID;
use Illuminate\Support\Facades\Log;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\DeleteEnrollmentLogs;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

use App\Models\SettingDB\ConfigureCurrent;

class EnGradesheetLogbookController extends Controller
{
    public function logbookindex()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
        
        $colleges = College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])->orderBy('college_name', 'ASC')->get();

        return view('enrollment.reports.logbok.search_gslogbook', compact('sy', 'colleges'));
    }

    public function logbook_search(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $colleges = College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])->orderBy('college_name', 'ASC')->get();

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $collegeabbr = $request->query('collegeabbr');
        $campus = Auth::guard('web')->user()->campus;

        return view('enrollment.reports.logbok.searchlist_gslogbook', compact('sy', 'colleges'));
    }

    public function getlogbook_search(Request $request)
    {
        $schlyear   = $request->query('schlyear');
        $semester   = $request->query('semester');
        $collegeabbr = $request->query('collegeabbr');
        $campus     = Auth::guard('web')->user()->campus;

        $data = DB::select("
    SELECT
        so.id AS soid,
        so.subCode,
        s.sub_name,
        f.lname,
        f.fname,
        f.dept,
        (
            SELECT MAX(sg.updated_at)
            FROM coasv2_db_enrollment.studgrades sg
            WHERE sg.subjID = so.id
        ) AS lastupdated
    FROM sub_offered so
    LEFT JOIN scheduleclass sc ON sc.subject_id = so.id
    LEFT JOIN faculty f ON f.id = sc.faculty_id
    LEFT JOIN subjects s ON s.sub_code = so.subCode
    WHERE so.schlyear = ?
      AND so.semester = ?
      AND so.campus = ?
      AND f.dept = ?
      AND so.subCode NOT LIKE '%-GSS-%'
    ORDER BY f.lname ASC
", [$schlyear, $semester, $campus, $collegeabbr]);

        return response()->json(['data' => $data]);
    }

    public function logbookpdfprint(Request $request)
    {
        $schlyear   = $request->query('schlyear');
        $semester   = $request->query('semester');
        $collegeabbr = $request->query('collegeabbr');
        $campus     = Auth::guard('web')->user()->campus;

        $data = SubjectOffered::query()

            // FILTER FIRST (VERY IMPORTANT)
            ->where('sub_offered.schlyear', $schlyear)
            ->where('sub_offered.semester', $semester)
            ->where('sub_offered.campus', $campus)
            ->where('sub_offered.subCode', 'NOT LIKE', '%-GSS-%')

            ->leftJoin('scheduleclass', 'sub_offered.id', '=', 'scheduleclass.subject_id')
            ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
            ->leftJoin('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')

            // ✅ Replace heavy join with subquery
            ->select([
                'sub_offered.id as soid',
                'sub_offered.subCode',
                'subjects.sub_name',
                'faculty.lname',
                'faculty.fname',
                'faculty.dept',

                \DB::raw('(
                    SELECT MAX(updated_at)
                    FROM coasv2_db_enrollment.studgrades sg
                    WHERE sg.subjID = sub_offered.id
                ) as lastupdated')
            ])

            ->where('faculty.dept', $collegeabbr)
            ->orderBy('faculty.lname', 'ASC')
            ->get();
        
        $data = [
            'gslog' => $gslog,
        ];

        $pdf = PDF::loadView('enrollment.reports.logbok.logbookpdf', $data)->setPaper('legal', 'landscape');
        return $pdf->stream();
    }
}
