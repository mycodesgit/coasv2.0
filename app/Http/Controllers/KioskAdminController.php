<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\KioskUser;
use App\Models\EnrollmentDB\KioskLogs;
use App\Models\EnrollmentDB\StudEnrolmentHistory;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\EnPrograms;

use App\Models\SettingDB\ConfigureCurrent;

class KioskAdminController extends Controller
{
    public function adminkioskRead()
    {
        $campus = Auth::guard('web')->user()->campus;

        return view('kioskadmin.list_kioskuser');
    }

    public function getStudentById($id)
    {
        $campus = Auth::guard('web')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));
        
        $student = Student::where('stud_id', $id)
            //->where('campus', $campus)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhere('campus', 'LIKE', "%$campus%");
                }
            })
            ->first();
        if ($student) {
            return response()->json($student);
        } else {
            return response()->json(['error' => 'Student not found'], 404);
        }
    }

    public function getadminkioskRead()
    {
        $campus = Auth::guard('web')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));

        $data = KioskUser::leftJoin('students', 'kioskstudent.studid', '=', 'students.stud_id')
                    //->where('students.campus', $campus)
                    ->where(function ($q) use ($campusArray) {
                        foreach ($campusArray as $campus) {
                            $q->orWhere('students.campus', 'LIKE', "%$campus%");
                        }
                    })
                    ->select('kioskstudent.*', 'kioskstudent.id as studkiosid', 'students.lname', 'students.fname', 'students.mname')
                    ->get();

        return response()->json(['data' => $data]);
    }

    public function adminkioskCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'studid' => 'required',
                'password' => 'required',
            ]);

            $studidName = $request->input('studid'); 
            $existingStudentID = KioskUser::where('studid', $studidName)->first();

            if ($existingStudentID) {
                return response()->json(['error' => true, 'message' => 'Student ID No already exists'], 404);
            }

            try {
                KioskUser::create([
                    'studid' => $request->input('studid'),
                    'password' => Hash::make($request->input('password')),
                    'postedBy' => Auth::guard('web')->user()->id
                ]);

                KioskLogs::create([
                    'studidres' => $studidName,
                    'postedBy' => Auth::guard('web')->user()->id
                ]);

                return response()->json(['success' => true, 'message' => 'Stored successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store'], 404);
            }
        }
    }

    public function adminkioskUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'studid' => 'required',
            'password' => 'required',
        ]);

        try {
            $studidName = $request->input('studid');
            $existingStudentID = KioskUser::where('studid', $studidName)->where('id', '!=', $request->input('id'))->first();

            if ($existingStudentID) {
                return response()->json(['error' => true, 'message' => 'Student ID No already exists'], 404);
            }

            $kioskuser = KioskUser::findOrFail($request->input('id'));
            $kioskuser->resetnumber = $kioskuser->resetnumber + 1;
            $kioskuser->update([
                'studid' => $studidName,
                'password' => Hash::make($request->input('password')),
                'postedBy' => Auth::guard('web')->user()->id,
                'resetnumber' => $kioskuser->resetnumber,
            ]);

            KioskLogs::create([
                'studidres' => $studidName,
                'postedBy' => Auth::guard('web')->user()->id
            ]);

            return response()->json(['success' => true, 'message' => 'Student Password in Kiosk Updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Student Password'], 404);
        }
    }

    public function adminkioskDelete($id) 
    {
        $kioskuser = KioskUser::find($id);
        $kioskuser->delete();

        return response()->json(['success'=> true, 'message'=>'Deleted Successfully',]);
    }


    public function adminbulkkioskRead()
    {
        $campus = Auth::guard('web')->user()->campus;
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('kioskadmin.list_kioskbulkuser', compact('sy'));
    }

    public function adminbulkkioskShow()
    {
        $campus = Auth::guard('web')->user()->campus;
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('kioskadmin.listsearch_kioskbulkuser', compact('sy'));
    }

    public function getstudCurrBulkSearch(Request $request)
    {
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');   
        // $campus = Auth::guard('web')->user()->campus;
        if(Auth::guard('web')->user()->role == 0 || Auth::guard('web')->user()->lname == 'Arlos') {
            $campus = $request->query('campus');    
        } else {
            $campus = Auth::guard('web')->user()->campus;
        }

        $campusArray = array_map('trim', explode(',', $campus));

        $data = StudEnrolmentHistory::leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                ->join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', $semester)
                // ->where('program_en_history.campus', $campus)
                ->where(function ($q) use ($campusArray) {
                    foreach ($campusArray as $campus) {
                        $q->orWhere('program_en_history.campus', 'LIKE', "%$campus%");
                    }
                })
                ->where('program_en_history.status', 2)
                ->groupBy('program_en_history.progCod', 'program_en_history.studYear', 'program_en_history.studSec')
                ->select(
                    'coasv2_db_schedule.programs.progCod', 
                    'coasv2_db_schedule.programs.progName', 
                    'coasv2_db_schedule.programs.progAcronym', 
                    'program_en_history.studYear', 
                    'program_en_history.studYear', 
                    'program_en_history.studSec', 
                    'students.gender', 
                    'program_en_history.id', 
                    'program_en_history.schlyear', 
                    'program_en_history.semester')
                ->selectRaw('program_en_history.progCod,
                            program_en_history.studYear, 
                            program_en_history.studSec, 
                            COUNT(DISTINCT students.stud_id) as studentCount,
                            COUNT(DISTINCT CASE WHEN students.gender IN ("Male", "MALE") THEN students.stud_id END) as maleCount,
                            COUNT(DISTINCT CASE WHEN students.gender IN ("Female", "FEMALE") THEN students.stud_id END) as femaleCount')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function adminkioskCreateBatch(Request $request)
    {
        $studentIDs = $request->studentID;
        $passwords = $request->password;

        $savedCount = 0;
        $failedCount = 0;

        $postedBy = Auth::guard('web')->user()->id;
        foreach ($studentIDs as $index => $id) {
            $password = $passwords[$index];

            try {
                KioskUser::updateOrCreate(
                    ['studid' => $id],
                    [
                        'password' => Hash::make($password),
                        'passtext' => $password,
                        'postedBy' => $postedBy
                    ]
                );
                $savedCount++;
            } catch (\Exception $e) {
                $failedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "$savedCount passwords saved successfully. $failedCount failed.",
        ]);
    }

    public function kioskReport()
    {
        $userId = Auth::guard('web')->user()->id;

        $logs = KioskLogs::select(
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(studidres) as total')
        )
        ->where('postedBy', $userId)
        ->whereYear('created_at', now()->year)
        ->groupBy(DB::raw('MONTH(created_at)'))
        ->pluck('total', 'month');

        $monthlyData = array_fill(1, 12, 0);

        foreach ($logs as $month => $count) {
            $monthlyData[$month] = $count;
        }

        // Today and yesterday counts
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        $todayCount = KioskLogs::where('postedBy', $userId)
            ->whereDate('created_at', $today)
            ->count();

        $yesterdayCount = KioskLogs::where('postedBy', $userId)
            ->whereDate('created_at', $yesterday)
            ->count();

        return view('kioskadmin.list_kioskreps', [
            'monthlyData' => array_values($monthlyData),
            'todayCount' => $todayCount,
            'yesterdayCount' => $yesterdayCount,
        ]);
    }
}
