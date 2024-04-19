<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;


use Storage;
use Carbon\Carbon;

use App\Models\ScheduleDB\Room;
use App\Models\ScheduleDB\College;

class SchedClassRoomsController extends Controller
{
    public function roomsRead() 
    {
        return view('scheduler.room.rlist');
    }

    public function getroomsRead() 
    {
        $data = Room::join('college', 'rooms.college_room', 'college.id')
                ->where('rooms.campus', '=', Auth::guard('web')->user()->campus)->get();

        return response()->json(['data' => $data]);
    }
}
