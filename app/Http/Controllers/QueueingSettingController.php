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
use App\Models\SettingDB\QueueMode;

class QueueingSettingController extends Controller
{
    public function dash()
    {
        $data = $this->getQueueMetrics();
        return view('queue.conf.dashboard', $data);
    }

    public function index()
    {
        $user = User::where(function ($query) {
            $query->where('dept', '=', 'Registrar Office')
              ->orWhere('dept', '=', 'Graduate School Registrar');
        })->where('campus', '=', Auth::guard('web')->user()->campus)->get();
        return view('queue.conf.list_counter', compact('user'));
    }

    // Endpoint for AJAX polling
    public function fetchLiveData()
    {
        $data = $this->getQueueMetrics();
        return response()->json($data);
    }

    private function getQueueMetrics()
    {
        // 1. Fetch all counters
        $counters = QueueCounter::all();
        // 2. Extract unique user IDs logged into the counters
        $userIds = $counters->pluck('useridlog')->filter()->unique();
        // 3. Query users from their database connection and key by ID
        $users = User::whereIn('id', $userIds)->get()->keyBy('id');
        // 4. Attach the user object manually to each counter
        $counters->transform(function ($counter) use ($users) {
            $counter->user_info = $users->get($counter->useridlog);
            return $counter;
        });

        $customerTickets = QueueCustomer::pluck('queue_number', 'id');
        $activeCounters = QueueCounter::whereNotNull('activeidnumber')->get();
        $waitingCustomers = [];
        foreach ($activeCounters as $counter) {
            // Find the next waiting customer for this category whose ID is greater than activeidnumber
            $nextCustomer = QueueCustomer::where('catname', $counter->category)
                ->where('status', 'waiting')
                ->where('id', '>', $counter->activeidnumber)
                ->orderBy('id', 'asc')
                ->first();

            if ($nextCustomer) {
                $waitingCustomers[$counter->category] = $nextCustomer;
            }
        }

        $customers = QueueCustomer::latest()->take(10)->get();
        // Standardize timestamps for JSON response
        $customers->transform(function ($customer) {
            $customer->formatted_time = $customer->created_at ? $customer->created_at->format('h:i A') : 'N/A';
            return $customer;
        });

        $totalWaiting = QueueCustomer::where('status', 'waiting')->count();
        $totalServing = QueueCustomer::where('status', 'serving')->count();
        $totalCompleted = QueueCustomer::where('status', 'completed')->count();

        return [
            'counters' => $counters,
            'customerTickets' => $customerTickets,
            'waitingCustomers' => $waitingCustomers,
            'customers' => $customers,
            'totalWaiting' => $totalWaiting,
            'totalServing' => $totalServing,
            'totalCompleted' => $totalCompleted,
            'totalCounters' => $counters->count(),
        ];
    }

    public function getcounterRead()
    {
        $data = QueueCounter::leftJoin('coasv2_db_admission.users', 'counters.useridlog', '=', 'coasv2_db_admission.users.id')
                ->where('counters.campus', '=', Auth::guard('web')->user()->campus)
                ->select('coasv2_db_admission.users.fname', 'coasv2_db_admission.users.lname', 'counters.*')
                ->orderBy('id', 'ASC')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function counterCreate(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'windowname' => 'required',
            ]);

            $counterName = $request->input('windowname');
            $existingCounter = QueueCounter::where('windowname', $counterName)->where('campus', '=', Auth::guard('web')->user()->campus)->first();

            if ($existingCounter) {
                return response()->json(['error' => true, 'message' => 'Counter Name already exists'], 404);
            }

