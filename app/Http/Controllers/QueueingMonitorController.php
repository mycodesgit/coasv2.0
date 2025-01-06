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
        // $counters = QueueCounter::all();
        // $countersArray = [];

        // foreach ($counters as $count) {
        //     $numbers = QueueCustomer::find($count->activeidnumber);

        //     $countersArray[] = [
        //         'window' => $count->windowname,
        //         'number' => $numbers ? $numbers->queue_number : null, 
        //         'status' => $count->status, 
        //     ];
        // }


        return view('queue.monitor.queuemonitor');
    }

    public function streamQueueData()
    {
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

        return response()->json(['data' => $countersArray]);
    }

    public function getCurrentQueue()
    {
        $counter = QueueCounter::where('currentid', '!=', '0')->latest('updated_at')->first();

        $data = null;

        if ($counter) {
            $customer = QueueCustomer::find($counter->currentid);

            $data = [
                'window' => $counter->windowname,
                'number' => $customer ? $customer->queue_number : 'N/A',
                'currentid' => $counter->currentid,
            ];
        }

        return response()->json($data);
    }

    public function getCurrentCallQueue()
    {
        $counter = QueueCounter::where('callid', '!=', '0')->first();

        $data = null;

        if ($counter) {
            $customer = QueueCustomer::find($counter->callid);

            $data = [
                'window' => $counter->windowname,
                'number' => $customer ? $customer->queue_number : 'N/A',
                'callid' => $counter->callid,
            ];
        }

        return response()->json($data);
    }
}
