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

class SchedCurriculumController extends Controller
{
    public function curRead()
    {
        return view('scheduler.curriculum.curlist');
    }
}
