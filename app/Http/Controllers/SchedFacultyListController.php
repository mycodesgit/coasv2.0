<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\Faculty;

class SchedFacultyListController extends Controller
{
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
