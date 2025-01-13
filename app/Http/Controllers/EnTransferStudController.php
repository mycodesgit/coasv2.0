<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Rules\UniqueStudentID;
use Illuminate\Support\Facades\Log;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudentTransfered;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\DeleteEnrollmentLogs;

use App\Models\ScheduleDB\ClassEnroll;
use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\EnPrograms;
use App\Models\ScheduleDB\Subject;
use App\Models\ScheduleDB\SubjectOffered;
use App\Models\ScheduleDB\ClassesSubjects;

use App\Models\ScholarshipDB\Scholar;

use App\Models\AssessmentDB\StudentFee;
use App\Models\AssessmentDB\StudentAppraisal;
use App\Models\AssessmentDB\StudPayment;

class EnTransferStudController extends Controller
{
    public function list_trans()
    {
        $stud = Student::all();
        return view('enrollment.transfer.list_studtransfered', compact('stud'));
    }

    public function getStudents()
    {
        $students = Student::select('id', 'stud_id', 'lname', 'fname', 'mname')->get();
        return response()->json($students);
    }

    public function studtransferCreate(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'stud_id' => 'required',
            ]);

            $studidName = $request->input('stud_id'); 
            $existingStudentID = StudentTransfered::where('stud_id', $studidName)->first();

            if ($existingStudentID) {
                return response()->json(['error' => true, 'message' => 'Student ID No already exists'], 404);
            }

            try {
                StudentTransfered::create([
                    'stud_id' => $request->input('stud_id'),
                    'studbaseprim_id' => $request->input('studbaseprim_id'),
                    'fromcampus' => $request->input('fromcampus'),
                    'tocampus' => $request->input('tocampus'),
                ]);

                Student::where('stud_id', $studidName)->update([
                    'campus' => $request->input('tocampus'),
                ]);

                StudEnrolmentHistory::where('studentID', $studidName)->update([
                    'campus' => $request->input('tocampus'),
                ]);

                Grade::where('studID', $studidName)->update([
                    'campus' => $request->input('tocampus'),
                ]);

                StudentAppraisal::where('studID', $studidName)->update([
                    'campus' => $request->input('tocampus'),
                ]);

                return response()->json(['success' => true, 'message' => 'Transfer successfully'], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store'], 404);
            }
        }
    }

    public function getstudentTransferRead()
    {
        $campus = Auth::guard('web')->user()->campus;

        $data = KioskUser::leftJoin('students', 'kioskstudent.studid', '=', 'students.stud_id')
                    ->where('students.campus', $campus)
                    ->select('kioskstudent.*', 'kioskstudent.id as studkiosid', 'students.lname', 'students.fname', 'students.mname')
                    ->get();

        return response()->json(['data' => $data]);
    }
}
