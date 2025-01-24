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

use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\ApplicantDocs;

use App\Models\SettingDB\ConfigureCurrent;


class EnreportsController extends Controller
{
    public function studInfo() 
    {
        return view('enrollment.reports.studentinfo.studInfo');
    }

    public function studInfo_search(Request $request) 
    {
        if(Auth::guard('web')->user()->role == 0) {
            $campus = $request->query('campus');    
        } else {
            $campus = Auth::guard('web')->user()->campus;
        }

        $studlist = Student::where('campus', '=', $campus)->where('stud_id', 'NOT LIKE', '%-G%')->get();
        $civilStatuses = StudentCvlStatus::all();
        $genderStatuses = StudentGnderStatus::all();

        return view('enrollment.reports.studentinfo.studInfo_search', compact('studlist', 'civilStatuses', 'genderStatuses'));
    }

    public function getstudInfo_search(Request $request) 
    {
        if(Auth::guard('web')->user()->role == 0) {
            $campus = $request->query('campus');    
        } else {
            $campus = Auth::guard('web')->user()->campus;
        }

        $data = Student::join('studcivilstat', 'students.civil_status', '=', 'studcivilstat.cvlstat_name')
                        ->leftJoin('studgenderstat', 'students.gender', '=', 'studgenderstat.genderstat_name')
                        ->where('students.campus', '=', $campus)
                        ->where('students.stud_id', 'NOT LIKE', '%-G%')
                        ->select('students.*', 'studcivilstat.*', 'studgenderstat.*', 'students.id as stuDsid')
                        ->orderBy('students.lname', 'ASC')
                        ->get();
        
        return response()->json(['data' => $data]);
    }

    public function studInfo_view($id)
    {
        $student = Student::find($id);

        $selectedProgram = $student->course;

        $year = Carbon::now()->format('Y');
        $admissionid = Student::orderBy('admission_id', 'desc')->first();
        $program = Programs::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $docs = ApplicantDocs::where('admission_id', '=', $student->admission_id)->get();
        return view('enrollment.reports.studentinfo.studInfo_view')
        ->with('student', $student)
        ->with('program', $program)
        ->with('docs', $docs)
        ->with('selectedProgram', $selectedProgram);
    }

    public function studInfoUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
        ]);

        try {
            $decryptedId = Crypt::decrypt($request->input('id'));
            $studfee = Student::findOrFail($decryptedId);
            $studfee->update([
                'lname' => $request->input('lname'),
                'fname' => $request->input('fname'),
                'mname' => $request->input('mname'),
                'ext' => $request->input('ext'),
                'course' => $request->input('course'),
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


    public function studInfograduated() {
        return view('enrollment.reports.graduated.studinfograd');
    }

    public function studInfograduated_search(Request $request) 
    {
        $campus = Auth::guard('web')->user()->campus;

        $civilStatuses = StudentCvlStatus::all();
        $genderStatuses = StudentGnderStatus::all();

        return view('enrollment.reports.graduated.studinfograd_listsearch', compact('civilStatuses', 'genderStatuses'));
    }

    public function getstudInfograduated_search(Request $request) 
    {
        $campus = Auth::guard('web')->user()->campus;

        $data = Student::join('studcivilstat', 'students.civil_status', '=', 'studcivilstat.cvlstat_name')
                        ->leftJoin('studgenderstat', 'students.gender', '=', 'studgenderstat.genderstat_name')
                        ->leftJoin('studentsgradinfo', 'students.id', '=', 'studentsgradinfo.studIDprim')
                        ->where('students.campus', '=', $campus)
                        ->where('students.stud_id', 'LIKE', '%-G%')
                        ->select('students.*', 'studcivilstat.*', 'studgenderstat.*', 'students.id as stuDsid', 'studentsgradinfo.*')
                        ->orderBy('students.lname', 'ASC')
                        ->get();
        
        return response()->json(['data' => $data]);
    }

    public function rfstudprint() 
    {
        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.regform.search_studrf', compact('sy'));
    }

    public function rfstudprintsearch(Request $request)
    {
        $stud_id = $request->query('stud_id');
        $schlyear = $request->query('schlyear');
        $semester = $request->query('semester');
        $campus = Auth::guard('web')->user()->campus;

        $student = Student::where('stud_id', $stud_id)->where('campus', $campus)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> does not exist.');
        }
        $programEnHistory = StudEnrolmentHistory::join('coasv2_db_admission.users', 'program_en_history.postedBy', '=', 'coasv2_db_admission.users.id')
                ->where('program_en_history.studentID', $stud_id)
                ->where('program_en_history.schlyear', $schlyear)
                ->where('program_en_history.semester', '=', $semester)
                ->where('program_en_history.campus', '=', $campus)
                ->select('program_en_history.*', 'coasv2_db_admission.users.lname', 'coasv2_db_admission.users.fname', 'coasv2_db_admission.users.id as uid')
                ->first(); 

        if (!$programEnHistory) {
            return redirect()->back()->with('error', 'Student ID Number <strong>' . $stud_id . '</strong> not enrolled at this term or school year.');
        }

        $sy = ConfigureCurrent::select('id', 'schlyear')
            ->whereIn('id', function($query) {
                $query->select(DB::raw('MAX(id)'))
                    ->from('settings_conf')
                    ->groupBy('schlyear');
            })
            ->orderBy('id', 'DESC')
            ->get();

        return view('enrollment.reports.regform.searchresult_studrf', compact('sy'));
    }
}
