<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;

class EnSubjectsController extends Controller
{
    public function subjectsRead() 
    {
        return view('enrollment.subject.sublist');
    }

    public function getsubjectsRead() 
    {
        $data = Subject::all();
        return response()->json(['data' => $data]);
    }
}
