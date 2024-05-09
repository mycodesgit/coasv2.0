<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\AdmissionDB\Programs;

use App\Models\SettingDB\ConfigureCurrent;

class EnstudgradeController extends Controller
{
    public function studgrade_search()
    {   
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
            
        $grdCode = GradeCode::all();
        return view('enrollment.gradesheet.list_studgrade',  compact('grdCode', 'sy'));
    }

    public function studgrade_searchlist(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        $schlyear = is_array($schlyear) ? $schlyear : [$schlyear];
        $semester = is_array($semester) ? $semester : [$semester];

        $data = SubjectOffered::select('sub_offered.*', 'subjects.*', 'sub_offered.id as sid',)
                        ->join('subjects', 'sub_offered.subcode', '=', 'subjects.sub_code')
                        ->whereIn('sub_offered.schlyear', $schlyear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->get();
        $grdCode = GradeCode::all();
        $totalSearchResults = count($data);

        return view('enrollment.gradesheet.listsearch_studgrade', compact('data', 'totalSearchResults', 'grdCode'));
    }

    public function geneStudent1(Request $request, $id)
    {
        $id = $request->id;
        $grade = $request->grade;

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        $gradereg = Grade::where('subjID', $id)
                        ->where('status', '!=', '')
                        ->count();

        $genstud = Grade::select('so.*', 'studgrades.*', 'studgrades.id as sgid', 'studgrades.status as gstat', 'students.*', 's.*')
                ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
                ->join('students', 'studgrades.studID', '=', 'students.stud_id')
                ->leftJoin('coasv2_db_schedule.sub_offered as so2', 'studgrades.subjID', '=', 'so2.id')
                ->leftJoin('coasv2_db_schedule.subjects as s', 'so2.subCode', '=', 's.sub_code')
                ->where('so.schlyear', $schlyear)
                ->where('so.semester', $semester)
                ->where('studgrades.subjID', $id)
                ->orderBy('students.lname', 'ASC')
                ->get(); 
        $grdCode = GradeCode::all();

        $totalSearchResults = count($genstud);

        $grades = [];

        foreach ($genstud as $dataItem) {
            $grades[$dataItem->subjectID] = Grade::where('subjID', $dataItem->subjectID)->get();
        }
        return view('enrollment.gradesheet.view_search', compact('genstud', 'totalSearchResults', 'grdCode', 'grade', 'grades', 'gradereg'));
    }

    public function geneStudent(Request $request)
    {
        $sid = $request->sid;

        $studgradesData = Grade::on('enrollment')
                ->where('subjID', $sid)
                ->select('studID', 'subjID', 'subjFgrade', 'subjComp', 'creditEarned', 'postedBy', 'students.*', 'studgrades.status as gstat')
                ->join('students', 'studgrades.studID', '=', 'students.stud_id')
                ->orderBy('students.lname', 'ASC')
                ->get();
        $grdCode = GradeCode::all();
        return view('enrollment.gradesheet.view_search', compact('studgradesData', 'grdCode'));

    }

}
