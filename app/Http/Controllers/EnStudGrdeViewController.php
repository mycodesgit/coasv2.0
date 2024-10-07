<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

class EnStudGrdeViewController extends Controller
{
    public function studviewgradeRead()
    {
        return view('enrollment.reports.studgrdview.list_studgrdeview');
    }

    public function search_studviewgradeRead(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('campus', $campus)->where('stud_id', $stud_id)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }

        $studauth = Student::where('stud_id', '=', $stud_id)->first();

        $studsubviewgrd = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('studgrades.studID', $stud_id)
                    ->where('studgrades.campus', '=', Auth::guard('web')->user()->campus)
                    ->orderBy('coasv2_db_schedule.sub_offered.id', 'ASC')
                    ->get();

        return view('enrollment.reports.studgrdview.listsearch_studgrdeview', compact('studauth', 'studsubviewgrd'));
    }

    public function searchgradschool_studviewgradeRead(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('campus', $campus)->where('stud_id', $stud_id)->where('stud_id', 'LIKE', '%-G')->where('stud_id', 'LIKE', '%-N')->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }

        $studauth = Student::where('stud_id', '=', $stud_id)->first();

        $studsubviewgrd = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('studgrades.studID', $stud_id)
                    ->where('studgrades.campus', '=', Auth::guard('web')->user()->campus)
                    ->orderBy('coasv2_db_schedule.sub_offered.id', 'ASC')
                    ->get();

        return view('enrollment.reports.studgrdview.listsearch_studgrdeview', compact('studauth', 'studsubviewgrd'));
    }
}
