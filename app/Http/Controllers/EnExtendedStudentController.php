<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Rules\UniqueStudentID;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\AdmissionDB\User;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;

class EnExtendedStudentController extends Controller
{
    public function index()
    {
        return view('enrollment.reports.extended.listview');
    }
    
    public function show()
    {
        $campus = Auth::guard('web')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $data = Student::join('program_en_history', 'students.stud_id', '=', 'program_en_history.studentID')
            ->select(
                'students.id',
                'students.stud_id',
                'students.fname',
                'students.mname',
                'students.lname',
                'students.ext',
                'students.campus',
                DB::raw('COUNT(program_en_history.studentID) as history_count')
            )
            ->groupBy(
                'students.id',
                'students.stud_id',
                'students.fname',
                'students.mname',
                'students.lname',
                'students.ext',
                'students.campus',
            )
            ->havingRaw('COUNT(program_en_history.studentID) > 10')
            ->when(!empty($campusArray), function ($query) use ($campusArray) {
                        $query->where(function ($q) use ($campusArray) {
                            foreach ($campusArray as $camp) {
                                $q->orWhereRaw(
                                    "FIND_IN_SET(?, REPLACE(students.campus, ' ', ''))",
                                    [$camp]
                                );
                            }
                        });
                    })
            ->get();

        return response()->json(['data' => $data]);
    }
}
