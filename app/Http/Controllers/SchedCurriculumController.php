<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
}
