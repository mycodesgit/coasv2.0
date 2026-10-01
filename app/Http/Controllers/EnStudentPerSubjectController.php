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
use ZipArchive;

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

        return view('enrollment.reports.studentsub.listsearch_studsub', compact('sy'));
    }

    public function getlistsearch_studsubjectsRead(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        // Prepare a subquery that aggregates the student count per subject.
        $studCountSubquery = DB::table('coasv2_db_enrollment.studgrades')
            ->select('subjID', DB::raw('COUNT(subjID) as countstud'))
            ->where('campus', $campus)
            ->groupBy('subjID');

        // Join with subjects and the aggregated student count.
        $data = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            // Left join ensures that even if a subject has no enrolled students, it is still returned.
            // ->leftJoinSub($studCountSubquery, 'studgrades', function ($join) {
            //     $join->on('sub_offered.id', '=', 'studgrades.subjID');
            // })
            ->where('sub_offered.schlyear', $schlyear)
            ->where('sub_offered.semester', $semester)
            ->where('sub_offered.campus', $campus)
            ->where('sub_offered.subCode', 'NOT LIKE', '%-GSS-%')
            ->select(
                'subjects.sub_name',
                'subjects.sub_title',
                'sub_offered.*',
                'sub_offered.id as sid',
                // Use COALESCE to return 0 when there are no matching studgrades.
                //DB::raw('COALESCE(studgrades.countstud, 0) as countstud')
            )
            ->get();

        return response()->json(['data' => $data]);
    }

    public function gradschoolgetlistsearch_studsubjectsRead(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        // $studCountSubquery = DB::table('coasv2_db_enrollment.studgrades')
        //     ->select('subjID', DB::raw('COUNT(subjID) as countstud'))
        //     ->where('campus', $campus)
        //     ->groupBy('subjID');

        $data = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            // ->leftJoinSub($studCountSubquery, 'studgrades', function ($join) {
            //     $join->on('sub_offered.id', '=', 'studgrades.subjID');
            // })
            ->where('sub_offered.schlyear', $schlyear)
            ->where('sub_offered.semester', $semester)
            ->where('sub_offered.campus', $campus)
            ->where('sub_offered.subCode', 'LIKE', '%-GSS-%')
            ->select(
                'subjects.sub_name',
                'subjects.sub_title',
                'sub_offered.*',
                'sub_offered.id as sid',
                //DB::raw('COUNT(coasv2_db_enrollment.studgrades.subjID) as countstud')
                //DB::raw('COALESCE(studgrades.countstud, 0) as countstud')
                //DB::raw('COALESCE(studgrades.countstud, 0) as countstud')
            )
            ->get();

        return response()->json(['data' => $data]);
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

        // return view('enrollment.reports.studentsub.listsearchview_studsub', compact('substudnowview'));
        return view('enrollment.reports.studentsub.pdf.attendancestud', compact('substudnowview'));
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
                ->orderBy('students.fname', 'ASC')
                ->get();
        $data = [
            'substudnowviewpdf' => $substudnowviewpdf,
        ];

        $pdf = PDF::loadView('enrollment.reports.studentsub.pdf.attendancestud', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function bulkDownloadPdf(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus   = $request->query('campus') ?: Auth::guard('web')->user()->campus;
        $offset   = (int) $request->query('offset', 0);
        $limit    = (int) $request->query('limit', 100);

        // 1. Fetch distinct offered subjects using correct table column aliases
        $offeredSubjects = Grade::select(
                'so.id as subj_id',
                'so.subSec',
                'so.schlyear',
                'so.semester',
                'so.isType',
                's.sub_name',
                's.sub_title'
            )
            ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
            ->leftJoin('coasv2_db_schedule.subjects as s', 'so.subCode', '=', 's.sub_code')
            ->when($schlyear, fn($q) => $q->where('so.schlyear', $schlyear))
            ->when($semester, fn($q) => $q->where('so.semester', $semester))
            ->when($campus, fn($q) => $q->where('so.campus', $campus))
            ->groupBy('so.id', 'so.subSec', 'so.schlyear', 'so.semester', 'so.isType', 's.sub_name', 's.sub_title')
            ->skip($offset)
            ->take($limit)
            ->get();

        if ($offeredSubjects->isEmpty()) {
            return back()->with('error', 'No records found to generate PDFs.');
        }

        // 2. Setup Zip File response
        $zipFileName = 'Bulk_Attendance_' . time() . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive;

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($offeredSubjects as $subject) {
                // Fetch students for each class matching your existing studsubjectsReadPDF logic
                $substudnowviewpdf = Grade::select('so.*', 'studgrades.*', 'studgrades.id as sgid', 'studgrades.status as gstat', 'students.*', 's.*')
                    ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
                    ->join('students', 'studgrades.studID', '=', 'students.stud_id')
                    ->leftJoin('coasv2_db_schedule.sub_offered as so2', 'studgrades.subjID', '=', 'so2.id')
                    ->leftJoin('coasv2_db_schedule.subjects as s', 'so2.subCode', '=', 's.sub_code')
                    ->where('so.schlyear', $subject->schlyear)
                    ->where('so.semester', $subject->semester)
                    ->where('studgrades.subjID', $subject->subj_id)
                    ->orderBy('students.lname', 'ASC')
                    ->orderBy('students.fname', 'ASC')
                    ->get();

                $data = [
                    'substudnowviewpdf' => $substudnowviewpdf,
                ];

                // Render single attendance sheet PDF
                $pdf = Pdf::loadView('enrollment.reports.studentsub.pdf.attendancestud', $data)
                    ->setPaper('Legal', 'portrait');

                // Sanitize file names inside zip archive
                $cleanSubName = preg_replace('/[^A-Za-z0-9\-]/', '_', $subject->sub_name ?? 'Subject');
                $cleanSubSec  = preg_replace('/[^A-Za-z0-9\-]/', '_', $subject->subSec ?? 'Section');

                $singleFileName = 'Attendance_' . $cleanSubName . '_' . $cleanSubSec . '.pdf';

                // Add raw PDF content to ZIP
                $zip->addFromString($singleFileName, $pdf->output());
            }

            $zip->close();
        }

        // Returns ZIP file for automatic browser download
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
