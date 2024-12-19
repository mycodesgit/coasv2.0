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

class QueueingMonitorController extends Controller
{
    public function queueRead()
    {
        $counters = QueueCounter::all();
        $countersArray = [];

        foreach ($counters as $count) {
            $numbers = QueueCustomer::find($count->activeidnumber);

            $countersArray[] = [
                'window' => $count->windowname,
                'number' => $numbers ? $numbers->queue_number : null, 
                'status' => $count->status, 
            ];
        }


        return view('queue.monitor.queuemonitor', compact('countersArray'));
    }

    public function streamQueueData()
    {
        return response()->stream(function () {
            while (true) {
                // Fetch the data from your `getqueueRead` logic
                $counters = QueueCounter::all();
                $countersArray = [];

                foreach ($counters as $count) {
                    $numbers = QueueCustomer::find($count->activeidnumber);

                    $countersArray[] = [
                        'window' => $count->windowname,
                        'number' => $numbers ? $numbers->queue_number : null,
                        'updated_at' => $count->updated_at,
                    ];
                }

                // Send data as SSE
                echo "data: " . json_encode(['data' => $countersArray]) . "\n\n";
                ob_flush();
                flush();

                // Sleep to control the update frequency (e.g., every 2 seconds)
                sleep(2);
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
        ]);
    }

    public function getCurrentQueue()
    {
        $queueNumber = QueueCustomer::where('status', 'serving')->first();

        if ($queueNumber) {
            return response()->json([
                'success' => true,
                'queue_number' => $queueNumber->queue_number,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No queue number is currently being served.',
        ]);
    }

}
