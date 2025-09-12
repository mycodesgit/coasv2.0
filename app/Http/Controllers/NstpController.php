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
    public function index()
    {
        $activeConfig = ConfigureCurrent::where('set_status', 2)->first();
        $schlyearactive = $activeConfig->schlyear;
        $semesteractive = $activeConfig->semester;
        $campus = Auth::guard('web')->user()->campus;

        $cwtscodes = ["KAB-SER-076", "KAB-SER-144", "KAB-SER-147"];
        $ltscodes = ["KAB-SER-145", "KAB-SER-148"];
        $rotccodes = ["KAB-SER-146", "KAB-SER-149"];

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
        $campusArray = array_map('trim', explode(',', $campus));

        $data = SubjectOffered::join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->where('sub_offered.schlyear', $schlyear)
            ->where('sub_offered.semester', $semester)
            ->where('sub_offered.campus', $campusArray)
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
            "KAB-SER-146", 'KAB-SER-149'
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
}
