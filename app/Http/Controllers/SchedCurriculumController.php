<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PDF;

use Storage;
use Carbon\Carbon;
use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\Curriculum;

use App\Models\AssessmentDB\AccountAppraisal;

class SchedCurriculumController extends Controller
{
    public function curRead()
    {
        $program = EnPrograms::whereRaw("FIND_IN_SET(?, campus)", [Auth::guard('web')->user()->campus])
                    ->orderBy('progAcronym', 'ASC')
                    ->get();

        return view('scheduler.curriculum.curlist', compact('program'));
    }

    public function curRead_search(Request $request)
    {
        $semester = $request->query('semester');
        $progCod = $request->query('progCod');
        $campus = Auth::guard('web')->user()->campus;

        $program = EnPrograms::whereRaw("FIND_IN_SET(?, campus)", [Auth::guard('web')->user()->campus])
                    ->orderBy('progAcronym', 'ASC')
                    ->get();

        $curriculum = EnPrograms::whereRaw("FIND_IN_SET(?, campus)", [Auth::guard('web')->user()->campus])
                ->where('progCod', $progCod)
                ->get();

        $subjects = Subject::where('sub_code', 'not like', 'VIC%')->get();
        $funds = AccountAppraisal::whereIn('id', [85, 105])->get();

        return view('scheduler.curriculum.curlist_search', compact('progCod', 'program', 'curriculum', 'subjects', 'funds'));
    }

    public function show(Request $request) 
    {
        $semester = $request->query('semester');
        $progCod = $request->query('progCod');
    
        $data = Curriculum::join('subjects', 'curriculum.subCode', '=', 'subjects.sub_code')
                        ->join('programs', 'curriculum.progCode', '=', 'programs.progCod')
                        ->where('curriculum.semester', $semester)
                        ->where('curriculum.progCode', $progCod)
                        // ->where('curriculum.campus', $campus)
                        ->select(
                            'curriculum.*',
                            'subjects.sub_name',
                            'programs.progAcronym as progCode'
                        )
                        ->get();

        return response()->json(['data' => $data]);
    }

    public function store(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'semester' => 'required',
                'campus' => 'required',
                'subCode' => 'required',
                'lecUnit' => 'required',
                'labUnit' => 'required',
                'subUnit' => 'required',
                'lecFee' => 'required',
                'labFee' => 'required',
                'isTemp' => 'required',
                'isOJT' => 'required',
            ]);

            $progCod = $request->input('progCode');
            $semester = $request->input('semester');
            $campus = $request->input('campus');
            $subCode = $request->input('subCode');

            $existingSubjectOff = Curriculum::where('progCode', $progCod)
                    ->where('semester', $semester)
                    ->where('campus', $campus)
                    ->where('subCode', $subCode)
                    ->first();

            if ($existingSubjectOff) {
                return response()->json(['error' => true, 'message' => 'Subject already exists'], 404);
            }

            try {
                Curriculum::create([
                    'progCode' => $request->input('progCode'),
                    'yrlvl' => $request->input('yrlvl'),
                    'subSec' => $request->input('subSec'),
                    'semester' => $request->input('semester'),
                    'campus' => Auth::guard('web')->user()->campus,
                    'subCode' => $request->input('subCode'),
                    'lecUnit' => $request->input('lecUnit'),
                    'labUnit' => $request->input('labUnit'),
                    'subUnit' => $request->input('subUnit'),
                    'lecFee' => $request->input('lecFee'),
                    'labFee' => $request->input('labFee'),
                    'devFee' => $request->input('devFee'),
                    'isOJT' => $request->input('isOJT'),
                    'isTemp' => $request->input('isTemp'),
                    'fund' => $request->input('fund'),
                    'fundAccount' => $request->input('fundAccount'),
                    'itfee' => $request->input('itfee'),
                    'isType' => "No",
                    'postedBy' => Auth::guard('web')->user()->id,
                    'prerequisite' => $request->input('prerequisite'),
                ]);

                return response()->json(['success' => true, 'message' => 'Subject stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Subject'], 404);
            }
        }
    }

    public function currpdfview(Request $request)
    {
        $curr = Curriculum::join('programs', 'curriculum.progCode', '=', 'programs.progCod')
                    ->join('subjects', 'curriculum.subCode', '=', 'subjects.sub_code')
                    ->select(
                        'programs.progAcronym', 
                        'programs.progName', 
                        'subjects.sub_name', 
                        'subjects.sub_title', 
                        'subjects.sub_unit',
                        'curriculum.yrlvl',
                        'curriculum.semester'
                    )
                    ->orderBy('curriculum.yrlvl')
                    ->orderBy('curriculum.semester')
                    ->orderBy('subjects.sub_name')
                    ->get();
        $groupedCurr = $curr->groupBy(['yrlvl', 'semester']);
        $data = [
            'progAcronym' => $curr->first()->progAcronym ?? 'Unknown Program', // Get program name once
            'groupedCurr' => $groupedCurr,
            'curr' => $curr
        ];

        $pdf = PDF::loadView('scheduler.curriculum.curpdf', $data)->setPaper('Legal', 'portrait');
        return $pdf->stream();
    }
}