            try {
                QueueCounter::create([
                    'windowname' => $request->input('windowname'),
                    'category' => $request->input('category'),
                    'useridlog' => $request->input('useridlog'),
                    'campus' => Auth::guard('web')->user()->campus,
                ]);

                return response()->json(['success' => true, 'message' => 'Counter stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Counter'], 404);
            }
        }
    }

    public function counterUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'useridlog' => 'required',
        ]);

        try {
            $counterName = $request->input('useridlog');
            $existingCounter = QueueCounter::where('useridlog', $counterName)->where('id', '!=', $request->input('id'))->first();

            if ($existingCounter) {
                return response()->json(['error' => true, 'message' => 'Counters already exists'], 404);
            }

            $counter = QueueCounter::findOrFail($request->input('id'));
            $counter->update([
                'useridlog' => $counterName,
                'category' => $request->input('category'),
        ]);
            return response()->json(['success' => true, 'message' => 'Counter update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Counter'], 404);
        }
    }

    public function numberRead()
    {
        $counterwin = QueueCounter::orderBy('id', 'ASC')->where('campus', '=', Auth::guard('web')->user()->campus)->get();
        return view('queue.conf.list_numbers', compact('counterwin'));
    }

    public function getnumberRead()
    {
        $data = QueueCustomer::orderBy('id', 'ASC')->where('campus', '=', Auth::guard('web')->user()->campus)->get();

        return response()->json(['data' => $data]);
    }

    public function storeQueueNumbers(Request $request)
    {
        try {
            $request->validate([
                'start' => 'required|integer|min:1',
                'end' => 'required|integer|min:1|gte:start',
                'catname' => 'required|string',
                'available_in' => 'required|array',
                'available_in.*' => 'string',
            ]);

            $start = $request->start;
            $end = $request->end;
            $catname = trim($request->catname);
            $available_in = $request->available_in;
            $campus = Auth::guard('web')->user()->campus;

            $prefixes = [
                'Processing'   => strtoupper($campus) . 'P-',
                'Pre-register' => 'PRE-',
                'Enrollment'   => 'ENRO-',
                'Printing'     => 'PRINT-',
                'Evaluation'   => 'EVAL-',
            ];

            $prefix = $prefixes[$catname] ?? strtoupper($campus) . '-';

            for ($i = $start; $i <= $end; $i++) {
                QueueCustomer::create([
                    'queue_number' => sprintf('%s%04d', $prefix, $i),
                    'catname'      => $catname,
                    'campus'       => $campus,
                    'available_in' => implode(',', $available_in),
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Queue numbers stored successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function queueonoff()
    {
        $setqueuemode = QueueMode::first();

        return view('queue.conf.queuesetting', compact('setqueuemode'));
    }

    public function toggleQueue(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'statusqueue' => 'required|boolean', // Accepts `true` or `false` from checkbox
        ]);

        // Fetch the first record or create a default one if it doesn't exist
        $queueMode = QueueMode::firstOrCreate([], ['statusqueue' => 'Off']);

        // Toggle the status based on the checkbox state
        $queueMode->statusqueue = $request->statusqueue ? 'On' : 'Off';
        $queueMode->save();

        return response()->json([
            'success' => true,
            'message' => $queueMode->statusqueue === 'On'
                ? ' enabled'
                : ' disabled',
        ]);
    }

    public function resetQueue(Request $request)
    {
        try {
            QueueCustomer::query()->update(['status' => 'waiting']);
            QueueCounter::query()->update(['activeidnumber' => 0, 'currentid' => 0, 'callid' => 0]);

            return response()->json(['success' => true, 'message' => 'Queueing numbers reset successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to reset Queueing numbers'], 404);
        }
    }

    public function counterUserUpdate(Request $request)
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $counterName = Auth::guard('web')->user()->id;
            $existingCounter = QueueCounter::where('useridlog', $counterName)->where('id', '!=', $request->input('id'))->first();

            if ($existingCounter) {
                return response()->json(['error' => true, 'message' => 'Counters already exists'], 404);
            }

            $counter = QueueCounter::findOrFail($request->input('id'));
            $counter->update([
                'useridlog' => $counterName,
                'category' => $request->input('category'),
        ]);
            return response()->json(['success' => true, 'message' => 'Counter update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Counter'], 404);
        }
    }
}
