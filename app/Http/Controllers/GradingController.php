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
use App\Models\AdmissionDB\User;
use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\FacDesignation;
use App\Models\ScheduleDB\FacultyLoad;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;

use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\SettingDB\ConfigureCurrent;

class GradingController extends Controller
{
    public function getGuard()
    {
        if(\Auth::guard('web')->check()) {
            return 'web';
        } elseif(\Auth::guard('faculty')->check()) {
            return 'faculty';
        }
    }

    public function index()
    {
        $desiredIds = [1, 74, 75, 76, 77];
        $grdlegend = GradeCode::whereIn('id', $desiredIds)->get();
        return view('grading.index', compact('grdlegend'));
    }

    public function grades(Request $request)
    {
        $cursttngs = ConfigureCurrent::where('set_status', 2)->first();
        $desiredIds = [1, 74, 75, 76, 77];
        $grdlegend = GradeCode::whereIn('id', $desiredIds)->get();
        $guard= $this->getGuard();
        $user = Auth::guard($guard)->user(); 
        $fac = Faculty::all();

        if ($cursttngs) {
            $syear = $cursttngs->syear;
            $semester = $cursttngs->semester;
            $campus = "MC";
            $facID = $user->id;
        } else {

        }

        $syear = is_array($syear) ? $syear : [$syear];
        $semester = is_array($semester) ? $semester : [$semester];
        $campus = is_array($campus) ? $campus : [$campus];
        $facID = is_array($facID) ? $facID : [$facID];

        $datargrades = FacultyLoad::select('facultyload.*', 'sub_offered.*', 'subjects.*')
                        ->join('sub_offered', 'facultyload.subjectID', '=', 'sub_offered.id')
                        ->leftJoin('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->whereIn('sub_offered.syear', $syear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->whereIn('sub_offered.campus', $campus)
                        ->whereIn('facultyload.facultyID', $facID)
                        ->get();

        $totalSearchResults = count($datargrades);

        $grades = [];

        foreach ($datargrades as $dataItem) {
            $grades[$dataItem->subjectID] = Grade::where('subjID', $dataItem->subjectID)->get();
        }

        return view('grading.gradesheet.list', compact('fac', 'datargrades', 'grades', 'totalSearchResults', 'guard', 'cursttngs', 'grdlegend'));
    }

    public function gradesstud($subjID) 
    {
        //$userID = decrypt($id);
        $guard= $this->getGuard();
        $user = Auth::guard($guard)->user();

        $grade = Grade::where('subjID', $subjID)
                        ->where('status', '!=', '')
                        ->count();


        $cursttngs = ConfigureCurrent::where('set_status', 2)->first();
        $fac = Faculty::all();

        $desiredIds = [1, 74, 75, 76, 77];
        $grdlegend = GradeCode::whereIn('id', $desiredIds)->get();

        $syear = $cursttngs->syear;
        $semester = $cursttngs->semester;
        $facID = $user->id;

        $syear = is_array($syear) ? $syear : [$syear];
        $semester = is_array($semester) ? $semester : [$semester];
        $facID = is_array($facID) ? $facID : [$facID];

        $gradeviewData = FacultyLoad::select('facultyload.*', 'sub_offered.*', 'subjects.*', 'coasv2_db_enrollment.studgrades.*', 'coasv2_db_enrollment.studgrades.status as gstat', 'coasv2_db_enrollment.students.*', 'coasv2_db_enrollment.studgrades.id as sgid' )
                        ->join('sub_offered', 'facultyload.subjectID', '=', 'sub_offered.id')
                        ->leftJoin('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('coasv2_db_enrollment.studgrades', 'facultyload.subjectID', '=', 'coasv2_db_enrollment.studgrades.subjID')
                        ->leftJoin('coasv2_db_enrollment.students', 'coasv2_db_enrollment.studgrades.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                        ->whereIn('sub_offered.syear', $syear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->whereIn('facultyload.facultyID', $facID)
                        ->where('coasv2_db_enrollment.studgrades.subjID', $subjID)
                        ->orderBy('coasv2_db_enrollment.students.lname', 'ASC')
                        ->get();

        $grdpercentage = range(44, 78);

        $grdCode = GradeCode::whereIn('id', $grdpercentage)
                ->orderByRaw('CASE WHEN id BETWEEN 44 AND 74 THEN id END DESC, id DESC')
                ->get();

        $grdpercentageComp = range(44, 74);

        $grdCodeComp = GradeCode::whereIn('id', $grdpercentageComp)
                ->orderByRaw('CASE WHEN id BETWEEN 44 AND 74 THEN id END DESC, id DESC')
                ->get();


        $totalSearchResults = count($gradeviewData);

        return view('grading.gradesheet.viewstudgrade', compact('gradeviewData', 'cursttngs', 'grdCode', 'grdCodeComp', 'totalSearchResults', 'grade', 'grdlegend'));
    }

    public function gradesstud_search(Request $request)
    {
        $fac = Faculty::all();
        
        $syear = $request->query('syear');
        $semester = $request->query('semester');
        $campus = "MC";
        $facID = $request->query('facID');
       

        $syear = is_array($syear) ? $syear : [$syear];
        $semester = is_array($semester) ? $semester : [$semester];
        $facID = is_array($facID) ? $facID : [$facID];

        $datargrades1 = FacultyLoad::select('facultyload.*', 'sub_offered.*', 'subjects.*')
                        ->join('sub_offered', 'facultyload.subjectID', '=', 'sub_offered.id')
                        ->leftJoin('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->whereIn('sub_offered.syear', $syear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->whereIn('facultyload.facultyID', $facID)
                        ->get();

        $totalSearchResults = count($datargrades1);

        $grades = [];

        foreach ($datargrades1 as $dataItem) {
            $grades[$dataItem->subjectID] = Grade::where('subjID', $dataItem->subjectID)->get();
        }

        return view('grading.gradesheet.search_viewstudgrade', compact('fac', 'datargrades1', 'grades', 'totalSearchResults'));
    }

    public function save_grades(Request $request)
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

    public function save_gradesComp(Request $request)
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

    public function updateStatus_gradessubmit(Request $request, $subjID)
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

    public function PDFgradesheetnew($subjID) 
    {
        $guard= $this->getGuard();
        $user = Auth::guard($guard)->user();

        $grade = Grade::where('subjID', $subjID)
                        ->where('status', '!=', '')
                        ->count();


        $cursttngs = ConfigureCurrent::where('set_status', 2)->first();
        $fac = Faculty::all();

        $desiredIds = [1, 74, 75, 76, 77];
        $grdlegend = GradeCode::whereIn('id', $desiredIds)->get();

        $syear = $cursttngs->syear;
        $semester = $cursttngs->semester;
        $facID = $user->id;

        $syear = is_array($syear) ? $syear : [$syear];
        $semester = is_array($semester) ? $semester : [$semester];
        $facID = is_array($facID) ? $facID : [$facID];

        $gradeviewData = FacultyLoad::select('facultyload.*', 'sub_offered.*', 'subjects.*', 'coasv2_db_enrollment.studgrades.*', 'coasv2_db_enrollment.studgrades.status as gstat', 'coasv2_db_enrollment.students.*', 'coasv2_db_enrollment.studgrades.id as sgid' )
                        ->join('sub_offered', 'facultyload.subjectID', '=', 'sub_offered.id')
                        ->leftJoin('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('coasv2_db_enrollment.studgrades', 'facultyload.subjectID', '=', 'coasv2_db_enrollment.studgrades.subjID')
                        ->leftJoin('coasv2_db_enrollment.students', 'coasv2_db_enrollment.studgrades.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                        ->whereIn('sub_offered.syear', $syear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->whereIn('facultyload.facultyID', $facID)
                        ->where('coasv2_db_enrollment.studgrades.subjID', $subjID)
                        ->orderBy('coasv2_db_enrollment.students.lname', 'ASC')
                        ->get();
        $grdCode = GradeCode::all();

        //$dean = FacDesignation::join('faculty', 'fac_designation.facdept', 'faculty.dept')->where('facdept'. '=', Auth::guard('faculty')->user()->dept)->first();

        $data = [
            'cursttngs' => $cursttngs,
            'gradeviewData' => $gradeviewData,
            'grade' => $grade,
            'grdlegend' => $grdlegend,
            'grdCode' => $grdCode,
            //'dean' => $dean,
        ];

        $pdf = PDF::loadView('grading.gradesheet.gpdf.gnewtem',  $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
}
