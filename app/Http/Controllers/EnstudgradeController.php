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
use App\Models\EnrollmentDB\EncodedGrade;

use App\Models\AdmissionDB\Programs;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\GradePass;

class EnstudgradeController extends Controller
{
    public function getGuard()
    {
        if(\Auth::guard('web')->check()) {
            return 'web';
        } elseif(\Auth::guard('faculty')->check()) {
            return 'faculty';
        }
    }

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
            
        return view('enrollment.gradesheet.list_studgrade',  compact('sy'));
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


        $data = SubjectOffered::select('sub_offered.*', 'subjects.*', 'sub_offered.id as sid')
                        ->join('subjects', 'sub_offered.subcode', '=', 'subjects.sub_code')
                        ->where('sub_offered.schlyear', $schlyear)
                        ->where('sub_offered.semester', $semester)
                        ->where('sub_offered.campus', $campus)
                        ->where('subjects.subjdep', 'NOT LIKE', '%GSS')
                        ->get();

        // $grdCode = GradeCode::all();
        // $totalSearchResults = count($data);

        return response()->json(['data' => $data]);
    }

    public function studgradegrad_searchlistajax(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;


        $data = SubjectOffered::select('sub_offered.*', 'subjects.*', 'sub_offered.id as sid')
                        ->join('subjects', 'sub_offered.subcode', '=', 'subjects.sub_code')
                        ->where('sub_offered.schlyear', $schlyear)
                        ->where('sub_offered.semester', $semester)
                        ->where('sub_offered.campus', $campus)
                        ->where('subjects.subjdep', 'LIKE', '%GSS')
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
        $campus = Auth::guard('web')->user()->campus;

        $campusArray = array_map('trim', explode(',', $campus));

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
                ->where('so.campus', $campus)
                // ->where('studgrades.campus', $campus)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('studgrades.campus', 'LIKE', "%$campus%");
                    }
                })
                //->where('students.campus', $campus)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('students.campus', 'LIKE', "%$campus%");
                    }
                })
                ->where('studgrades.subjID', $id)
                ->orderBy('students.lname', 'ASC')
                ->get();

        // $genstud = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
        //             ->join('students', 'studgrades.studID', '=', 'students.stud_id')
        //             ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyear)
        //             ->where('coasv2_db_schedule.sub_offered.semester', $semester)
        //             ->get();

        if (Auth::guard('web')->user()->role == '15') {
            $grdpercentage = array_merge(range(2, 43), [76]);
        } elseif (Auth::guard('web')->user()->campus == 'MC' && in_array(Auth::guard('web')->user()->role, [0, 3, 4])) {
            $grdpercentage = array_merge([2, 3, 4, 6, 7, 8, 9, 10, 12, 13, 15, 16, 17, 19, 20, 21, 22, 23, 25, 26, 27, 28, 29, 31, 32], range(44, 80));
        } else {
            $grdpercentage = range(44, 80); 
        }
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

            EncodedGrade::create([
                'grdeprimID' => $gradecheck->id, 
                'studsID' => $gradecheck->studID, 
                'subjctsID' => $gradecheck->subjID,
                'datefgrade' => \Carbon\Carbon::now(), 
                'campus' => $gradecheck->campus, 
                'fgrade' => $grade,
                'encodedBy' => Auth::guard('web')->user()->fname . ' ' . Auth::guard('web')->user()->lname,
            ]);
        } else {
            $gradeCount = 0;
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

            EncodedGrade::where('grdeprimID', $gradecheck->id)
            ->update([
                'studsID' => $gradecheck->studID, 
                'subjctsID' => $gradecheck->subjID,
                'datecgrade' => \Carbon\Carbon::now(), 
                'campus' => $gradecheck->campus, 
                'cgrade' => $grade,
                'encodedBy' => Auth::guard('web')->user()->fname . ' ' . Auth::guard('web')->user()->lname,
            ]);
        } else {
            $gradeCount = 0;
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

        return redirect()->back()->with('success', 'Grades Submitted Successfully.');
    }

    public function editGrade(Request $request)
    {
        //$guard = $this->getGuard();
        $user = Auth::guard('web')->user();
        $status = $request->input('status');
        $id = $request->input('id');
        //$prmID = $request->input('status');
        $campus = Auth::guard('web')->user()->campus;

        Grade::where('id', $id)->where('campus', $campus)->update(['status' => $status]);

        return redirect()->back()->with('success', 'Now you can edit the grade.');
    }

    public function editCompletion(Request $request, $id)
    {
        // $guard = $this->getGuard();
        // $user = Auth::guard($guard)->user();
        $campus = Auth::guard('web')->user()->campus;

        Grade::where('id', $id)
        ->where('compstat', 2)
        ->where('campus', $campus)->update(['compstat' => 1]);

        return redirect()->back()->with('success', 'Now you can edit the grade.');
    }

    public function checkPassword(Request $request)
    {
        $campus = Auth::guard('web')->user()->campus;
        
        $password = $request->input('password');
        $gradePass = GradePass::where('campus', $campus)->first();

        if ($gradePass && $gradePass->gradeauthpass === $password) {
            return response()->json(['status' => 'success']);
        }

        return response()->json(['status' => 'fail']);
    }


    public function studgradecorrection_search()
    {   
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
            
        return view('enrollment.correctiongrades.searchgrades_correct',  compact('sy'));
    }

    public function studgradecorrection_resultsearch(Request $request)
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
        $totalSearchResults = count($data);

        return view('enrollment.correctiongrades.searchgrades_resultcorrect', compact('sy', 'data', 'totalSearchResults'));
    }

    public function geneStudentcorrectiongrades(Request $request, $id)
    {
        $id = $request->id;
        $grade = $request->grade;

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

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
                ->where('so.campus', $campus)
                ->where('studgrades.campus', $campus)
                ->where('students.campus', $campus)
                ->where('studgrades.subjID', $id)
                ->orderBy('students.lname', 'ASC')
                ->get();

        // $genstud = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
        //             ->join('students', 'studgrades.studID', '=', 'students.stud_id')
        //             ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyear)
        //             ->where('coasv2_db_schedule.sub_offered.semester', $semester)
        //             ->get();

        if(Auth::guard('web')->user()->role == '15') {
            $grdpercentage = array_merge(range(2, 43), [76]);
        } else {
            $grdpercentage = range(44, 80); 
        }
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

        return view('enrollment.correctiongrades.searchgrades_resultviewcorrect', compact('sy', 'genstud', 'totalSearchResults', 'grdCode', 'grade', 'grades', 'gradereg'));
    }

}
