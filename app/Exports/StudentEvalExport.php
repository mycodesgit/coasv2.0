<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\UniqueStudentID;

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

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;

class StudentEvalExport implements FromView
{
    protected $stud_id;

    public function __construct($stud_id)
    {
        $this->stud_id = $stud_id;
    }

    public function view(): View
    {
        $campus = Auth::guard('web')->user()->campus;

        $campusArray = array_map('trim', explode(',', $campus));

        // Define a function to convert numerical grades to GPA equivalents
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
                } elseif ($grade >= 95 || $grade == 1) {
                    return ['gpa' => number_format(1.0, 1), 'status' => 'Passed'];
                } elseif ($grade >= 94) {
                    return ['gpa' => number_format(1.2, 1), 'status' => 'Passed'];
                } elseif ($grade >= 91) {
                    return ['gpa' => number_format(1.5, 1), 'status' => 'Passed'];
                } elseif ($grade >= 88) {
                    return ['gpa' => number_format(1.7, 1), 'status' => 'Passed'];
                } elseif ($grade >= 85 || $grade == 2) {
                    return ['gpa' => number_format(2.0, 1), 'status' => 'Passed'];
                } elseif ($grade >= 82) {
                    return ['gpa' => number_format(2.2, 1), 'status' => 'Passed'];
                } elseif ($grade >= 79) {
                    return ['gpa' => number_format(2.5, 1), 'status' => 'Passed'];
                } elseif ($grade >= 76) {
                    return ['gpa' => number_format(2.7, 1), 'status' => 'Passed'];
                } elseif ($grade >= 75 || $grade == 3) {
                    return ['gpa' => number_format(3.0, 1), 'status' => 'Passed'];
                } elseif ($grade >= 70) {
                    return ['gpa' => number_format(4.0, 1), 'status' => 'Conditional'];
                } else {
                    return ['gpa' => number_format(5.0, 1), 'status' => 'Failure'];
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

        $studrepcard = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->join('studgrades', 'program_en_history.studentID', '=', 'studgrades.studID')
                    ->leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select('students.*', 'program_en_history.*', 'coasv2_db_schedule.programs.progName', 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where(function ($q) use ($campusArray) {
                        foreach ($campusArray as $campus) {
                            $q->orWhere('program_en_history.campus', 'LIKE', "%$campus%");
                        }
                    })
                    ->where('program_en_history.studentID', $this->stud_id)
                    ->orderBy('program_en_history.id', 'desc')->first();

        $studrepcardsub = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select('studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    //->where('coasv2_db_schedule.sub_offered.campus',  $campus)
                    ->where(function ($q) use ($campusArray) {
                        foreach ($campusArray as $campus) {
                            $q->orWhere('coasv2_db_schedule.sub_offered.campus', 'LIKE', "%$campus%");
                        }
                    })
                    ->where('studgrades.studID', $this->stud_id)
                    ->orderBy('coasv2_db_schedule.sub_offered.schlyear', 'ASC')  // Sort by school year first
                    ->orderByRaw("FIELD(coasv2_db_schedule.sub_offered.semester, '1', '2', '3') ASC") // Sort by semester: 1 (First), 2 (Second), 3 (Summer)
                    ->orderBy('coasv2_db_schedule.sub_offered.subCode', 'ASC')
                    ->get();

        $totalCredits = 0;
        $weightedSum = 0;
        $subjectsData = [];

        // $firstYear = !empty($studrepcardsub) ? $studrepcardsub->first()->schlyear : '2024-2025';
        $firstYear = ($studrepcardsub && $studrepcardsub->isNotEmpty()) ? $studrepcardsub->first()->schlyear : '2024-2025';
        $isOldSystem = (intval(substr($firstYear, 0, 4)) < 2022);

        foreach ($studrepcardsub as $subject) {
            $creditEarned = (float)$subject->creditEarned;

            $subjFgrade = $subject->subjFgrade;
            $gpaFgrade = is_numeric($subjFgrade) && strpos($subjFgrade, '.') === false
                ? getEquivalentGPA($subjFgrade, $isOldSystem)['gpa']
                : $subjFgrade;

            $subjComp = $subject->subjComp;
            $gpaComp = is_numeric($subjComp) && strpos($subjComp, '.') === false
                ? getEquivalentGPA($subjComp, $isOldSystem)['gpa']
                : $subjComp;

            $weightedSumPerSubject = is_numeric($gpaFgrade) ? $gpaFgrade * $creditEarned : 0;

            $totalCredits += $creditEarned;
            $weightedSum += $weightedSumPerSubject;

            $semester = $subject->semester;
            $schoolYear = $subject->schlyear;

            if (!isset($subjectsData[$schoolYear][$semester])) {
                $subjectsData[$schoolYear][$semester] = [];
            }

            $subjectsData[$schoolYear][$semester][] = [
                'subject' => $subject,
                'gpaFgrade' => $gpaFgrade,
                'gpaComp' => $gpaComp
            ];
        }


        $average = $totalCredits ? $weightedSum / $totalCredits : 0;

        $data = [
            'studrepcard' => $studrepcard,
            'subjectsData' => $subjectsData,
            'average' => $average,
            'semester' => $semester,
            'schoolYear' => $schoolYear,
        ];

        return view('enrollment.reports.evaluation.studeval_excel', $data);
    }
}

