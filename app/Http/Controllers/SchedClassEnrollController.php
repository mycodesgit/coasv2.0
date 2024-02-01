<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


use Storage;
use Carbon\Carbon;
use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\FacultyLoad;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;

use App\Models\AdmissionDB\Programs;

class SchedClassEnrollController extends Controller
{
    public function index()
    {
        return view('scheduler.index');
    }

    public function courseEnroll_list() 
    {
        return view('scheduler.classenroll.list_classenroll');
    }

    public function courseEnroll_list_search(Request $request) {
        $program = Programs::all();
        $class = ClassEnroll::all();

        $data = ClassEnroll::query();
        if ($request->schlyear) {
            $data->where('schlyear', $request->schlyear);
        }
        if ($request->semester) {
            $data->where('semester', $request->semester);
        }
        if ($request->campus) {
            $data->where('campus', $request->campus);
        }
        $data = $data->get();

        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);

        return view('scheduler.classenroll.list_classenroll_search', compact('program', 'class', 'data', 'totalSearchResults'));
    }

    public function classEnrolledAdd(Request $request)
    {
        $program = Programs::all();
        $class = ClassEnroll::all();

        $schlyear = $request->input('schlyear');
        $semester = $request->input('semester');
        $campus = $request->input('campus');
        $class = $request->input('class');
        $class_section = $request->input('class_section');

        $existingRecord = ClassEnroll::where('class', $class)
            ->where('class_section', $class_section)
            ->first();

        if ($existingRecord) {
            return redirect()->back()->with('fail', 'The course and section already exists.');
        }

        $classEnroll = new ClassEnroll();
        $classEnroll->schlyear = $schlyear;
        $classEnroll->semester = $semester;
        $classEnroll->campus = $campus;
        $classEnroll->class = $class;
        $classEnroll->class_section = $class_section;

        $classEnroll->save();

        return redirect()->back()->with('success','The Course and Section successfully saved in this semester');
    }

    public function subjectsOffered() 
    {
        return view('scheduler.subOff.list_subOff');
    }

    public function subjectsOffered_search(Request $request) 
    {
        $syear = $request->query('syear');
        $semester = $request->query('semester');

        $syear = is_array($syear) ? $syear : [$syear];
        $semester = is_array($semester) ? $semester : [$semester];

        $data = SubjectOffered::select('sub_offered.*', 'subjects.*')
                        ->join('subjects', 'sub_offered.subcode', '=', 'subjects.sub_code')
                        ->whereIn('sub_offered.syear', $syear)
                        ->whereIn('sub_offered.semester', $semester)
                        ->get();
        $totalSearchResults = count($data);

        return view('scheduler.subOff.listsearch_subOff', compact('data', 'totalSearchResults'));
    }

    public function faculty_list() 
    {
        return view('scheduler.faculty.list_faculty');
    }

    public function faculty_listsearch(Request $request) {

        $fac = Faculty::query();
        if ($request->campus) {
            $fac->where('campus', $request->campus);
        }
        $fac = $fac->get();

        $request->session()->put('recent_search', $fac);
        $totalSearchResults = count($fac);

        return view('scheduler.faculty.listsearch_faculty', compact('fac', 'totalSearchResults'));
    }
}


