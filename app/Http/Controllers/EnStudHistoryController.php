<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

use Storage;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;

class EnStudHistoryController extends Controller
{
    public function studentEnHistory() 
    {
        return view('enrollment.enrolhis.list_enrolhis');
    }

    public function viewsearchenStudHistory(Request $request) 
    {
        $query = $request->input('query');
        $campus = Auth::guard('web')->user()->campus;

        $results = Student::where('lname', 'like', '%' . $query . '%')
                        ->orWhere('stud_id', $query)
                        ->where('campus', $campus)
                        ->get();

        if (count($results) > 0) {    
            return view('enrollment.enrolhis.listsearch_enrolhis', compact('results'));
        }
        return redirect()->back()->with('error', 'No results found for the search.');
    }

    public function searchenStudHistory(Request $request)
    {
        $query = $request->input('query'); 
        $campus = Auth::guard('web')->user()->campus;
        $results = Student::where('lname', 'like', '%' . $query . '%')
                        ->orWhere('stud_id', $query)
                        ->where('campus', $campus)
                        ->get();

        return response()->json(['data' => $results]);
    }
    public function fetchStudEnrollmentHistory(Request $request)
    {
        $stud_id = $request->input('stud_id');

        $enrollmentHistory = StudEnrolmentHistory::join('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('studentID', $stud_id)
            ->select('program_en_history.*', 'coasv2_db_schedule.programs.progAcronym')
            ->get();

        return response()->json(['data' => $enrollmentHistory]);
    }
}
