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
use App\Models\EnrollmentDB\Student;

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

        $schlyear = is_array($schlyear) ? $schlyear : [$schlyear];
        $semester = is_array($semester) ? $semester : [$semester];

        $data = SubjectOffered::select('sub_offered.*', 'subjects.*', 'sub_offered.id as sid',)
                        ->join('subjects', 'sub_offered.subcode', '=', 'subjects.sub_code')
                        ->whereIn('sub_offered.schlyear', $schlyear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->get();
        $grdCode = GradeCode::all();
        $totalSearchResults = count($data);

        return view('enrollment.gradesheet.listsearch_studgrade', compact('sy', 'data', 'totalSearchResults', 'grdCode'));
    }

    public function studgrade_searchlistajax(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;


        $data = SubjectOffered::select('sub_offered.*', 'subjects.*', 'sub_offered.id as sid',)
                        ->join('subjects', 'sub_offered.subcode', '=', 'subjects.sub_code')
                        ->where('sub_offered.schlyear', $schlyear)
                        ->where('sub_offered.semester', $semester)
                        ->where('sub_offered.campus', $campus)
                        ->get();

        // $grdCode = GradeCode::all();
        // $totalSearchResults = count($data);

        return response()->json(['data' => $data]);
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

        // $genstud = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
        //             ->join('students', 'studgrades.studID', '=', 'students.stud_id')
        //             ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyear)
        //             ->where('coasv2_db_schedule.sub_offered.semester', $semester)
        //             ->get();


        $grdpercentage = range(44, 78); 
        $grdCode = GradeCode::whereIn('id', $grdpercentage)
                ->orderByRaw('CASE WHEN id BETWEEN 44 AND 74 THEN id END DESC, id DESC')
                ->get();

        $totalSearchResults = count($genstud);

        $grades = [];

        foreach ($genstud as $dataItem) {
            $grades[$dataItem->subjectID] = Grade::where('subjID', $dataItem->subjectID)->get();
        }

        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.gradesheet.view_search', compact('sy', 'genstud', 'totalSearchResults', 'grdCode', 'grade', 'grades', 'gradereg'));
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

    public function registrarsave_grades(Request $request)
    {
        $id = $request->id;
        $grade = $request->grade;

        $gradecheck = Grade::find($id);

        $gradeup = Grade::join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
            ->where('studgrades.id', $id)
            ->update([
                'studgrades.subjFgrade' => $grade,
                'studgrades.status' => (empty($grade) || $grade === '') ? null : 1,
                'studgrades.creditEarned' => (empty($grade) || in_array($grade, ['INC', 'NN', 'NG', 'Drp.'])) ? 0 : \DB::raw('coasv2_db_schedule.sub_offered.subUnit'),
            ]);

        if($gradeup){
            $gradeCount = Grade::where('subjID', $gradecheck->subjID)
                ->where('status', '!=', '')
                ->count();
        }

        return response()->json(['success' => true, 'gradeCount' => $gradeCount]);
    }

    public function registrarsave_gradesComp(Request $request)
    {
        $id = $request->id;
        $grade = $request->grade;

        $gradecheck = Grade::find($id);

        $gradeup = Grade::join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
            ->where('studgrades.id', $id)
            ->update([
                'studgrades.subjComp' => $grade,
                'studgrades.compstat' => (empty($grade)) ? null : 1,
                'studgrades.creditEarned' => (empty($grade) || in_array($grade, ['INC', 'NN', 'NG', 'Drp.'])) ? 0 : \DB::raw('coasv2_db_schedule.sub_offered.subUnit'),
            ]);

        if($gradeup){
            $gradeCount = Grade::where('subjID', $gradecheck->subjID)
                ->where('status', '!=', '')
                ->count();
        }

        return response()->json(['success' => true, 'gradeCount' => $gradeCount]);
    }

    public function registrarupdateStatus_gradessubmit(Request $request, $subjID)
    {
        $guard = $this->getGuard();
        $user = Auth::guard($guard)->user();

        Grade::where('subjID', $subjID)
        ->where('status', 1)
        ->update(['status' => 2, 'postedBy' => $user->id,]);

        Grade::where('subjID', $subjID)
        ->where('subjFgrade', 'INC')
        ->where('compstat', 1)
        ->update(['compstat' => 2]);

        return redirect()->back()->with('success', 'Status updated successfully.');
    }

}
