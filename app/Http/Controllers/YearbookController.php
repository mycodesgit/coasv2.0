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
use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentInfoGrad;
use App\Models\EnrollmentDB\StudentCvlStatus;
use App\Models\EnrollmentDB\StudentGnderStatus;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\ApplicantDocs;

use App\Models\AssessmentDB\StudPayment;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\Region;
use App\Models\SettingDB\Province;
use App\Models\SettingDB\City;
use App\Models\SettingDB\Barangay;

use App\Models\YearBookDB\Yearbooks;
use App\Models\YearBookDB\YearbookShipment;
use App\Models\YearBookDB\YearbookIssuance;

class YearbookController extends Controller
{
    public function index()
{
    $userCampus = Auth::guard('web')->user()->campus;

    $activeConfig = ConfigureCurrent::where('set_status', 2)->first();
    if (!$activeConfig) {
        return back()->with('error', 'No active school year found.');
    }

    $schlyearactive = $activeConfig->schlyear;
    $semesteractive = $activeConfig->semester;

    // OPTIMIZATION 1: Group student enrollment counts into 1 DB query instead of 4
    $enrolmentCounts = StudEnrolmentHistory::where('studentID', 'NOT LIKE', '%-G%')
        ->where('schlyear', $schlyearactive)
        ->where('semester', $semesteractive)
        ->where('campus', $userCampus)
        ->whereIn('status', [2, 3])
        ->select('studYear', DB::raw('count(*) as total'))
        ->groupBy('studYear')
        ->pluck('total', 'studYear');

    $enrlstudcountfirst  = $enrolmentCounts->get(1, 0);
    $enrlstudcountsecond = $enrolmentCounts->get(2, 0);
    $enrlstudcountthird  = $enrolmentCounts->get(3, 0);
    $enrlstudcountfourth = $enrolmentCounts->get(4, 0);

    // OPTIMIZATION 2: Releasing Stats (Paid vs Released)
    $totalPaidStudents = StudPayment::where('schlyear', $schlyearactive)
        ->where('semester', $semesteractive)
        ->where('campus', $userCampus)
        ->where('account', 'YEARBOOK FEE')
        ->count();

    $totalReleased = YearbookIssuance::count(); // Adjust conditions/joins if linked to active SY
    $totalPending  = max(0, $totalPaidStudents - $totalReleased);

    // OPTIMIZATION 3: Last 7 Days Daily Releasing Trend for Chart
    $dailyReleases = YearbookIssuance::select(
            DB::raw('DATE(issued_at) as date'),
            DB::raw('count(*) as count')
        )
        ->where('issued_at', '>=', Carbon::now()->subDays(7))
        ->groupBy('date')
        ->orderBy('date', 'ASC')
        ->get();

    $chartDates  = $dailyReleases->pluck('date')->map(fn($d) => Carbon::parse($d)->format('M d'));
    $chartCounts = $dailyReleases->pluck('count');

    return view('yearbook.index', compact(
        'enrlstudcountfirst',
        'enrlstudcountsecond',
        'enrlstudcountthird',
        'enrlstudcountfourth',
        'totalPaidStudents',
        'totalReleased',
        'totalPending',
        'chartDates',
        'chartCounts'
    ));
}

    public function showStudent(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('yearbook.studs.liststud', compact('sy'));
    }

    public function showStudentResult(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');

        return view('yearbook.studs.liststudsearch', compact('sy'));
    }

    public function showRelease()
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
        return view('yearbook.books.release', compact('sy'));
    }

    public function showReleaseResult(Request $request)
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();
        
        $availableYearbooks = Yearbooks::where('total_received', '>', 0)->get();

        return view('yearbook.books.releasesearch', compact('sy', 'availableYearbooks'));
    }

    public function getstudorreleaseRead(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = $request->query('campus');

        $yearbookDb = config('database.connections.yearbook.database');

        $data = StudPayment::leftJoin('coasv2_db_enrollment.students', 'studpayment.studID', '=', 'coasv2_db_enrollment.students.stud_id')
                ->leftJoin("{$yearbookDb}.yearbook_issuances", 'studpayment.studID', '=', "{$yearbookDb}.yearbook_issuances.student_id")
                ->where('studpayment.schlyear', '=', $schlyear)
                ->where('studpayment.semester', '=', $semester)
                ->where('studpayment.campus', '=', $campus)
                ->where('studpayment.account', '=', 'YEARBOOK FEE')
                ->select(
                    'coasv2_db_enrollment.students.lname',
                    'coasv2_db_enrollment.students.fname',
                    'coasv2_db_enrollment.students.mname',
                    'coasv2_db_enrollment.students.ext',
                    'studpayment.*',
                    "{$yearbookDb}.yearbook_issuances.issued_at",
                    "{$yearbookDb}.yearbook_issuances.issued_by"
                )
                ->orderBy('studpayment.orno', 'ASC')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function issueYearbookToStudent(Request $request)
    {
        $request->validate([
            'yearbook_id' => 'required',
            'student_id' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $yearbook = Yearbooks::findOrFail($request->input('yearbook_id'));

                // Check stock availability
                if ($yearbook->total_received <= 0) {
                    throw new \Exception('Selected yearbook batch has no stock available.');
                }

                // Check duplicate release
                $alreadyClaimed = YearbookIssuance::where('yearbook_id', $yearbook->id)
                    ->where('student_id', $request->input('student_id'))
                    ->exists();

                if ($alreadyClaimed) {
                    throw new \Exception('This student has already claimed a copy from this yearbook batch.');
                }

                // Record release log
                YearbookIssuance::create([
                    'yearbook_id' => $yearbook->id,
                    'student_id'  => $request->input('student_id'),
                    'issued_at'   => Carbon::now(),
                    'issued_by'   => auth()->id() ?? 'Office Admin',
                    'remarks'     => $request->input('remarks'),
                ]);

                // Deduct inventory stock
                $yearbook->decrement('total_received', 1);
            });

            return response()->json(['success' => true, 'message' => 'Yearbook successfully released to student!']);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }
}
