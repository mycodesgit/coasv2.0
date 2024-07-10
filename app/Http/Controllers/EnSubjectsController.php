<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\Department;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\SubjectAcademicType;
use App\Models\ScheduleDB\SubjectDeliveryMode;

use App\Models\EnrollmentDB\StudentLevel;

class EnSubjectsController extends Controller
{
    public function subjectsRead() 
    {
        $col = College::whereBetween('id', [2, 8])->get();
        $dept = Department::all();
        $lev = StudentLevel::all();
        $acad = SubjectAcademicType::all();
        $delv = SubjectDeliveryMode::all();
        return view('enrollment.subject.sublist', compact('col', 'dept', 'lev', 'delv'));
    }

    public function getsubjectsRead() 
    {
        $data = Subject::all();
        return response()->json(['data' => $data]);
    }

    public function getNextSubjectNumber(Request $request)
    {
        $college_abbr = $request->input('college_abbr');
        $deptCod = $request->input('deptCod');

        $lastSubject = Subject::where('sub_code', 'like', "$college_abbr-$deptCod-%")
                              ->orderBy('sub_code', 'desc')
                              ->first();

        if ($lastSubject) {
            $lastNumber = intval(substr($lastSubject->sub_code, strrpos($lastSubject->sub_code, '-') + 1));
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        return response()->json(['nextNumber' => $nextNumber]);
    }
}
