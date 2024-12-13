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

        return view('enrollment.reports.logbok.search_gslogbook', compact('sy'));
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

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        return view('enrollment.reports.logbok.searchlist_gslogbook', compact('sy'));
    }

    public function getlogbook_search(Request $request)
    {

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
    
        $data = SubjectOffered::leftJoin('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('scheduleclass', 'sub_offered.id', '=', 'scheduleclass.subject_id')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->select('sub_offered.*', 'subjects.*', 'sub_offered.id as soid', 'faculty.lname', 'faculty.fname', 'faculty.dept')
                        ->where('sub_offered.schlyear', $schlyear)
                        ->where('sub_offered.semester', $semester)
                        ->where('sub_offered.campus', $campus)
                        ->where('sub_offered.subCode', 'NOT LIKE', '%-GSS-%')
                        ->orderBy('faculty.lname', 'ASC')
                        ->groupBy('sub_offered.id')
                        ->get();

        return response()->json(['data' => $data]);
    }
}
