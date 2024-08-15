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

class EnStudReportCardController extends Controller
{
    public function reportCard_list()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.reportcard.list_reportcard', compact('sy'));
    }

    public function reportCard_listsearch(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
            
        $stud_id = $request->stud_id;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('campus', $campus)->where('stud_id', $stud_id)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }

        return view('enrollment.reports.reportcard.listsearch_reportcard', compact('sy'));
    }

    public function reportCard_listsearchpdf(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        // Define a function to convert numerical grades to GPA equivalents
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

        $studrepcard = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->join('studgrades', 'program_en_history.studentID', '=', 'studgrades.studID')
                    ->leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select('students.*', 'program_en_history.*', 'coasv2_db_schedule.programs.progName', 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('program_en_history.schlyear',  $schlyear)
                    ->where('program_en_history.semester',  $semester)
                    ->where('program_en_history.campus',  $campus)
                    ->where('program_en_history.studentID', $stud_id)->first();

        $studrepcardsub = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('coasv2_db_schedule.sub_offered.schlyear',  $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester',  $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus',  $campus)
                    ->where('studgrades.studID', $stud_id)
                    ->orderBy('coasv2_db_schedule.sub_offered.subCode', 'ASC')
                    ->get();

        $totalCredits = 0;
        $weightedSum = 0;
        $subjectsData = [];

        foreach ($studrepcardsub as $subject) {
            $creditEarned = (float)$subject->creditEarned;

            // Check if subjFgrade is numeric, otherwise use subjComp
            if (is_numeric($subject->subjFgrade)) {
                $subjFgrade = (float)$subject->subjFgrade;
            } else {
                $subjFgrade = (float)$subject->subjComp;
            }

            // Convert numerical grades to GPA equivalents
            if (is_numeric($subjFgrade) && strpos($subjFgrade, '.') === false) {
                $subjFgrade = getEquivalentGPA($subjFgrade)['gpa'];
            }

            $weightedSumPerSubject = $subjFgrade * $creditEarned;

            $totalCredits += $creditEarned;
            $weightedSum += $weightedSumPerSubject;

            $subjectsData[] = [
                'subject' => $subject,
                'weightedSumPerSubject' => $weightedSumPerSubject
            ];
        }

        $average = $totalCredits ? $weightedSum / $totalCredits : 0;

        $data = [
            'studrepcard' => $studrepcard,
            'subjectsData' => $subjectsData,
            'average' => $average
        ];

        $pdf = PDF::loadView('enrollment.reports.reportcard.reportcardpdftem', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

    public function studevalRead()
    {
        return view('enrollment.reports.evaluation.studeval');
    }

    public function studevalRead_listsearch(Request $request)
    {
            
        $stud_id = $request->stud_id;
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('campus', $campus)->where('stud_id', $stud_id)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }

        return view('enrollment.reports.evaluation.studeval_listsearch');
    }

    public function studevalRead_listsearchpdf(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $campus = Auth::guard('web')->user()->campus;

        // Function to determine if old system is used based on the earliest schlyear
        function isOldSystem($earliestYear) {
            return version_compare($earliestYear, '2022-2023', '<');
        }

        // GPA conversion function with system check
        function getEquivalentGPA($grade, $isOldSystem) {
            if ($isOldSystem) {
                // Old GPA conversion logic here
                if ($grade === 'INC') {
                    return ['gpa' => 'INC', 'status' => 'Incomplete'];
                } elseif ($grade === 'NN') {
                    return ['gpa' => 'NN', 'status' => 'No Name'];
                } elseif ($grade === 'NG') {
                    return ['gpa' => 'NG', 'status' => 'No Grade'];
                } elseif ($grade === 'Drp..') {
                    return ['gpa' => 'Drp.', 'status' => 'Drop'];
                } elseif ($grade >= 97 || $grade == 1) {
                    return ['gpa' => number_format(1.0, 2), 'status' => 'Passed'];
                } elseif ($grade >= 94) {
                    return ['gpa' => number_format(1.2, 2), 'status' => 'Passed'];
                } elseif ($grade >= 91) {
                    return ['gpa' => number_format(1.5, 2), 'status' => 'Passed'];
                } elseif ($grade >= 88) {
                    return ['gpa' => number_format(1.7, 2), 'status' => 'Passed'];
                } elseif ($grade >= 85 || $grade == 2) {
                    return ['gpa' => number_format(2.0, 2), 'status' => 'Passed'];
                } elseif ($grade >= 82) {
                    return ['gpa' => number_format(2.2, 2), 'status' => 'Passed'];
                } elseif ($grade >= 79) {
                    return ['gpa' => number_format(2.5, 2), 'status' => 'Passed'];
                } elseif ($grade >= 76) {
                    return ['gpa' => number_format(2.7, 2), 'status' => 'Passed'];
                } elseif ($grade >= 75 || $grade == 3) {
                    return ['gpa' => number_format(3.0, 2), 'status' => 'Passed'];
                } elseif ($grade >= 70) {
                    return ['gpa' => number_format(4.0, 2), 'status' => 'Conditional'];
                } else {
                    return ['gpa' => number_format(5.0, 2), 'status' => 'Failure'];
                }
            } else {
                // New GPA conversion logic here
                if ($grade === 'INC') {
                    return ['gpa' => 'INC', 'status' => 'Incomplete'];
                } elseif ($grade === 'NN') {
                    return ['gpa' => 'NN', 'status' => 'No Name'];
                } elseif ($grade === 'NG') {
                    return ['gpa' => 'NG', 'status' => 'No Grade'];
                } elseif ($grade === 'Drp..') {
                    return ['gpa' => 'Drp.', 'status' => 'Drop'];
                } elseif ($grade >= 97 || $grade == 1) {
                    return ['gpa' => number_format(1.00, 2), 'status' => 'Passed'];
                } elseif ($grade >= 94) {
                    return ['gpa' => number_format(1.25, 2), 'status' => 'Passed'];
                } elseif ($grade >= 91) {
                    return ['gpa' => number_format(1.50, 2), 'status' => 'Passed'];
                } elseif ($grade >= 88) {
                    return ['gpa' => number_format(1.75, 2), 'status' => 'Passed'];
                } elseif ($grade >= 85 || $grade == 2) {
                    return ['gpa' => number_format(2.00, 2), 'status' => 'Passed'];
                } elseif ($grade >= 82) {
                    return ['gpa' => number_format(2.25, 2), 'status' => 'Passed'];
                } elseif ($grade >= 79) {
                    return ['gpa' => number_format(2.50, 2), 'status' => 'Passed'];
                } elseif ($grade >= 76) {
                    return ['gpa' => number_format(2.75, 2), 'status' => 'Passed'];
                } elseif ($grade >= 75 || $grade == 3) {
                    return ['gpa' => number_format(3.00, 2), 'status' => 'Passed'];
                } elseif ($grade >= 70) {
                    return ['gpa' => number_format(4.00, 2), 'status' => 'Conditional'];
                } else {
                    return ['gpa' => number_format(5.00, 2), 'status' => 'Failure'];
                }
            }
        }

        // Fetch the student's earliest schlyear
        $studrepcard = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->join('studgrades', 'program_en_history.studentID', '=', 'studgrades.studID')
            ->leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
            ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
            ->select('students.*', 'program_en_history.*', 'coasv2_db_schedule.programs.progName', 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
            ->where('program_en_history.campus', $campus)
            ->where('program_en_history.studentID', $stud_id)
            ->first();

        $earliestYear = StudEnrolmentHistory::where('studentID', $stud_id)
            ->orderBy('schlyear', 'ASC')
            ->value('schlyear');

        $isOldSystem = isOldSystem($earliestYear);

        // Fetch the student's grades and process them
        $studrepcardsub = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
            ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
            ->select('studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
            ->where('coasv2_db_schedule.sub_offered.campus', $campus)
            ->where('studgrades.studID', $stud_id)
            ->orderBy('coasv2_db_schedule.sub_offered.schlyear', 'ASC')  // Sort by school year first
            ->orderByRaw("FIELD(coasv2_db_schedule.sub_offered.semester, '1', '2', '3') ASC") // Sort by semester: 1 (First), 2 (Second), 3 (Summer)
            ->orderBy('coasv2_db_schedule.sub_offered.subCode', 'ASC')
            ->get();

        $totalCredits = 0;
        $weightedSum = 0;
        $subjectsData = [];

        foreach ($studrepcardsub as $subject) {
            $creditEarned = (float)$subject->creditEarned;

            // GPA conversion for subjFgrade
            $subjFgrade = $subject->subjFgrade;
            $gpaFgrade = is_numeric($subjFgrade) && strpos($subjFgrade, '.') === false
                ? getEquivalentGPA($subjFgrade, $isOldSystem)['gpa']
                : $subjFgrade;

            // GPA conversion for subjComp
            $subjComp = $subject->subjComp;
            $gpaComp = is_numeric($subjComp) && strpos($subjComp, '.') === false
                ? getEquivalentGPA($subjComp, $isOldSystem)['gpa']
                : $subjComp;

            // Compute for GPA (ensure both are numbers)
            $gpaEarned = $gpaFgrade === 'INC' || $gpaFgrade === 'NN' || $gpaFgrade === 'NG' || $gpaFgrade === 'Drp.'
                ? 0
                : (float)$gpaFgrade;

            $totalCredits += $creditEarned;
            $weightedSum += $gpaEarned * $creditEarned;

            $subjectsData[] = [
                'subjCode' => $subject->subjCode,
                'subjTitle' => $subject->subjTitle,
                'creditEarned' => $creditEarned,
                'subjFgrade' => $gpaFgrade,
                'subjComp' => $gpaComp,
                'gpa' => number_format($gpaEarned, 2),
            ];
        }

        $gpa = $totalCredits > 0 ? number_format($weightedSum / $totalCredits, 2) : 0;

        $data = [
            'student' => $studrepcard,
            'subjects' => $subjectsData,
            'gpa' => $gpa,
        ];

        $pdf = PDF::loadView('enrollment.reports.evaluation.studevalpdf_listsearch', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }

}
