<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\Grade;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\ScholarshipDB\Scholar;
use App\Models\ScholarshipDB\FSCode;
use App\Models\ScholarshipDB\ChedSch;
use App\Models\ScholarshipDB\UniSch;

use App\Models\AssessmentDB\StudentFee;
use App\Models\AssessmentDB\StudentAppraisal;

use App\Models\SettingDB\ConfigureCurrent;

class ScholarshipController extends Controller
{
    public function index()
    {
        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->subYears(1)->year;
        $userCampus = Auth::guard('web')->user()->campus;

        $collegesFirstSemester = College::join('coasv2_db_enrollment.program_en_history', function($join) {
                        $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
                    })
                    ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
                    ->where(function ($query) use ($userCampus) {
                        $campuses = explode(', ', $userCampus);
                        foreach ($campuses as $campus) {
                            $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                        }
                    })
                    ->where('coasv2_db_enrollment.program_en_history.semester', '=', 1)
                    ->where(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.schlyear, '-', -1)"), $currentYear)
                    ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
                    ->orderBy('college_name', 'ASC')
                    ->select('college.*', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
                    ->groupBy('college.id')
                    ->get();

        $collegesSecondSemester = College::join('coasv2_db_enrollment.program_en_history', function($join) {
                        $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
                    })
                    ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
                    ->where(function ($query) use ($userCampus) {
                        $campuses = explode(', ', $userCampus);
                        foreach ($campuses as $campus) {
                            $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                        }
                    })
                    ->where('coasv2_db_enrollment.program_en_history.semester', '=', 2)
                    ->where(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.schlyear, '-', -1)"), $currentYear)
                    ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
                    ->orderBy('college_name', 'ASC')
                    ->select('college.*', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
                    ->groupBy('college.id')
                    ->get();

        $schlyearactive = ConfigureCurrent::where('set_status', 2)->first()->schlyear;
        $semesteractive = ConfigureCurrent::where('set_status', 2)->first()->semester;

        $enrlstudcountfirst = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                            ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                            ->where('program_en_history.semester', 'LIKE', $semesteractive)
                            ->where('program_en_history.studYear', '=', '1')
                            ->where('program_en_history.campus', '=', $userCampus)
                            ->count();


        $enrlstudcountsecond = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                            ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                            ->where('program_en_history.semester', 'LIKE', $semesteractive)
                            ->where('program_en_history.studYear', '=', '2')
                            ->where('program_en_history.campus', '=', $userCampus)
                            ->count();

        $enrlstudcountthird = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                            ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                            ->where('program_en_history.semester', 'LIKE', $semesteractive)
                            ->where('program_en_history.studYear', '=', '3')
                            ->where('program_en_history.campus', '=', $userCampus)
                            ->count();

        $enrlstudcountfourth = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                            ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                            ->where('program_en_history.semester', 'LIKE', $semesteractive)
                            ->where('program_en_history.studYear', '=', '4')
                            ->where('program_en_history.campus', '=', $userCampus)
                            ->count();
        return view('scholar.index', compact('collegesFirstSemester', 'collegesSecondSemester', 'currentYear', 'previousYear', 'enrlstudcountfirst', 'enrlstudcountsecond', 'enrlstudcountthird', 'enrlstudcountfourth'));
    }

    public function chedscholarlist()
    {
        $schched = ChedSch::all();
        return view('scholar.list.listched_scholar', compact('schched'));
    }

    public function getchedscholarlist()
    {
        $data = ChedSch::all();

        return response()->json(['data' => $data]);
    }

    public function chedscholarCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'chedsch_name' => 'required',
            ]);

