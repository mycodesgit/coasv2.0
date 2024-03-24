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
                        ->join('subjects', 'sub_offered.subcode', '=', 'subjects.sub_code')
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

        return view('scheduler.subOff.listsearch_subOff', compact('data', 'totalSearchResults', 'subjects', 'class'));
    }

    public function getsubjectsOfferedRead(Request $request) 
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;
    
        $data = SubjectOffered::select('sub_offered.*', 'subjects.*')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->where('sub_offered.schlyear', $schlyear)
                        ->where('sub_offered.semester', $semester)
                        ->where('sub_offered.campus', $campus)
                        ->get();

        return response()->json(['data' => $data]);
    }

    // public function subjectsRead() 
    // {
    //     $subject = Subject::all();
    //     return view('scheduler.reports.subjects', compact('subject'));
    // }
}
