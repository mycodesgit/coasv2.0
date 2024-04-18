<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Room;

class SchedClassCollegeController extends Controller
{
    public function index()
    {
        $colCount = College::whereIn('id', [2, 3, 4, 5, 6, 7, 8])->count();
        $enunprogCount = EnPrograms::where('progCod', 'NOT LIKE', '%GSS%')->count();
        $engradprogCount = EnPrograms::where('progCod', 'LIKE', '%GSS%')->count();
        $roomCount = Room::where('campus', Auth::guard('web')->user()->campus)->count();

        return view('scheduler.index', compact('colCount', 'enunprogCount', 'engradprogCount', 'roomCount'));
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
