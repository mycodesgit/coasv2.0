<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

use Carbon\Carbon;
use App\Models\AdmissionDB\User;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\QueueCounter;
use App\Models\SettingDB\QueueCustomer;

class QueueingSettingController extends Controller
{
    public function index()
    {
        return view('queue.conf.list_counter');
    }

    public function getcounterRead()
    {
        $data = QueueCounter::orderBy('id', 'ASC')->get();

        return response()->json(['data' => $data]);
    }

    public function counterCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required',
            ]);

            $counterName = $request->input('name'); 
            $existingCounter = QueueCounter::where('name', $counterName)->first();

            if ($existingCounter) {
                return response()->json(['error' => true, 'message' => 'Counter Name already exists'], 404);
            }

            try {
                QueueCounter::create([
                    'name' => $request->input('name'),
                    'campus' => Auth::guard('web')->user()->campus,
                ]);

                return response()->json(['success' => true, 'message' => 'Counter stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Counter'], 404);
            }
        }
    }   
}
