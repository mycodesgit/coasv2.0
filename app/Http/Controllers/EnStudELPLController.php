<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
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

        // function getEquivalentGPA($grade) {
        //     if ($grade === 'INC') {
        //         return ['gpa' => 'INC', 'status' => 'Incomplete'];
        //     } elseif ($grade === 'NN') {
        //         return ['gpa' => 'NN', 'status' => 'No Name'];
        //     } elseif ($grade === 'NG') {
        //         return ['gpa' => 'NG', 'status' => 'No Grade'];
        //     } elseif ($grade === 'Drp..') {
        //         return ['gpa' => 'Drp.', 'status' => 'Drop'];
        //     } elseif ($grade >= 97 || $grade == 1) {
        //         return ['gpa' => 1.0, 'status' => 'Passed'];
        //     } elseif ($grade >= 94) {
        //         return ['gpa' => 1.2, 'status' => 'Passed'];
        //     } elseif ($grade >= 91) {
        //         return ['gpa' => 1.5, 'status' => 'Passed'];
        //     } elseif ($grade >= 88) {
        //         return ['gpa' => 1.7, 'status' => 'Passed'];
        //     } elseif ($grade >= 85 || $grade == 2) {
        //         return ['gpa' => 2.0, 'status' => 'Passed'];
        //     } elseif ($grade >= 82) {
        //         return ['gpa' => 2.2, 'status' => 'Passed'];
        //     } elseif ($grade >= 79) {
        //         return ['gpa' => 2.5, 'status' => 'Passed'];
        //     } elseif ($grade >= 76) {
        //         return ['gpa' => 2.7, 'status' => 'Passed'];
        //     } elseif ($grade >= 75 || $grade == 3) {
        //         return ['gpa' => 3.0, 'status' => 'Passed'];
        //     } elseif ($grade >= 70) {
        //         return ['gpa' => 4.0, 'status' => 'Conditional'];
        //     } else {
        //         return ['gpa' => 5.0, 'status' => 'Failure'];
        //     }
        // }


        function getEquivalentGPA($grade, $isFourScale) {
            if ($grade === 'INC') return ['gpa' => 'INC', 'status' => 'Incomplete'];
            if ($grade === 'NN') return ['gpa' => 'NN', 'status' => 'No Name'];
            if ($grade === 'NG') return ['gpa' => 'NG', 'status' => 'No Grade'];
            if ($grade === 'Drp.') return ['gpa' => 'Drp.', 'status' => 'Drop'];

            if ($isFourScale) {
                // 4-point scale logic
                if ($grade >= 95 || $grade == 1) return ['gpa' => '1.0', 'status' => 'Passed'];
                if ($grade >= 94) return ['gpa' => '1.1', 'status' => 'Passed'];
                if ($grade >= 93) return ['gpa' => '1.2', 'status' => 'Passed'];
                if ($grade >= 92) return ['gpa' => '1.3', 'status' => 'Passed'];
                if ($grade >= 91) return ['gpa' => '1.4', 'status' => 'Passed'];
                if ($grade >= 90) return ['gpa' => '1.5', 'status' => 'Passed'];
                if ($grade >= 89) return ['gpa' => '1.6', 'status' => 'Passed'];
                if ($grade >= 88) return ['gpa' => '1.7', 'status' => 'Passed'];
                if ($grade >= 87) return ['gpa' => '1.8', 'status' => 'Passed'];
                if ($grade >= 86) return ['gpa' => '1.9', 'status' => 'Passed'];
                if ($grade >= 85 || $grade == 2) return ['gpa' => '2.0', 'status' => 'Passed'];
                if ($grade >= 84) return ['gpa' => '2.1', 'status' => 'Passed'];
                if ($grade >= 83) return ['gpa' => '2.2', 'status' => 'Passed'];
                if ($grade >= 82) return ['gpa' => '2.3', 'status' => 'Passed'];
                if ($grade >= 81) return ['gpa' => '2.4', 'status' => 'Passed'];
                if ($grade >= 80) return ['gpa' => '2.5', 'status' => 'Passed'];
                if ($grade >= 79) return ['gpa' => '2.6', 'status' => 'Passed'];
                if ($grade >= 78) return ['gpa' => '2.7', 'status' => 'Passed'];
                if ($grade >= 77) return ['gpa' => '2.8', 'status' => 'Passed'];
                if ($grade >= 76) return ['gpa' => '2.9', 'status' => 'Passed'];
                if ($grade >= 75 || $grade == 3) return ['gpa' => '3.0', 'status' => 'Passed'];
                if ($grade >= 74) return ['gpa' => '4.0', 'status' => 'Conditional'];
                if ($grade >= 73) return ['gpa' => '4.0', 'status' => 'Conditional'];
                if ($grade >= 72) return ['gpa' => '4.0', 'status' => 'Conditional'];
                if ($grade >= 71) return ['gpa' => '4.0', 'status' => 'Conditional'];
                if ($grade >= 70) return ['gpa' => '4.0', 'status' => 'Conditional'];
                return ['gpa' => '5.0', 'status' => 'Failure'];
            } else {
                // Standard GPA logic
                if ($grade >= 97 || $grade == 1) return ['gpa' => '1.00', 'status' => 'Passed'];
                if ($grade >= 94) return ['gpa' => '1.25', 'status' => 'Passed'];
                if ($grade >= 91) return ['gpa' => '1.50', 'status' => 'Passed'];
                if ($grade >= 88) return ['gpa' => '1.75', 'status' => 'Passed'];
                if ($grade >= 85 || $grade == 2) return ['gpa' => '2.00', 'status' => 'Passed'];
                if ($grade >= 82) return ['gpa' => '2.25', 'status' => 'Passed'];
                if ($grade >= 79) return ['gpa' => '2.50', 'status' => 'Passed'];
                if ($grade >= 76) return ['gpa' => '2.75', 'status' => 'Passed'];
                if ($grade >= 75 || $grade == 3) return ['gpa' => '3.00', 'status' => 'Passed'];
                if ($grade >= 70) return ['gpa' => '4.00', 'status' => 'Conditional'];
                return ['gpa' => '5.00', 'status' => 'Failure'];
            }
        } 

        function displayGrade($grade, $isFourScale) {
            if (is_numeric($grade)) {
                $equivalent = getEquivalentGPA($grade, $isFourScale);
                return $equivalent['gpa'];
            }
            return $grade;
        }

        // Fetch data
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
            ->select(
                'program_en_history.*',
                'students.lname', 'students.fname', 'students.mname', 'students.ext',
                'students.gender', 'students.address',
                'studgrades.studID', 'studgrades.subjFgrade',
                'coasv2_db_schedule.sub_offered.subCode', 'coasv2_db_schedule.sub_offered.subSec',
                'coasv2_db_schedule.subjects.sub_name', 'coasv2_db_schedule.sub_offered.subUnit'
            )
            ->orderBy('program_en_history.studYear', 'ASC')
            ->get();

        // Check if subSec contains '4-'
        $isFourScale = Str::contains($data->first()->subSec ?? '', '4-');

        // Convert grades
        $data = $data->map(function ($item) use ($isFourScale) {
            $item->subjFgrade = displayGrade($item->subjFgrade, $isFourScale);
            return $item;
        });

        // Return JSON response
        return response()->json(['data' => $data]);

    }
}
