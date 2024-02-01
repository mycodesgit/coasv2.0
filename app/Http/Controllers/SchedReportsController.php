<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;
use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\FacultyLoad;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;

use App\Models\EnrollmentDB\Grade;

class SchedReportsController extends Controller
{
    public function subjectsRead() 
    {
        $subject = Subject::all();
        return view('scheduler.reports.subjects', compact('subject'));
    }

    public function facultyloadRead() 
    {
        $fac = Faculty::all();
        return view('scheduler.reports.facultyload', compact('fac'));
    }

    public function facultyload_search(Request $request) 
    {
        $fac = Faculty::all();

        $syear = $request->query('syear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');
        $facID = $request->query('facID');

        $syear = is_array($syear) ? $syear : [$syear];
        $semester = is_array($semester) ? $semester : [$semester];
        $campus = is_array($campus) ? $campus : [$campus];
        $facID = is_array($facID) ? $facID : [$facID];

        $datar = FacultyLoad::select('facultyload.*', 'sub_offered.*', 'subjects.*')
                        ->join('sub_offered', 'facultyload.subjectID', '=', 'sub_offered.id')
                        ->leftJoin('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->whereIn('sub_offered.syear', $syear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->whereIn('sub_offered.campus', $campus)
                        ->whereIn('facultyload.facultyID', $facID)
                        ->get();

        $totalSearchResults = count($datar);

        $grades = [];

        foreach ($datar as $dataItem) {
            $grades[$dataItem->subjectID] = Grade::where('subjID', $dataItem->subjectID)->get();
        }

        return view('scheduler.reports.facultyload_listsearch', compact('fac', 'datar', 'grades', 'totalSearchResults'));
    }
}
