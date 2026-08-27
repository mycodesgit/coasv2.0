<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use Storage;
use Carbon\Carbon;

use App\Models\AdmissionDB\Year;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Room;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\SetClassSchedule;

use App\Models\SettingDB\ConfigureCurrent;

class SchedClassCollegeController extends Controller
{
    public function getGuard()
    {
        if(\Auth::guard('web')->check()) {
            return 'web';
        } elseif(\Auth::guard('faculty')->check()) {
            return 'faculty';
        }
    }

    // public function index()
    // {
    //     $guard= $this->getGuard();

    //     $colCount = College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])->count();
    //     $enunprogCount = EnPrograms::where('progCod', 'NOT LIKE', '%GSS%')->count();
    //     $engradprogCount = EnPrograms::where('progCod', 'LIKE', '%GSS%')->count();
    //     $roomCount = Room::where('campus', Auth::guard('web')->user()->campus)->count();

    //     return view('scheduler.index', compact('colCount', 'enunprogCount', 'engradprogCount', 'roomCount', 'guard'));
    // }

    public function index()
    {
        $currentYear = Year::where('status', 'On')->value('adyear');
        $acadyear = ConfigureCurrent::where('set_status', 2)->value('schlyear');
        $acadsem = ConfigureCurrent::where('set_status', 2)->value('semester');
        $guard = $this->getGuard();

        // Cache colleges count for 5 minutes (300 seconds)
        $colCount = Cache::remember('colleges_count', 300, function () {
            return College::whereIn('id', [2,3,4,5,6,7,8])->count();
        });

        // Cache non-GSS programs count for 5 minutes
        $enunprogCount = Cache::remember('enunprog_count', 300, function () {
            return EnPrograms::where('progCod', 'NOT LIKE', '%GSS%')->count();
        });

        // Cache GSS programs count for 5 minutes
        $engradprogCount = Cache::remember('engradprog_count', 300, function () {
            return EnPrograms::where('progCod', 'LIKE', '%GSS%')->count();
        });

        // Cache room count per campus for 5 minutes
        $userCampus = Auth::guard('web')->user()->campus;
        $roomCount = Cache::remember("room_count_{$userCampus}", 300, function () use ($userCampus) {
            return Room::where('campus', $userCampus)->count();
        });

        return view('scheduler.index', compact('currentYear', 'acadyear', 'acadsem', 'colCount', 'enunprogCount', 'engradprogCount', 'roomCount', 'guard'));
    }

    public function recentschedfetch()
    {
        $acadyear = ConfigureCurrent::where('set_status', 2)->value('schlyear');
        $acadsem = ConfigureCurrent::where('set_status', 2)->value('semester');
        $campus = Auth::guard('web')->user()->campus;

        $data = SetClassSchedule::join('sub_offered', 'scheduleclass.subject_id', '=', 'sub_offered.id')
                        ->join('subjects', 'sub_offered.subCode', '=', 'subjects.sub_code')
                        ->leftJoin('faculty', 'scheduleclass.faculty_id', '=', 'faculty.id')
                        ->leftJoin('rooms', 'scheduleclass.room_id', '=', 'rooms.id')
                        ->where('scheduleclass.schlyear', '=', $acadyear)
                        ->where('scheduleclass.semester', '=', $acadsem)
                        ->where('scheduleclass.campus', $campus)
                        ->select('sub_offered.subSec', 'scheduleclass.*', 'subjects.sub_name', 'faculty.lname', 'faculty.fname', 'rooms.room_name')
                        ->orderBy('scheduleclass.id', 'desc') // Orders by latest added records
                        ->limit(5)
                        ->get();

        return response()->json(['data' => $data]);
    }

    public function collegeRead() 
    {
        return view('scheduler.college.list_college');
    }

    public function getcollegeRead() 
    {
        $data = College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])->orderBy('college_name', 'ASC')->get();

        return response()->json(['data' => $data]);
    }

    // public function getcollegeRead()
    // {
    //     $key = 'colleges_list';

    //     $data = Cache::remember($key, 60, function () {
    //         Log::info('Cache miss — fetching from DB');
    //         return College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])
    //             ->orderBy('college_name', 'ASC')
    //             ->get();
    //     });

    //     Log::info('Serving colleges from cache');

    //     return response()->json(['data' => $data]);
    // }

    public function collegeUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'campus' => 'required',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->input('id'));
            $coll = College::findOrFail($decryptedId);
            $coll->update([
                'campus' => implode(',', $request->input('campus')),
        ]);
            return response()->json(['success' => true, 'message' => 'College belong to Campus update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to store College belong to Campus'], 404);
        }
    }
}
