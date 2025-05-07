<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\Department;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\SubjectAcademicType;
use App\Models\ScheduleDB\SubjectDeliveryMode;

use App\Models\EnrollmentDB\StudentLevel;

class SchedClassProgramsController extends Controller
{
    public function programsRead() 
    {
        $col = College::whereBetween('id', [2, 8])->get();
        $dept = Department::all();
        $lev = StudentLevel::all();
        $acad = SubjectAcademicType::all();
        $delv = SubjectDeliveryMode::all();

        return view('scheduler.programs.list_programs', compact('col', 'dept', 'lev', 'delv', 'acad'));
    }

    public function getprogramsRead() 
    {
        $data = EnPrograms::orderBy('progAcronym', 'ASC')->get();

        return response()->json(['data' => $data]);
    }
}
