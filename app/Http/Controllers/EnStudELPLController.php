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

use App\Models\ScholarshipDB\Scholar;

use App\Models\AssessmentDB\StudentFee;
use App\Models\AssessmentDB\StudentAppraisal;

use App\Models\SettingDB\ConfigureCurrent;

class EnStudELPLController extends Controller
{
    public function elpl_list()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $class = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')->get();

        return view('enrollment.reports.enrolmentlist.elpl', compact('sy', 'class'));
    }

    public function getCourses(Request $request)
    {
        $semester = $request->semester;
        $schlyear = $request->schlyear;
        $campus = Auth::guard('web')->user()->campus;

        $courses = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
            ->where('class_enroll.semester', $semester)
            ->where('class_enroll.schlyear', $schlyear)
            ->where('class_enroll.campus', $campus)
            ->groupBy('programs.progCod')
            ->get();

        return response()->json($courses);
    }


    public function elpl_listsearch(Request $request)
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
        $progCod = $request->query('progCod');
        $campus = Auth::guard('web')->user()->campus;

        $studelpl = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                        ->join('studgrades', 'program_en_history.studentID', '=', 'studgrades.studID')
                        ->leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                        ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                        ->where('program_en_history.schlyear', $schlyear)
                        ->where('program_en_history.semester', $semester)
                        ->where('program_en_history.campus', $campus)
                        ->where('program_en_history.progCod', $progCod)
                        ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                        ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                        ->select('program_en_history.*', 'students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.gender', 'studgrades.studID', 'coasv2_db_schedule.sub_offered.subCode', 'coasv2_db_schedule.subjects.sub_name')
                        ->limit('500')
                        ->get()
                        ->groupBy('studentID');

        return view('enrollment.reports.enrolmentlist.listsearch_elpl', compact('sy', 'studelpl'));
    }

    public function elplajax_listsearch(Request $request)
    {
            
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $progCod = $request->query('progCod');
        $campus = Auth::guard('web')->user()->campus;

        function getEquivalentGPA($grade) {
            if ($grade === 'INC') {
                return ['gpa' => 'INC', 'status' => 'Incomplete'];
            } elseif ($grade === 'NN') {
                return ['gpa' => 'NN', 'status' => 'No Name'];
            } elseif ($grade === 'NG') {
                return ['gpa' => 'NG', 'status' => 'No Grade'];
            } elseif ($grade === 'Drp..') {
                return ['gpa' => 'Drp.', 'status' => 'Drop'];
            } elseif ($grade >= 97 || $grade == 1) {
                return ['gpa' => 1.0, 'status' => 'Passed'];
            } elseif ($grade >= 94) {
                return ['gpa' => 1.2, 'status' => 'Passed'];
            } elseif ($grade >= 91) {
                return ['gpa' => 1.5, 'status' => 'Passed'];
            } elseif ($grade >= 88) {
                return ['gpa' => 1.7, 'status' => 'Passed'];
            } elseif ($grade >= 85 || $grade == 2) {
                return ['gpa' => 2.0, 'status' => 'Passed'];
            } elseif ($grade >= 82) {
                return ['gpa' => 2.2, 'status' => 'Passed'];
            } elseif ($grade >= 79) {
                return ['gpa' => 2.5, 'status' => 'Passed'];
            } elseif ($grade >= 76) {
                return ['gpa' => 2.7, 'status' => 'Passed'];
            } elseif ($grade >= 75 || $grade == 3) {
                return ['gpa' => 3.0, 'status' => 'Passed'];
            } elseif ($grade >= 70) {
                return ['gpa' => 4.0, 'status' => 'Conditional'];
            } else {
                return ['gpa' => 5.0, 'status' => 'Failure'];
            }
        }

        function displayGrade($grade) {
            if (is_numeric($grade) && strpos($grade, '.') === false) {
                $equivalent = getEquivalentGPA($grade);
                return $equivalent['gpa'];
            }
            return $grade;
        }

        $data = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                        ->join('studgrades', 'program_en_history.studentID', '=', 'studgrades.studID')
                        ->leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                        ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                        ->where('program_en_history.schlyear', $schlyear)
                        ->where('program_en_history.semester', $semester)
                        ->where('program_en_history.campus', $campus)
                        ->where('program_en_history.progCod', $progCod)
                        ->where('coasv2_db_schedule.sub_offered.schlyear', '=', $schlyear)
                        ->where('coasv2_db_schedule.sub_offered.semester', '=', $semester)
                        ->select('program_en_history.*', 'students.lname', 'students.fname', 'students.mname', 'students.ext', 'students.gender', 'students.address', 'studgrades.studID', 'studgrades.subjFgrade', 'coasv2_db_schedule.sub_offered.subCode', 'coasv2_db_schedule.subjects.sub_name',  'coasv2_db_schedule.sub_offered.subUnit')
                        ->orderBy('program_en_history.studYear', 'ASC')
                        ->get();

        $data = $data->map(function($item) {
            $item->subjFgrade = displayGrade($item->subjFgrade); // Convert the grade
            return $item;
        });

       return response()->json(['data' => $data]);
    }
}
