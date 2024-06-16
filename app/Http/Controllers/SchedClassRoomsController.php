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

use App\Models\ScheduleDB\Room;
use App\Models\ScheduleDB\College;

class SchedClassRoomsController extends Controller
{
    public function roomsRead() 
    {
        $collegelist = College::all();
        return view('scheduler.room.rlist', compact('collegelist'));
    }

    public function getroomsRead() 
    {
        $data = Room::join('college', 'rooms.college_room', '=', 'college.id')
                ->where('rooms.campus', '=', Auth::guard('web')->user()->campus)
                ->select('rooms.*', 'college.*', 'rooms.id as rmid')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function roomsCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'college_room' => 'required',
                'room_name' => 'required',
                'room_capacity' => 'required',
            ]);

            $roomName = $request->input('room_name'); 
            $existingRooms = Room::where('room_name', $roomName)->first();

            if ($existingRooms) {
                return response()->json(['error' => true, 'message' => 'Room already exists'], 404);
            }

            try {
                Room::create([
                    'room_name' => $request->input('room_name'),
                    'college_room' => $request->input('college_room'),
                    'room_capacity' => $request->input('room_capacity'),
                    'campus' => Auth::guard('web')->user()->campus,
                ]);

                return response()->json(['success' => true, 'message' => 'Room stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Room'], 404);
            }
        }
    }

    public function roomsUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'college_room' => 'required',
            'room_name' => 'required',
            'room_capacity' => 'required',
        ]);

        try {
            $roomName = $request->input('room_name');
            $existingRooms = Room::where('room_name', $roomName)->where('id', '!=', $request->input('id'))->first();

            if ($existingRooms) {
                return response()->json(['error' => true, 'message' => 'Room already exists'], 404);
            }

            //$decryptedId = Crypt::decrypt($request->input('id'));
            $room = Room::findOrFail($request->input('id'));
            $room->update([
                'room_name' => $request->input('room_name'),
                'college_room' => $request->input('college_room'),
                'room_capacity' => $request->input('room_capacity'),
        ]);
            return response()->json(['success' => true, 'message' => 'Room update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to update Room'], 404);
        }
    }

    public function roomsDelete($id) 
    {
        $room = Room::find($id);
        $room->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }
}
