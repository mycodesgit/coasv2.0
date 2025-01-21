<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use Carbon\Carbon;

use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentInfoGrad;
use App\Models\EnrollmentDB\StudentCvlStatus;
use App\Models\EnrollmentDB\StudentGnderStatus;

class EnStudAddController extends Controller
{
    public function studentCreate()
    {
        $program = Programs::orderBy('id', 'asc')->get();
        $strand = Strands::orderBy('id', 'asc')->get();
        $civilStatuses = StudentCvlStatus::all();
        $genderStatuses = StudentGnderStatus::all();

        return view('enrollment.students.addstud', compact('strand', 'program', 'civilStatuses', 'genderStatuses'));
    }

    public function studentStore(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'type' => 'required',
                'lname' => 'required',
                'fname' => 'required',
                'mname' => 'required',
                'gender' => 'required',
                'bday' => 'required',
                'contact' => 'required',
                'civil_status' => 'required',
            ]);

            $campus = Auth::guard('web')->user()->campus;
            $studentId = $this->generateAdmissionId($campus);

            $lname = $request->input('lname');
            $fname = $request->input('fname');
            $mname = $request->input('mname');

            $existingStud = Student::where('campus', $campus)
                            ->where('lname', $lname)
                            ->where('fname', $fname)
                            ->where('mname', $mname)
                            ->where('stud_id', 'LIKE', '%-G')
                            ->first();

            if ($existingStud) {
                return response()->json(['error' => true, 'message' => 'Student already exists'], 404);
            }

            try {
                $newstudID = Student::create([
                    'app_id' => $request->input('app_id'),
                    'status' => $request->input('status'),
                    'en_status' => $request->input('en_status'),
                    'p_status' => $request->input('p_status'),
                    'campus' => Auth::guard('web')->user()->campus,
                    'stud_id' =>  $studentId,
                    'type' => $request->input('type'),
                    'lname' => $request->input('lname'),
                    'fname' => $request->input('fname'),
                    'mname' => $request->input('mname'),
                    'ext' => $request->input('ext'),
                    'gender' => $request->input('gender'),
                    'civil_status' => $request->input('civil_status'),
                    'bday' => $request->input('bday'),
                    'pbirth' => $request->input('pbirth'),
                    'email' => $request->input('email'),
                    'contact' => $request->input('contact'),
                    'religion' => $request->input('religion'),
                    'address' => $request->input('address'),
                    'hnum' => $request->input('hnum'),
                    'brgy' => $request->input('brgy'),
                    'city' => $request->input('city'),
                    'province' => $request->input('province'),
                    'region' => $request->input('region'),
                    'zcode' => $request->input('zcode'),
                    'posted_by' => Auth::guard('web')->user()->id,
                ]);

                StudentInfoGrad::create([
                    'studIDprim' => $newstudID->id,
                    'studIDno' => $studentId,
                    'elementary' => $request->input('elementary'),
                    'elemyeargrad' => $request->input('elemyeargrad'),
                    'highschool' => $request->input('highschool'),
                    'highschoolyeargrad' => $request->input('highschoolyeargrad'),
                    'tertiary' => $request->input('tertiary'),
                    'tertiaryyeargrad' => $request->input('tertiaryyeargrad'),
                    'tertiarycourse' => $request->input('tertiarycourse'),
                    'tertiarymajor' => $request->input('tertiarymajor'),
                    'masterspeciallization' => $request->input('masterspeciallization'),
                    'masterschool' => $request->input('masterschool'),
                    'masternounit' => $request->input('masternounit'),
                    'masteraddress' => $request->input('masteraddress'),
                    'masterinclyear' => $request->input('masterinclyear'),
                    'doctorcourse' => $request->input('doctorcourse'),
                    'doctorspecialization' => $request->input('doctorspecialization'),
                    'doctorschool' => $request->input('doctorschool'),
                    'doctornounit' => $request->input('doctornounit'),
                    'doctoraddress' => $request->input('doctoraddress'),
                    'doctorinclyear' => $request->input('doctorinclyear'),
                ]);

                return response()->json(['success' => true, 'message' => 'Student stored successfully', 'student_id' => $studentId], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Student'], 404);
            }
        }
    }

    protected function generateAdmissionId($campus)
    {
        $campusLetter = $this->getCampusLetter($campus);
        $year = Carbon::now()->format('Y');

        // Find the highest existing incremental number for this year and campus
        $latestStudent = Student::where('stud_id', 'like', "{$year}-%-{$campusLetter}")
            ->orderBy('stud_id', 'desc')
            ->first();

        if ($latestStudent) {
            // Extract the last 4-digit incremental number and increment it
            $latestIncrement = (int) substr($latestStudent->stud_id, 5, 4);
            $newIncrement = str_pad($latestIncrement + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // If no students found, start from 0001
            $newIncrement = '0001';
        }

        $formattedId = "{$year}-{$newIncrement}-{$campusLetter}";

        return $formattedId;
    }

    protected function getCampusLetter($campus)
    {
        $role = Auth::guard('web')->user()->role;
        
        $campusMappings = [
            'MC' => $role == 15 ? 'G' : 'K',
            'VC' => 'V',
            'SCC' => 'S',
            'HC' => 'H',
            'MP' => 'M',
            'IC' => 'I',
            'CA' => 'A',
            'CC' => 'C',
            'SC' => 'P',
            'HinC' => 'N',
            'VE' => 'D',
        ];
        return $campusMappings[$campus] ?? 'X';
    }

    public function studentUnderGradStore(Request $request) 
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'type' => 'required',
                'lname' => 'required',
                'fname' => 'required',
                'mname' => 'required',
                'gender' => 'required',
                'bday' => 'required',
                'contact' => 'required',
                'civil_status' => 'required',
            ]);

            $campus = Auth::guard('web')->user()->campus ?? null;

            if (!$campus) {
                return response()->json(['error' => true, 'message' => 'Campus not found for the authenticated user'], 401);
            }

            $stud_id = $request->input('stud_id');
            $lname = $request->input('lname');
            $fname = $request->input('fname');
            $mname = $request->input('mname');

            // Check if the student exists by stud_id
            $studentByStudId = Student::where('campus', $campus)
                ->where('stud_id', $stud_id)
                ->first();

            // Check if the student exists by name
            $studentByName = Student::where('campus', $campus)
                ->where('lname', $lname)
                ->where('fname', $fname)
                ->where('mname', $mname)
                ->first();

            if ($studentByStudId) {
                if ($studentByName && $studentByStudId->id === $studentByName->id) {
                    return response()->json([
                        'error' => true, 
                        'message' => 'This student ID is already assigned to this student in the campus.'
                    ], 409);
                } else {
                    $realOwnerName = $studentByStudId->fname . ' ' . $studentByStudId->mname . ' ' . $studentByStudId->lname;
                    return response()->json([
                        'error' => true, 
                        'message' => 'This student ID ' . $stud_id . ' is already assigned to ' . $realOwnerName
                    ], 409);
                }
            } elseif ($studentByName) {
                return response()->json([
                    'error' => true, 
                    'message' => 'Student with this name already exists in this campus but has a different ID.'
                ], 409);
            }

            try {
                $newstudID = Student::create([
                    'app_id' => $request->input('app_id'),
                    'status' => $request->input('status'),
                    'en_status' => $request->input('en_status'),
                    'p_status' => $request->input('p_status'),
                    'campus' => Auth::guard('web')->user()->campus,
                    'stud_id' =>  $request->input('stud_id'),
                    'type' => $request->input('type'),
                    'lname' => $request->input('lname'),
                    'fname' => $request->input('fname'),
                    'mname' => $request->input('mname'),
                    'ext' => $request->input('ext'),
                    'gender' => $request->input('gender'),
                    'civil_status' => $request->input('civil_status'),
                    'bday' => $request->input('bday'),
                    'pbirth' => $request->input('pbirth'),
                    'email' => $request->input('email'),
                    'contact' => $request->input('contact'),
                    'religion' => $request->input('religion'),
                    'address' => $request->input('address'),
                    'hnum' => $request->input('hnum'),
                    'brgy' => $request->input('brgy'),
                    'city' => $request->input('city'),
                    'province' => $request->input('province'),
                    'region' => $request->input('region'),
                    'zcode' => $request->input('zcode'),
                    'stud_father' => $request->input('stud_father'),
                    'stud_mother' => $request->input('stud_mother'),
                    'stud_guardian' => $request->input('stud_guardian'),
                    'monthly_income' => $request->input('monthly_income'),
                    'guardian_contact' => $request->input('guardian_contact'),
                    'posted_by' => Auth::guard('web')->user()->id,
                ]);

                return response()->json(['success' => true, 'message' => 'Student stored successfully', 'student_id' => $stud_id], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => true, 'message' => 'Failed to store Student'], 404);
            }
        }
    }
}
