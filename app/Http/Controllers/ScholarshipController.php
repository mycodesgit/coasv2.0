<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Storage;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\ScholarshipDB\Scholar;
use App\Models\ScholarshipDB\FSCode;
use App\Models\ScholarshipDB\ChedSch;
use App\Models\ScholarshipDB\UniSch;

class ScholarshipController extends Controller
{
    public function index()
    {
        return view('scholar.index');
    }

    public function scholarAdd()
    {
        $fscode = FSCode::all();
        return view('scholar.add.addScholar', compact('fscode'));
    }

    public function scholarCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'scholar_name' => 'required',
                'scholar_sponsor' => 'required',
                'scholar_category' => 'required',
                'fund_source' => 'required',
            ]);

            try {
                Scholar::create([
                    'scholar_name' => $request->input('scholar_name'),
                    'scholar_sponsor' => $request->input('scholar_sponsor'),
                    'scholar_category' => $request->input('scholar_category'),
                    'fund_source' => $request->input('fund_source'),
                ]);

                return redirect()->route('scholarAdd')->with('success', 'Scholarship Name stored successfully!');
            } catch (\Exception $e) {
                return redirect()->route('scholarAdd')->with('fail', 'Failed to store Scholarship!');
            }
        }
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

    public function scholarlist()
    {
        $data = Scholar::join('fscode', 'scholarship.fund_source', '=', 'fscode.id')
                ->join('chedscholarship', 'scholarship.chedcategory', '=', 'chedscholarship.id')
                ->join('universityscholar', 'scholarship.unicategory', '=', 'universityscholar.id')
                ->get();

        $totalSearchResults = count($data);

        return view('scholar.list.listall_scholar', compact('data', 'totalSearchResults'));
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
        return view('scholar.list.list_studscholar');
    }

    public function studscholar_searchRead(Request $request)
    {
        $campus = Auth::guard('web')->user()->campus;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        // $data = StudEnrolmentHistory::leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
        //         ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
        //         ->join('coasv2_db_scholarship.scholarship', 'program_en_history.studSch', '=', 'coasv2_db_scholarship.scholarship.id')
        //         ->join('coasv2_db_scholarship.chedscholarship', 'coasv2_db_scholarship.scholarship.chedcategory', '=', 'coasv2_db_scholarship.chedscholarship.id')
        //         ->join('coasv2_db_scholarship.universityscholar', 'coasv2_db_scholarship.scholarship.unicategory', '=', 'coasv2_db_scholarship.universityscholar.id')
        //         ->leftJoin('coasv2_db_assessment.student_appraisal', 'program_en_history.studentID', '=', 'coasv2_db_assessment.student_appraisal.studID')
        //         ->where('coasv2_db_assessment.student_appraisal.account', 'LIKE', '%TUITION%')
        //         ->where('program_en_history.schlyear', $schlyear)
        //         ->where('program_en_history.semester', $semester)
        //         ->where('program_en_history.campus', $campus)
        //         ->where('coasv2_db_assessment.student_appraisal.schlyear', $schlyear)
        //         ->where('coasv2_db_assessment.student_appraisal.semester', $semester)
        //         ->where('coasv2_db_assessment.student_appraisal.campus', $campus)
        //         ->select('coasv2_db_schedule.programs.progCod', 
        //                 'coasv2_db_schedule.programs.progName', 
        //                 'coasv2_db_schedule.programs.progAcronym', 
        //                 'program_en_history.studYear', 
        //                 'program_en_history.studYear', 
        //                 'program_en_history.studSec', 
        //                 'program_en_history.studentID', 
        //                 'students.lname', 
        //                 'students.fname', 
        //                 'program_en_history.studYear', 
        //                 'program_en_history.studSec', 
        //                 'coasv2_db_scholarship.scholarship.scholar_name', 
        //                 'coasv2_db_scholarship.scholarship.scholar_sponsor', 
        //                 'coasv2_db_scholarship.chedscholarship.chedsch_name', 
        //                 'coasv2_db_scholarship.universityscholar.unisch_name', 
        //                 'coasv2_db_assessment.student_appraisal.amount'
        //             )
        //         ->orderBy('students.lname', 'ASC')
        //         ->get();

        // $totalSearchResults = count($data);

        return view('scholar.list.listsearch_studscholar');
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
                        'students.lname', 
                        'students.fname', 
                        'program_en_history.studYear', 
                        'program_en_history.studSec', 
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
}
