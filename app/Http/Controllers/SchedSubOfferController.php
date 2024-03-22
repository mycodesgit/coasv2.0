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
        $syear = $request->query('syear');
        $semester = $request->query('semester');

        $campus = is_array($campus) ? $campus : [$campus];
        $syear = is_array($syear) ? $syear : [$syear];
        $semester = is_array($semester) ? $semester : [$semester];

        $data = SubjectOffered::select('sub_offered.*', 'subjects.*')
                        ->join('subjects', 'sub_offered.subcode', '=', 'subjects.sub_code')
                        ->whereIn('sub_offered.syear', $syear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->whereIn('sub_offered.campus', $campus)
                        ->get();
        $totalSearchResults = count($data);

        return view('scheduler.subOff.listsearch_subOff', compact('data', 'totalSearchResults'));
    }

    public function subjectsRead() 
    {
        $subject = Subject::all();
        return view('scheduler.reports.subjects', compact('subject'));
    }
}
