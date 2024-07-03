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
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

use App\Models\SettingDB\ConfigureCurrent;

class EnStudentPerSubjectController extends Controller
{
    public function studsubjectsRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.studentsub.list_studsub', compact('sy'));
    }

    public function listsearch_studsubjectsRead(Request $request)
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

        $substudnow = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                ->where('sub_offered.schlyear', $schlyear)
                ->where('sub_offered.semester', $semester)
                ->where('sub_offered.campus', $campus)
                ->select('subjects.sub_name', 'subjects.sub_title', 'sub_offered.*',  'sub_offered.id as sid')
                ->get();

        return view('enrollment.reports.studentsub.listsearch_studsub', compact('sy', 'substudnow'));
    }

    public function listsearchview_studsubjectsRead(Request $request)
    {
        $id = $request->id;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('web')->user()->campus;


        $substudnowview = Grade::select('so.*', 'studgrades.*', 'studgrades.id as sgid', 'studgrades.status as gstat', 'students.*', 's.*')
                ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
                ->join('students', 'studgrades.studID', '=', 'students.stud_id')
                ->leftJoin('coasv2_db_schedule.sub_offered as so2', 'studgrades.subjID', '=', 'so2.id')
                ->leftJoin('coasv2_db_schedule.subjects as s', 'so2.subCode', '=', 's.sub_code')
                ->where('so.schlyear', $schlyear)
                ->where('so.semester', $semester)
                ->where('studgrades.subjID', $id)
                ->orderBy('students.lname', 'ASC')
                ->get();

        return view('enrollment.reports.studentsub.listsearchview_studsub', compact('substudnowview'));
    }

    public function studsubjectsReadPDF(Request $request)
    {
        $id = $request->id;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('web')->user()->campus;


        $substudnowviewpdf = Grade::select('so.*', 'studgrades.*', 'studgrades.id as sgid', 'studgrades.status as gstat', 'students.*', 's.*')
                ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
                ->join('students', 'studgrades.studID', '=', 'students.stud_id')
                ->leftJoin('coasv2_db_schedule.sub_offered as so2', 'studgrades.subjID', '=', 'so2.id')
                ->leftJoin('coasv2_db_schedule.subjects as s', 'so2.subCode', '=', 's.sub_code')
                ->where('so.schlyear', $schlyear)
                ->where('so.semester', $semester)
                ->where('studgrades.subjID', $id)
                ->orderBy('students.lname', 'ASC')
                ->get();
        $data = [
            'substudnowviewpdf' => $substudnowviewpdf,
        ];

        $pdf = PDF::loadView('enrollment.reports.studentsub.pdf.attendancestud', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
}
