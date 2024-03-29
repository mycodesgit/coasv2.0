<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


use Storage;
use Carbon\Carbon;
use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\AssessmentDB\AccountAppraisal;

class SchedSubOfferController extends Controller
{
    public function subjectsOffered() 
    {
        return view('scheduler.subOff.list_subOff');
    }

    public function subjectsOffered_search(Request $request) 
    {
        $campus = Auth::guard('web')->user()->campus;
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');

        $campus = is_array($campus) ? $campus : [$campus];
        $schlyear = is_array($schlyear) ? $schlyear : [$schlyear];
        $semester = is_array($semester) ? $semester : [$semester];

        $data = SubjectOffered::select('sub_offered.*', 'subjects.*')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->whereIn('sub_offered.schlyear', $schlyear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->whereIn('sub_offered.campus', $campus)
                        ->get();

        $totalSearchResults = count($data);

        $subjects = Subject::all();

        $class = ClassEnroll::join('programs', 'class_enroll.progCode', '=', 'programs.progCod')
                ->select('class_enroll.*', 'programs.*' )
                ->where('class_enroll.schlyear', $schlyear)
                ->where('class_enroll.semester', $semester)
                ->where('class_enroll.campus', $campus)
                ->orderBy('programs.progAcronym', 'ASC')
                ->orderBy('class_enroll.classSection', 'ASC')
                ->get();

        $funds = AccountAppraisal::whereIn('id', [85, 105])->get();

        return view('scheduler.subOff.listsearch_subOff', compact('data', 'totalSearchResults', 'subjects', 'class', 'funds'));
    }

    public function getsubjectsOfferedRead(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
    
        $data = SubjectOffered::select('sub_offered.*', 'subjects.*')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->select('sub_offered.*', 'subjects.*', 'sub_offered.id as soid')
                        ->where('sub_offered.schlyear', $schlyear)
                        ->where('sub_offered.semester', $semester)
                        ->where('sub_offered.campus', $campus)
                        ->get();

        return response()->json(['data' => $data]);
    }

    public function fetchSubjectName(Request $request)
    {
        $subCode = $request->input('subCode');

        $subjectName = SubjectOffered::where('sub_offered.subCode', $subCode)
            ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
            ->select('subjects.sub_name', 'subjects.sub_title')
            ->first();
            
        return response()->json($subjectName);
    }

    public function subjectsOfferedCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'schlyear' => 'required',
                'semester' => 'required',
                'campus' => 'required',
                'postedBy' => 'required',
                'datePosted' => 'required',
                'subCode' => 'required',
                'lecUnit' => 'required',
                'labUnit' => 'required',
                'subUnit' => 'required',
                'subSec' => 'required',
                'lecFee' => 'required',
                'labFee' => 'required',
                'maxstud' => 'required',
                'isTemp' => 'required',
                'isOJT' => 'required',
                'isType' => 'required',
            ]);

            $schlyear = $request->input('schlyear');
            $semester = $request->input('semester');
            $campus = $request->input('campus');
            $subSec = $request->input('subSec');
            $subCode = $request->input('subCode');

            $existingSubjectOff = SubjectOffered::where('schlyear', $schlyear)
                    ->where('semester', $semester)
                    ->where('campus', $campus)
                    ->where('subCode', $subCode)
                    ->where('subSec', $subSec)
                    ->first();

            if ($existingSubjectOff) {
                return response()->json(['error' => true, 'message' => 'Subject already exists'], 404);
            }

            try {
                SubjectOffered::create([
                    'schlyear' => $request->input('schlyear'),
                    'semester' => $request->input('semester'),
                    'campus' => $request->input('campus'),
                    'postedBy' => $request->input('postedBy'),
                    'datePosted' => $request->input('datePosted'),
                    'subCode' => $request->input('subCode'),
                    'lecUnit' => $request->input('lecUnit'),
                    'labUnit' => $request->input('labUnit'),
                    'subUnit' => $request->input('subUnit'),
                    'subSec' => $request->input('subSec'),
                    'lecFee' => $request->input('lecFee'),
                    'labFee' => $request->input('labFee'),
                    'maxstud' => $request->input('maxstud'),
                    'isTemp' => $request->input('isTemp'),
                    'isOJT' => $request->input('isOJT'),
                    'isType' => $request->input('isType'),
                    'fund' => $request->input('fund'),
                    'fundAccount' => $request->input('fundAccount'),
                ]);

                return response()->json(['success' => true, 'message' => 'Subject stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Subject'], 404);
            }
        }
    }

    public function subjectsOfferedUpdate(Request $request) 
    {
        $request->validate([
            'postedBy' => 'required',
            'datePosted' => 'required',
            'subCode' => 'required',
            'lecUnit' => 'required',
            'labUnit' => 'required',
            'subUnit' => 'required',
            'subSec' => 'required',
            'lecFee' => 'required',
            'labFee' => 'required',
            'maxstud' => 'required',
            'isTemp' => 'required',
            'isOJT' => 'required',
            'isType' => 'required',
        ]);

        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = $request->input('campus');
        $subSec = $request->input('subSec');
        $subCode = $request->input('subCode');

        try {
            $existingSubjectOff = SubjectOffered::where('schlyear', $schlyear)
                            ->where('semester', $semester)
                            ->where('campus', $campus)
                            ->where('subCode', $subCode)
                            ->where('subSec', $subSec)
                            ->where('id', '!=', $request->input('id'))->first();

            if ($existingSubjectOff) {
                return response()->json(['error' => true, 'message' => 'Subject already exists'], 404);
            }

            $studSubOffer = SubjectOffered::findOrFail($request->input('id'));
            $studSubOffer->update([
                'postedBy' => $request->input('postedBy'),
                'datePosted' => $request->input('datePosted'),
                'subCode' => $request->input('subCode'),
                'lecUnit' => $request->input('lecUnit'),
                'labUnit' => $request->input('labUnit'),
                'subUnit' => $request->input('subUnit'),
                'subSec' => $request->input('subSec'),
                'lecFee' => $request->input('lecFee'),
                'labFee' => $request->input('labFee'),
                'maxstud' => $request->input('maxstud'),
                'isTemp' => $request->input('isTemp'),
                'isOJT' => $request->input('isOJT'),
                'isType' => $request->input('isType'),
                'fund' => $request->input('fund'),
                'fundAccount' => $request->input('fundAccount'),
        ]);
            return response()->json(['success' => true, 'message' => 'Subject Offer Updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Subject Offer'], 404);
        }
    }
}
