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

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\Region;
use App\Models\SettingDB\Province;
use App\Models\SettingDB\City;
use App\Models\SettingDB\Barangay;

class StudentInfoController extends Controller
{
    public function index() 
    {
        $civilStatuses = StudentCvlStatus::all();
        $genderStatuses = StudentGnderStatus::all();
        $regions = Region::all();

        return view('enrollment.reports.studentinfo.studInfo', compact('civilStatuses', 'genderStatuses', 'regions'));
    }

    public function show(Request $request)
    {
        $campus = Auth::guard('web')->user()->campus;
        $search = $request->query('search');

        $students = Student::join(
                DB::raw('(SELECT MAX(id) as id, studentID FROM program_en_history GROUP BY studentID) as latest'),
                'students.stud_id',
                '=',
                'latest.studentID'
            )
            ->join('program_en_history', 'program_en_history.id', '=', 'latest.id')
            ->select('students.*', 'students.id as stdntid', 'program_en_history.course as enhiscourse')
            ->where('students.campus', $campus)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('students.lname', 'LIKE', "%{$search}%")
                    ->orWhere('students.fname', 'LIKE', "%{$search}%")
                    ->orWhere('students.stud_id', 'LIKE', "%{$search}%");
                });
            })
            ->orderBy('students.lname')
            ->paginate(10);

        $students->transform(function ($item) {
            $item->stdntid = $item->id;
            return $item;
        });

        return response()->json($students);
    }

    public function getEnrollmentHistory($studentId)
    {
        $history = StudEnrolmentHistory::where('studentID', $studentId)
            ->orderBy('schlyear', 'DESC')
            ->orderBy('semester', 'DESC')
            ->get();
        
        return response()->json($history);
    }

    public function update(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'fname' => 'required',
            'lname' => 'required',
        ]);

        try {
            $decryptedId = $request->input('id');
            $studfee = Student::findOrFail($decryptedId);
            $studfee->update([
                'lname' => $request->input('lname'),
                'fname' => $request->input('fname'),
                'mname' => $request->input('mname'),
                'ext' => $request->input('ext'),
                'gender' => $request->input('gender'),
                'civil_status' => $request->input('civil_status'),
                'contact' => $request->input('contact'),
                'email' => $request->input('email'),
                'religion' => $request->input('religion'),
                'address' => $request->input('address'),
                'bday' => $request->input('bday'),
                'pbirth' => $request->input('pbirth'),
                'monthly_income' => $request->input('monthly_income'),
                'hnum' => $request->input('hnum'),
                'brgy' => $request->input('brgy'),
                'city' => $request->input('city'),
                'province' => $request->input('province'),
                'region' => $request->input('region'),
                'zcode' => $request->input('zcode'),
                'lstsch_attended' => $request->input('lstsch_attended'),
                'suc_lst_attended' => $request->input('suc_lst_attended'),
                'stud_father' => $request->input('stud_father'),
                'stud_mother' => $request->input('stud_mother'),
                'stud_guardian' => $request->input('stud_guardian'),
                'guardian_contact' => $request->input('guardian_contact'),
                'lst_sch_attended_year' => $request->input('lst_sch_attended_year'),
                'date_admission' => $request->input('date_admission'),
                // 'graduation_date' => $request->input('graduation_date'),
                // 'graduation_schlyear' => $request->input('graduation_schlyear'),
                // 'graduation_semester' => $request->input('graduation_semester'),
                // 'graduation_course' => $request->input('graduation_course'),
                // 'lst_sch_type' => $request->input('lst_sch_type'),
        ]);
            return response()->json(['success' => true, 'message' => 'Student Information updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update Student Info'], 404);
        }
    }

    public function getProvinces($region_id) 
    {
        return response()->json(Province::where('region_id', $region_id)->get());
    }
    
    public function getCities($province_id) 
    {
        return response()->json(City::where('province_id', $province_id)->get());
    }
    
    public function getBarangays($city_id) 
    {
        return response()->json(Barangay::where('city_id', $city_id)->get());
    }
}

