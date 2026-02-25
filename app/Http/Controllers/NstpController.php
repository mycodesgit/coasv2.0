<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Rules\UniqueStudentID;
use Illuminate\Support\Facades\Log;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\AdmissionDB\User;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\EncodedGrade;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\DeleteEnrollmentLogs;
use App\Models\EnrollmentDB\StudHisLog;
use App\Models\EnrollmentDB\StudSubLog;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

use App\Models\ScholarshipDB\Scholar;

use App\Models\AssessmentDB\StudentFee;
use App\Models\AssessmentDB\StudentAppraisal;
use App\Models\AssessmentDB\StudPayment;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\ButtonAccess;

class NstpController extends Controller
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
        $activeConfig = ConfigureCurrent::where('set_status', 2)->first();
        $schlyearactive = $activeConfig->schlyear;
        $semesteractive = $activeConfig->semester;
        $campus = Auth::guard('web')->user()->campus;

        $cwtscodes = ["KAB-SER-076", "KAB-SER-144", "KAB-SER-147"];
        $ltscodes = ["KAB-SER-145", "KAB-SER-148"];
        $rotccodes = ["KAB-SER-146", "KAB-SER-149", "KAB-SER-077"];

        // ⚡ Fast counts
        $cwtscount = $this->countNSTPStudentsFast($cwtscodes, $schlyearactive, $semesteractive, $campus);
        $ltscount  = $this->countNSTPStudentsFast($ltscodes,  $schlyearactive, $semesteractive, $campus);
        $rotccount = $this->countNSTPStudentsFast($rotccodes, $schlyearactive, $semesteractive, $campus);

        return view('nstpcwtsltsrotc.index', compact('schlyearactive', 'semesteractive', 'cwtscount', 'ltscount', 'rotccount'));
    }

    private function countNSTPStudentsFast(array $subjectCodes, $schlyear, $semester, $campus)
    {
        return Grade::whereIn('subjID', function ($query) use ($subjectCodes, $schlyear, $semester, $campus) {
                $query->select('coasv2_db_schedule.sub_offered.id')
                    ->from('coasv2_db_schedule.sub_offered')
                    ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester', $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus', $campus)
                    ->whereIn('coasv2_db_schedule.sub_offered.subCode', $subjectCodes);
            })
            ->whereExists(function ($q) use ($schlyear, $semester) {
                $q->selectRaw(1)
                ->from('program_en_history')
                ->whereColumn('program_en_history.studentID', 'studgrades.studID')
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', $semester);
            })
            ->count();
    }
    
    public function cwts_nstp()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('nstpcwtsltsrotc.listcwts', compact('sy'));
    }

    public function cwts_nstpresult(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
        
        $cwtscodes = [
            "KAB-SER-076", "KAB-SER-144"
        ];

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('web')->user()->campus;

        $data = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->where('sub_offered.schlyear', $schlyear)
            ->where('sub_offered.semester', $semester)
            ->where('sub_offered.campus', $campus)
            ->whereIn('sub_offered.subCode', $cwtscodes)
            ->select(
                'subjects.sub_name',
                'subjects.sub_title',
                'sub_offered.*',
                'sub_offered.id as sid',
            )
            ->get();

        $subjectIDs = $data->pluck('sid')->toArray();

        $substudnowviewpdf = Grade::select(
                'so.*', 
                'studgrades.*', 
                'studgrades.id as sgid', 
                'studgrades.status as gstat', 
                'students.*', 
                's.*'
            )
            ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
            ->join('students', 'studgrades.studID', '=', 'students.stud_id')
            ->leftJoin('coasv2_db_schedule.sub_offered as so2', 'studgrades.subjID', '=', 'so2.id')
            ->leftJoin('coasv2_db_schedule.subjects as s', 'so2.subCode', '=', 's.sub_code')
            ->where('so.schlyear', $schlyear)
            ->where('so.semester', $semester)
            ->whereIn('studgrades.subjID', $subjectIDs)
            ->orderBy('students.lname', 'ASC')
            ->get();


        return view('nstpcwtsltsrotc.listcwtsresult', compact('sy', 'substudnowviewpdf'));
    }

    public function getcwtsnstpresult(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
        
        $cwtscodes = [
            "KAB-SER-076", "KAB-SER-144", "KAB-SER-147"
        ];

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('web')->user()->campus;

        $data = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->where('sub_offered.schlyear', $schlyear)
            ->where('sub_offered.semester', $semester)
            ->where('sub_offered.campus', $campus)
            ->whereIn('sub_offered.subCode', $cwtscodes)
            ->select(
                'subjects.sub_name',
                'subjects.sub_title',
                'sub_offered.*',
                'sub_offered.id as sid',
            )
            ->get();

        $subjectIDs = $data->pluck('sid')->toArray();

        $data = Grade::select(
                'so.*', 
                'studgrades.*', 
                'studgrades.studID', 
                'studgrades.id as sgid', 
                'studgrades.status as gstat', 
                'students.lname', 
                'students.fname', 
                'students.ext', 
                'students.mname', 
                'students.mname', 
                'students.bday', 
                'students.gender', 
                'students.region', 
                'students.brgy', 
                'students.city', 
                'students.province', 
                'students.course', 
                'students.email', 
                'students.contact', 
                's.*',
                'coasv2_db_schedule.programs.progAcronym',
                'coasv2_db_schedule.programs.progName'
            )
            ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
            ->join('students', 'studgrades.studID', '=', 'students.stud_id')
            ->leftJoin('coasv2_db_schedule.sub_offered as so2', 'studgrades.subjID', '=', 'so2.id')
            ->leftJoin('coasv2_db_schedule.subjects as s', 'so2.subCode', '=', 's.sub_code')
            ->leftJoin('program_en_history', 'studgrades.studID', '=', 'program_en_history.studentID')
            ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('so.schlyear', $schlyear)
            ->where('so.semester', $semester)
            ->where('program_en_history.semester', $semester)
            ->where('program_en_history.schlyear', $schlyear)
            ->whereIn('studgrades.subjID', $subjectIDs)
            ->orderBy('students.lname', 'ASC')
            ->get();


        return response()->json(['data' => $data]);
    }

    public function lts_nstp()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('nstpcwtsltsrotc.listlts', compact('sy'));
    }

    public function lts_nstpresult(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();


        return view('nstpcwtsltsrotc.listltsresult', compact('sy'));
    }

    public function getltsnstpresult(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
        
        $ltscodes = [
            "KAB-SER-145", 'KAB-SER-148'
        ];

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('web')->user()->campus;

        $data = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->where('sub_offered.schlyear', $schlyear)
            ->where('sub_offered.semester', $semester)
            ->where('sub_offered.campus', $campus)
            ->whereIn('sub_offered.subCode', $ltscodes)
            ->select(
                'subjects.sub_name',
                'subjects.sub_title',
                'sub_offered.*',
                'sub_offered.id as sid',
            )
            ->get();

        $subjectIDs = $data->pluck('sid')->toArray();

        $data = Grade::select(
                'so.*', 
                'studgrades.*', 
                'studgrades.studID', 
                'studgrades.id as sgid', 
                'studgrades.status as gstat', 
                'students.lname', 
                'students.fname', 
                'students.ext', 
                'students.mname', 
                'students.mname', 
                'students.bday', 
                'students.gender', 
                'students.region', 
                'students.brgy', 
                'students.city', 
                'students.province', 
                'students.course', 
                'students.email', 
                'students.contact', 
                's.*',
                'coasv2_db_schedule.programs.progAcronym',
                'coasv2_db_schedule.programs.progName'
            )
            ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
            ->join('students', 'studgrades.studID', '=', 'students.stud_id')
            ->leftJoin('coasv2_db_schedule.sub_offered as so2', 'studgrades.subjID', '=', 'so2.id')
            ->leftJoin('coasv2_db_schedule.subjects as s', 'so2.subCode', '=', 's.sub_code')
            ->leftJoin('program_en_history', 'studgrades.studID', '=', 'program_en_history.studentID')
            ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('so.schlyear', $schlyear)
            ->where('so.semester', $semester)
            ->where('program_en_history.semester', $semester)
            ->where('program_en_history.schlyear', $schlyear)
            ->whereIn('studgrades.subjID', $subjectIDs)
            ->orderBy('students.lname', 'ASC')
            ->get();


        return response()->json(['data' => $data]);
    }

    public function rotc_nstp()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('nstpcwtsltsrotc.listrotc', compact('sy'));
    }

    public function rotc_nstpresult(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();


        return view('nstpcwtsltsrotc.listrotcresult', compact('sy'));
    }

    public function getrotcnstpresult(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
        
        $rotccodes = [
            "KAB-SER-146", 'KAB-SER-149', 'KAB-SER-077'
        ];

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        $campus = Auth::guard('web')->user()->campus;

        $data = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->where('sub_offered.schlyear', $schlyear)
            ->where('sub_offered.semester', $semester)
            ->where('sub_offered.campus', $campus)
            ->whereIn('sub_offered.subCode', $rotccodes)
            ->select(
                'subjects.sub_name',
                'subjects.sub_title',
                'sub_offered.*',
                'sub_offered.id as sid',
            )
            ->get();

        $subjectIDs = $data->pluck('sid')->toArray();

        $data = Grade::select(
                'so.*', 
                'studgrades.*', 
                'studgrades.studID', 
                'studgrades.id as sgid', 
                'studgrades.status as gstat', 
                'students.lname', 
                'students.fname', 
                'students.ext', 
                'students.mname', 
                'students.mname', 
                'students.bday', 
                'students.gender', 
                'students.region', 
                'students.brgy', 
                'students.city', 
                'students.province', 
                'students.course', 
                'students.email', 
                'students.contact', 
                's.*',
                'coasv2_db_schedule.programs.progAcronym',
                'coasv2_db_schedule.programs.progName'
            )
            ->join('coasv2_db_schedule.sub_offered as so', 'studgrades.subjID', '=', 'so.id')
            ->join('students', 'studgrades.studID', '=', 'students.stud_id')
            ->leftJoin('coasv2_db_schedule.sub_offered as so2', 'studgrades.subjID', '=', 'so2.id')
            ->leftJoin('coasv2_db_schedule.subjects as s', 'so2.subCode', '=', 's.sub_code')
            ->leftJoin('program_en_history', 'studgrades.studID', '=', 'program_en_history.studentID')
            ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('so.schlyear', $schlyear)
            ->where('so.semester', $semester)
            ->where('program_en_history.semester', $semester)
            ->where('program_en_history.schlyear', $schlyear)
            ->whereIn('studgrades.subjID', $subjectIDs)
            ->orderBy('students.lname', 'ASC')
            ->get();


        return response()->json(['data' => $data]);
    }

    public function reports_nstp()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('nstpcwtsltsrotc.reports.listgen', compact('sy'));
    }

    public function reports_nstpresult(Request $request)
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
        if(Auth::guard('web')->user()->role == 0 || Auth::guard('web')->user()->lname == 'Arlos') {
            $campus = $request->query('campus');    
        } else {
            $campus = Auth::guard('web')->user()->campus;
        }

        $campusArray = array_map('trim', explode(',', $campus));

        $kabSubcodes = [
            "KAB-SER-076", "KAB-SER-077", "KAB-SER-144",
            "KAB-SER-145", "KAB-SER-146", "KAB-SER-147",
            "KAB-SER-148", "KAB-SER-149"
        ];

        $data = StudEnrolmentHistory::leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->join('studgrades', 'students.stud_id', '=', 'studgrades.studID') // Join studgrades
                ->join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id') // Join sub_offered
                ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code') // Join subjects
                ->whereIn('coasv2_db_schedule.sub_offered.subCode', $kabSubcodes) // Filter by target sub_code
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', $semester)
                ->where('program_en_history.studYear', '=', '1')
                ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyear)
                ->where('coasv2_db_schedule.sub_offered.semester', $semester)
                ->where('coasv2_db_schedule.sub_offered.campus', $campus)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('program_en_history.campus', 'LIKE', "$campus");
                    }
                })
                ->where('program_en_history.status', 2)
                ->select(
                    'program_en_history.studentID', 
                    'students.lname', 
                    'students.fname', 
                    'students.ext', 
                    'students.mname', 
                    'students.mname', 
                    'students.bday', 
                    'students.gender', 
                    'students.region', 
                    'students.brgy', 
                    'students.city', 
                    'students.province', 
                    'program_en_history.studlevel', 
                    'students.course', 
                    'students.email', 
                    'students.contact', 
                    'program_en_history.id', 
                    'program_en_history.schlyear', 
                    'program_en_history.semester',
                    'coasv2_db_schedule.subjects.sub_name')
                ->limit(10)
                ->get();

        return view('nstpcwtsltsrotc.reports.listgenresult', compact('sy', 'data'));
    }

    public function getreportsnstpresult(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        if(Auth::guard('web')->user()->role == 0) {
            $campus = $request->query('campus');    
        } else {
            $campus = Auth::guard('web')->user()->campus;
        }

        $campusArray = array_map('trim', explode(',', $campus));

        $kabSubcodes = [
            "KAB-SER-076", "KAB-SER-077", "KAB-SER-144",
            "KAB-SER-145", "KAB-SER-146", "KAB-SER-147",
            "KAB-SER-148", "KAB-SER-149"
        ];

        $data = StudEnrolmentHistory::leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->join('studgrades', 'students.stud_id', '=', 'studgrades.studID') // Join studgrades
                ->join('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id') // Join sub_offered
                ->join('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code') // Join subjects
                ->whereIn('coasv2_db_schedule.sub_offered.subCode', $kabSubcodes) // Filter by target sub_code
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', $semester)
                ->where('program_en_history.studYear', '=', '1')
                ->where('coasv2_db_schedule.sub_offered.schlyear', $schlyear)
                ->where('coasv2_db_schedule.sub_offered.semester', $semester)
                ->where('coasv2_db_schedule.sub_offered.campus', $campus)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('program_en_history.campus', 'LIKE', "$campus");
                    }
                })
                ->where('program_en_history.status', 2)
                ->select(
                    'program_en_history.studentID', 
                    'students.lname', 
                    'students.fname', 
                    'students.ext', 
                    'students.mname', 
                    'students.mname', 
                    'students.bday', 
                    'students.gender', 
                    'students.region', 
                    'students.brgy', 
                    'students.city', 
                    'students.province', 
                    'program_en_history.studlevel', 
                    'students.course', 
                    'students.email', 
                    'students.contact', 
                    'program_en_history.id', 
                    'program_en_history.schlyear', 
                    'program_en_history.semester',
                    'coasv2_db_schedule.subjects.sub_name')
                // ->limit(10)
                ->get();

        return response()->json(['data' => $data]);
    }

    public function gradenstp()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('nstpcwtsltsrotc.gradenstp', compact('sy'));
    }

    public function gradenstp_searchlist(Request $request)
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

        return view('nstpcwtsltsrotc.gradenstp_search', compact('sy', 'data', 'totalSearchResults', 'grdCode'));
    }

    public function gradenstp_searchlistajax(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $nstpsubcodes = [
            "KAB-SER-076", "KAB-SER-077", "KAB-SER-144",
            "KAB-SER-145", "KAB-SER-146", "KAB-SER-147",
            "KAB-SER-148", "KAB-SER-149"
        ];

        $data = SubjectOffered::select('sub_offered.*', 'subjects.*', 'sub_offered.id as sid')
                        ->join('subjects', 'sub_offered.subcode', '=', 'subjects.sub_code')
                        ->where('sub_offered.schlyear', $schlyear)
                        ->where('sub_offered.semester', $semester)
                        ->where('sub_offered.campus', $campus)
                        ->whereIn('sub_offered.subCode', $nstpsubcodes)
                        ->where('subjects.subjdep', 'NOT LIKE', '%GSS')
                        ->get();

        // $grdCode = GradeCode::all();
        // $totalSearchResults = count($data);

        return response()->json(['data' => $data]);
    }

    public function gradenstpview(Request $request, $id)
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
                ->where('studgrades.campus', $campus)
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

        return view('nstpcwtsltsrotc.gradenstp_viewsearch', compact('sy', 'genstud', 'totalSearchResults', 'grdCode', 'grade', 'grades', 'gradereg'));
    }

    public function nstpsave_grades(Request $request)
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

    public function nstpsave_gradesComp(Request $request)
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

    public function nstpupdateStatus_gradessubmit(Request $request, $subjID)
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
}
