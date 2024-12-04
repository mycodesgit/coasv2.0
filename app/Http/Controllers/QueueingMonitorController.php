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
        $counter = QueueCounter::orderBy('id', 'ASC')->get();
        return view('queue.monitor.queuemonitor', compact('counter'));
    }

    public function index()
    {
        $counters = Counter::all();
        $waitingCustomers = Customer::where('status', 'waiting')->orderBy('queue_number')->get();

        return view('queue.index', compact('counters', 'waitingCustomers'));
    }

}