            try {
                ChedSch::create([
                    'chedsch_name' => $request->input('chedsch_name'),
                ]);
                return response()->json(['success' => true, 'message' => 'CHED Scholarship Store Successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store CHED Scholarship!'], 404);
            }
        }
    }

    public function chedscholarUpdate(Request $request) 
    {
        $chedsch = ChedSch::find($request->id);
        
        $request->validate([
            'id' => 'required',
            'chedsch_name' => 'required',
        ]);

        try {
            $chedschName = $request->input('chedsch_name');
            $existingChedName = ChedSch::where('chedsch_name', $chedschName)->where('id', '!=', $request->input('id'))->first();

            if ($existingChedName) {
                return response()->json(['error' => true, 'message' => 'CHED Scholarship already exists!'], 404);
            }

            $chedsch = ChedSch::findOrFail($request->input('id'));
            $chedsch->update([
                'chedsch_name' => $chedschName,
            ]);
            return response()->json(['success' => true, 'message' => 'CHED Scholarship Updated Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update CHED Scholarship!'], 404);
        }
    }

    public function chedscholarDelete($id) 
    {
        $chedsch = ChedSch::find($id);
        $chedsch->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }


    public function unischolarlist()
    {
        $schuni = UniSch::all();
        return view('scholar.list.listuni_scholar', compact('schuni'));
    }

    public function getunischolarlist()
    {
        $data = UniSch::all();

        return response()->json(['data' => $data]);
    }

    public function unischolarCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'unisch_name' => 'required',
            ]);

            try {
                UniSch::create([
                    'unisch_name' => $request->input('unisch_name'),
                ]);
                return response()->json(['success' => true, 'message' => 'CPSU Scholarship Store Successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store CPSU Scholarship!'], 404);
            }
        }
    }

    public function unischolarUpdate(Request $request) 
    {
        $unisch = UniSch::find($request->id);
        
        $request->validate([
            'id' => 'required',
            'unisch_name' => 'required',
        ]);

        try {
            $unischName = $request->input('unisch_name');
            $existingUniName = UniSch::where('unisch_name', $unischName)->where('id', '!=', $request->input('id'))->first();

            if ($existingUniName) {
                return response()->json(['error' => true, 'message' => 'CPSU Scholarship already exists!'], 404);
            }

            $unisch = UniSch::findOrFail($request->input('id'));
            $unisch->update([
                'unisch_name' => $unischName,
            ]);
            return response()->json(['success' => true, 'message' => 'CPSU Scholarship Updated Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update CPSU Scholarship!'], 404);
        }
    }

    public function unischolarDelete($id) 
    {
        $unisch = UniSch::find($id);
        $unisch->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }

    public function allscholarlist()
    {
        $ched = ChedSch::all();
        $uni =  UniSch::all();
        $fs =  FSCode::all();

        return view('scholar.list.listall_scholar', compact('ched', 'uni', 'fs'));
    }

    public function getallscholarlist()
    {
        $data = Scholar::join('fscode', 'scholarship.fund_source', '=', 'fscode.id')
            ->join('chedscholarship', 'scholarship.chedcategory', '=', 'chedscholarship.id')
            ->join('universityscholar', 'scholarship.unicategory', '=', 'universityscholar.id')
            ->select('scholarship.*', 'scholarship.id as allschid', 'universityscholar.*', 'universityscholar.id as unischid', 'chedscholarship.*', 'chedscholarship.id as  chedschid', 'fscode.*', 'fscode.id as fsschid')
            ->orderBy('scholarship.scholar_name', 'ASC')
            ->get();

        return response()->json(['data' => $data]);
    }

    public function allscholarCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'scholar_name' => 'required',
                'scholar_sponsor' => 'required',
                'chedcategory' => 'required',
                'unicategory' => 'required',
                'fund_source' => 'required',
            ]);

            $allschName = $request->input('scholar_name');
            $existingAllName = Scholar::where('scholar_name', $allschName)->first();

            if ($existingAllName) {
                return response()->json(['error' => true, 'message' => 'Scholarship already exists!'], 404);
            }

            try {
                Scholar::create([
                    'scholar_name' => $request->input('scholar_name'),
                    'scholar_sponsor' => $request->input('scholar_sponsor'),
                    'chedcategory' => $request->input('chedcategory'),
                    'unicategory' => $request->input('unicategory'),
                    'fund_source' => $request->input('fund_source'),
                ]);
                return response()->json(['success' => true, 'message' => 'Scholarship Store Successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Scholarship!'], 404);
            }
        }
    }

    public function allscholarUpdate(Request $request) 
    {   
        $request->validate([
            'id' => 'required',
            'scholar_name' => 'required',
            'scholar_sponsor' => 'required',
            'chedcategory' => 'required',
            'unicategory' => 'required',
            'fund_source' => 'required',
        ]);

        try {
            $allschName = $request->input('scholar_name');
            $existingAllName = Scholar::where('scholar_name', $allschName)->where('id', '!=', $request->input('id'))->first();

            if ($existingAllName) {
                return response()->json(['error' => true, 'message' => 'Scholarship already exists!'], 404);
            }

            $decryptedId = Crypt::decrypt($request->input('id'));
            $allsch = Scholar::findOrFail($decryptedId);
            $allsch->update([
                'scholar_name' => $request->input('scholar_name'),
                'scholar_sponsor' => $request->input('scholar_sponsor'),
                'chedcategory' => $request->input('chedcategory'),
                'unicategory' => $request->input('unicategory'),
                'fund_source' => $request->input('fund_source'),
            ]);
            return response()->json(['success' => true, 'message' => 'Scholarship Updated Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Scholarship!'], 404);
        }
    }







    public function chedscholarSearch()
    {
        $data = Scholar::join('fscode', 'scholarship.fund_source', '=', 'fscode.id')
                ->join('chedscholarship', 'scholarship.chedcategory', '=', 'chedscholarship.id')
                ->join('universityscholar', 'scholarship.unicategory', '=', 'universityscholar.id')
                ->get();

        $totalSearchResults = count($data);

        return view('scholar.list.listsearch_scholar', compact('data', 'totalSearchResults'));
    }

    public function chedstudscholarRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('scholar.list.list_studscholar', compact('sy'));
    }

    public function studscholar_searchRead(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $campus = Auth::guard('web')->user()->campus;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        $studsch = Scholar::all();

        return view('scholar.list.listsearch_studscholar', compact('studsch', 'sy'));
    }

    public function getstudscholarSearchRead(Request $request)
    {
        $campus = Auth::guard('web')->user()->campus;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        $data = StudEnrolmentHistory::leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->join('coasv2_db_scholarship.scholarship', 'program_en_history.studSch', '=', 'coasv2_db_scholarship.scholarship.id')
                ->join('coasv2_db_scholarship.chedscholarship', 'coasv2_db_scholarship.scholarship.chedcategory', '=', 'coasv2_db_scholarship.chedscholarship.id')
                ->join('coasv2_db_scholarship.universityscholar', 'coasv2_db_scholarship.scholarship.unicategory', '=', 'coasv2_db_scholarship.universityscholar.id')
                ->leftJoin('coasv2_db_assessment.student_appraisal', 'program_en_history.studentID', '=', 'coasv2_db_assessment.student_appraisal.studID')
                ->where('coasv2_db_assessment.student_appraisal.account', 'LIKE', '%TUITION%')
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', $semester)
                ->where('program_en_history.campus', $campus)
                ->where('coasv2_db_assessment.student_appraisal.schlyear', $schlyear)
                ->where('coasv2_db_assessment.student_appraisal.semester', $semester)
                ->where('coasv2_db_assessment.student_appraisal.campus', $campus)
                ->select('coasv2_db_schedule.programs.progCod', 
                        'coasv2_db_schedule.programs.progName', 
                        'coasv2_db_schedule.programs.progAcronym', 
                        'program_en_history.studYear', 
                        'program_en_history.studYear', 
                        'program_en_history.studSec', 
                        'program_en_history.studentID',
                        'program_en_history.id', 
                        'students.lname', 
                        'students.fname', 
                        'program_en_history.studYear', 
                        'program_en_history.studSec', 
                        'coasv2_db_scholarship.scholarship.id as schid',
                        'coasv2_db_scholarship.scholarship.scholar_name', 
                        'coasv2_db_scholarship.scholarship.scholar_sponsor', 
                        'coasv2_db_scholarship.chedscholarship.chedsch_name', 
                        'coasv2_db_scholarship.universityscholar.unisch_name', 
                        'coasv2_db_assessment.student_appraisal.amount'
                    )
                ->orderBy('students.lname', 'ASC')
                ->get();

        //$totalSearchResults = count($data);

        return response()->json(['data' => $data]);
    }

    public function studscholarUpdate(Request $request) 
    {   
        $request->validate([
            'id' => 'required',
            'studSch' => 'required',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->input('id'));
            $studsch = StudEnrolmentHistory::find($decryptedId);
            $studsch->update([
                'studSch' => $request->input('studSch'),
            ]);
            return response()->json(['success' => true, 'message' => 'Student Scholarship Updated Successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Student Scholarship!'], 404);
        }
    }

    public function studEnHistory() 
    {
        return view('scholar.enrolhis.list_enrhis');
    }

    public function viewsearchStudHistory(Request $request) 
    {
        $query = $request->input('query');
        $campus = Auth::guard('web')->user()->campus;

        $results = Student::where('lname', 'like', '%' . $query . '%')
                        ->orWhere('stud_id', $query)
                        ->where('campus', $campus)
                        ->get();

        if (count($results) > 0) {    
            return view('scholar.enrolhis.listsearch_enrhis', compact('results'));
        }
        return redirect()->route('studEnHistory')->with('error', 'No results found for the search.');
    }

    public function searchStudHistory(Request $request)
    {
        $query = $request->input('query'); 
        $campus = Auth::guard('web')->user()->campus;
        $results = Student::where('lname', 'like', '%' . $query . '%')
                        ->orWhere('stud_id', $query)
                        ->where('campus', $campus)
                        ->get();

        return response()->json(['data' => $results]);
    }
    public function fetchEnrollmentHistory(Request $request)
    {
        $stud_id = $request->input('stud_id');

        $enrollmentHistory = StudEnrolmentHistory::join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('studentID', $stud_id)
            ->select('program_en_history.*', 'coasv2_db_schedule.programs.progAcronym')
            ->get();

        return response()->json(['data' => $enrollmentHistory]);
    }

    public function countstudnoenrollee() 
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('scholar.numenroll.listnum_enroll', compact('sy'));
    }

    public function studregformRead()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('scholar.numenroll.studregform', compact('sy'));
    }

    public function listsearch_studregformRead(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $student = Student::where('stud_id', $stud_id)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }
        $programEnHistory = StudEnrolmentHistory::where('studentID', $stud_id)
                ->where('schlyear', $schlyear)
                ->where('semester', '=', $semester)
                ->first(); 

        if (!$programEnHistory) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> not enrolled at this term or school year.');
        }

        $student = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                    ->join('coasv2_db_scholarship.scholarship', 'program_en_history.studSch', '=', 'coasv2_db_scholarship.scholarship.id')
                    ->select('students.*', 'program_en_history.*', 'coasv2_db_scholarship.scholarship.*')
                    ->where('program_en_history.schlyear',  $schlyear)
                    ->where('program_en_history.semester',  $semester)
                    ->where('program_en_history.campus',  $campus)
                    ->where('program_en_history.studentID', $stud_id)->first();

        $studsub = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('coasv2_db_schedule.sub_offered.schlyear',  $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester',  $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus',  $campus)
                    ->where('studgrades.studID', $stud_id)
                    ->orderBy('coasv2_db_schedule.sub_offered.subCode', 'ASC')
                    ->get();

        $studfees = StudentAppraisal::select('student_appraisal.*')
                    ->where('student_appraisal.schlyear',  $schlyear)
                    ->where('student_appraisal.semester',  $semester)
                    ->where('student_appraisal.campus',  $campus)
                    ->where('student_appraisal.studID', $stud_id)
                    ->orderBy('student_appraisal.account', 'ASC')
                    ->get();

        return view('scholar.numenroll.studregform_search', compact('sy'));
    }

    public function listsearchpdf_studregformRead(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $student = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                    ->join('coasv2_db_scholarship.scholarship', 'program_en_history.studSch', '=', 'coasv2_db_scholarship.scholarship.id')
                    ->select('students.*', 'program_en_history.*', 'coasv2_db_scholarship.scholarship.*')
                    ->where('program_en_history.schlyear',  $schlyear)
                    ->where('program_en_history.semester',  $semester)
                    ->where('program_en_history.campus',  $campus)
                    ->where('program_en_history.studentID', $stud_id)->first();

        $studsub = Grade::leftJoin('coasv2_db_schedule.sub_offered', 'studgrades.subjID', '=', 'coasv2_db_schedule.sub_offered.id')
                    ->leftJoin('coasv2_db_schedule.subjects', 'coasv2_db_schedule.sub_offered.subCode', '=', 'coasv2_db_schedule.subjects.sub_code')
                    ->select( 'studgrades.*', 'coasv2_db_schedule.sub_offered.*', 'coasv2_db_schedule.subjects.*')
                    ->where('coasv2_db_schedule.sub_offered.schlyear',  $schlyear)
                    ->where('coasv2_db_schedule.sub_offered.semester',  $semester)
                    ->where('coasv2_db_schedule.sub_offered.campus',  $campus)
                    ->where('studgrades.studID', $stud_id)
                    ->orderBy('coasv2_db_schedule.sub_offered.subCode', 'ASC')
                    ->get();

        $studfees = StudentAppraisal::select('student_appraisal.*')
                    ->where('student_appraisal.schlyear',  $schlyear)
                    ->where('student_appraisal.semester',  $semester)
                    ->where('student_appraisal.campus',  $campus)
                    ->where('student_appraisal.studID', $stud_id)
                    ->orderBy('student_appraisal.account', 'ASC')
                    ->get();

        $data = [
            'student' => $student,
            'studsub' => $studsub,
            'studfees' => $studfees
        ];
        $pdf = PDF::loadView('enrollment.studenroll.pdfrf.studRF', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();

    }

}
