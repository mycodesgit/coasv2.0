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
use App\Models\EnrollmentDB\GradesheetLogbook;

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
        $schlyear    = $request->query('schlyear');
        $semester    = $request->query('semester');
        $collegeabbr = $request->query('collegeabbr');
        $campus      = Auth::guard('web')->user()->campus;

        $data = GradesheetLogbook::leftJoin('coasv2_db_schedule.faculty', 'gradesheetlogbook.facultyid', '=', 'coasv2_db_schedule.faculty.id')
                        ->leftJoin('coasv2_db_schedule.sub_offered', 'gradesheetlogbook.subjectid', '=', 'coasv2_db_schedule.sub_offered.id')
                        ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                        ->select(
                            'gradesheetlogbook.*',
                            'coasv2_db_schedule.faculty.lname', 
                            'coasv2_db_schedule.faculty.fname', 
                            'coasv2_db_schedule.subjects.sub_name',
                            'coasv2_db_schedule.subjects.sub_title',
                            'coasv2_db_schedule.subjects.subjcollege',
                            'coasv2_db_schedule.sub_offered.subSec'
                        )
                        ->where('gradesheetlogbook.schlyear', $schlyear)
                        ->where('gradesheetlogbook.semester', $semester)
                        ->where('gradesheetlogbook.campus', $campus)
                        ->where('gradesheetlogbook.collegeabbr', $collegeabbr)
                        ->orderBy('gradesheetlogbook.timebeingsubmitted', 'DESC')
                        ->get();

        return response()->json(['data' => $data]);
    }

    public function logbookpdfprint(Request $request)
    {
        $schlyear    = $request->query('schlyear');
        $semester    = $request->query('semester');
        $collegeabbr = $request->query('collegeabbr');
        $campus      = Auth::guard('web')->user()->campus;

        // $gslog = GradesheetLogbook::leftJoin('coasv2_db_schedule.faculty', 'gradesheetlogbook.facultyid', '=', 'coasv2_db_schedule.faculty.id')
        //                 ->leftJoin('coasv2_db_schedule.sub_offered', 'gradesheetlogbook.subjectid', '=', 'coasv2_db_schedule.sub_offered.id')
        //                 ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
        //                 ->select(
        //                     'gradesheetlogbook.*',
        //                     'coasv2_db_schedule.faculty.lname', 
        //                     'coasv2_db_schedule.faculty.fname', 
        //                     'coasv2_db_schedule.subjects.sub_name',
        //                     'coasv2_db_schedule.subjects.sub_title',
        //                     'coasv2_db_schedule.subjects.subjcollege',
        //                     'coasv2_db_schedule.sub_offered.subSec'
        //                 )
        //                 ->where('gradesheetlogbook.schlyear', $schlyear)
        //                 ->where('gradesheetlogbook.semester', $semester)
        //                 ->where('gradesheetlogbook.campus', $campus)
        //                 ->where('gradesheetlogbook.collegeabbr', $collegeabbr)
        //                 ->orderBy('gradesheetlogbook.timebeingsubmitted', 'DESC')
        //                 ->get();
        $gslog = SubjectOffered::leftJoin('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('scheduleclass', 'sub_offered.id', '=', 'scheduleclass.subject_id')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->select('sub_offered.*', 'subjects.*', 'sub_offered.id as soid', 'faculty.lname', 'faculty.fname', 'faculty.faccollege')
                        ->where('sub_offered.schlyear', $schlyear)
                        ->where('sub_offered.semester', $semester)
                        ->where('sub_offered.campus', $campus)
                        ->where('faculty.faccollege', $collegeabbr)
                        ->where('sub_offered.subCode', 'NOT LIKE', '%-GSS-%')
                        ->orderBy('faculty.lname', 'ASC')
                        ->groupBy('sub_offered.id')
                        ->get();
        
        $data = [
            'gslog' => $gslog,
        ];

        $pdf = PDF::loadView('enrollment.reports.logbok.logbookpdf', $data)->setPaper('legal', 'landscape');
        return $pdf->stream();
    }
}
