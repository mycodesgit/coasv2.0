<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QueueingMonitorController extends Controller
{
    public function queueRead()
    {
        return view('queue.queuemonitor');
    }
}
