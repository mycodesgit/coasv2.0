<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Rules\UniqueStudentID;
use Illuminate\Support\Facades\Log;

use PDF;
use Storage;
use Carbon\Carbon;

use App\Models\AdmissionDB\User;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\StudentLevel;
use App\Models\EnrollmentDB\Grade;
use App\Models\EnrollmentDB\GradeCode;
use App\Models\EnrollmentDB\YearLevel;
use App\Models\EnrollmentDB\MajorMinor;
use App\Models\EnrollmentDB\StudentStatus;
use App\Models\EnrollmentDB\StudentType;
use App\Models\EnrollmentDB\StudentShifTrans;
use App\Models\EnrollmentDB\StudEnrolmentHistory;
use App\Models\EnrollmentDB\DeleteEnrollmentLogs;
use App\Models\EnrollmentDB\StudHisLog;
use App\Models\EnrollmentDB\StudSubLog;
use App\Models\EnrollmentDB\StudentRFID;

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

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\SettingDB\Region;
use App\Models\SettingDB\Province;
use App\Models\SettingDB\City;
use App\Models\SettingDB\Barangay;
use App\Models\SettingDB\ButtonAccess;

class OssaIDsystemController extends Controller
{
    public function index()
    {
        $userCampus = Auth::guard('web')->user()->campus;

        // Fetch the active configuration with set_status = 2
        $activeConfig = ConfigureCurrent::where('set_status', 2)->first();
        if (!$activeConfig) {
            return back()->with('error', 'No active school year found.');
        }
        $activeConfigId = $activeConfig->id;
        
        $previousConfig = ConfigureCurrent::where('id', '<', $activeConfigId) // Ensure it's before the current active one
            ->orderBy('id', 'desc') // Get the most recent one
            ->first();

        $schlyearactiveYear = $activeConfig->schlyear;
        $schlyearactive = $activeConfig->schlyear;
        $semesteractive = $activeConfig->semester;
        $prevsemesteractive = $previousConfig->semester;

        $previousSchlyearYear = $previousConfig ? $previousConfig->schlyear : null;

        if (!$previousSchlyearYear) {
            return back()->with('error', 'No previous school year found.');
        }

        // Query for the previous school year's first semester
        $collegesFirstSemester = College::join('coasv2_db_enrollment.program_en_history', function ($join) {
                $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
            })
            ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
            ->where(function ($query) use ($userCampus) {
                $campuses = explode(', ', $userCampus);
                foreach ($campuses as $campus) {
                    $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                }
            })
            ->where('coasv2_db_enrollment.program_en_history.semester', '=', $prevsemesteractive)
            ->where('coasv2_db_enrollment.program_en_history.schlyear', $previousSchlyearYear)
            ->whereIn('coasv2_db_enrollment.program_en_history.status', [2, 3])
            ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
            ->orderBy('college_name', 'ASC')
            ->select('college.*', 'coasv2_db_enrollment.program_en_history.semester', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
            ->groupBy('college.id')
            ->get();


        // Query for the current active school year's second semester
        $collegesSecondSemester = College::join('coasv2_db_enrollment.program_en_history', function ($join) {
                $join->on(DB::raw("SUBSTRING_INDEX(coasv2_db_enrollment.program_en_history.progCod, '-', 1)"), '=', 'college.college_abbr');
            })
            ->whereIn('college.id', [2, 3, 4, 5, 6, 7, 8])
            ->where(function ($query) use ($userCampus) {
                $campuses = explode(', ', $userCampus);
                foreach ($campuses as $campus) {
                    $query->orWhere('college.campus', 'LIKE', '%' . $campus . '%');
                }
            })
            ->where('coasv2_db_enrollment.program_en_history.semester', '=', $semesteractive)
            ->where('coasv2_db_enrollment.program_en_history.schlyear', $schlyearactiveYear)
            ->whereIn('coasv2_db_enrollment.program_en_history.status', [2, 3])
            ->where('coasv2_db_enrollment.program_en_history.campus', Auth::guard('web')->user()->campus)
            ->orderBy('college_name', 'ASC')
            ->select('college.*', 'coasv2_db_enrollment.program_en_history.semester', DB::raw('COUNT(DISTINCT coasv2_db_enrollment.program_en_history.studentID) as college_count'))
            ->groupBy('college.id')
            ->get();

        $enrlstudcountfirst = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                            ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                            ->where('program_en_history.semester', 'LIKE', $semesteractive)
                            ->where('program_en_history.studYear', '=', '1')
                            ->where('program_en_history.campus', '=', $userCampus)
                            ->whereIn('program_en_history.status',  [2, 3])
                            ->count();


        $enrlstudcountsecond = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                            ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                            ->where('program_en_history.semester', 'LIKE', $semesteractive)
                            ->where('program_en_history.studYear', '=', '2')
                            ->where('program_en_history.campus', '=', $userCampus)
                            ->whereIn('program_en_history.status',  [2, 3])
                            ->count();

        $enrlstudcountthird = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                            ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                            ->where('program_en_history.semester', 'LIKE', $semesteractive)
                            ->where('program_en_history.studYear', '=', '3')
                            ->where('program_en_history.campus', '=', $userCampus)
                            ->whereIn('program_en_history.status',  [2, 3])
                            ->count();

        $enrlstudcountfourth = StudEnrolmentHistory::where('program_en_history.studentID', 'NOT LIKE', '%-G%')
                            ->where('program_en_history.schlyear', 'LIKE', $schlyearactive)
                            ->where('program_en_history.semester', 'LIKE', $semesteractive)
                            ->where('program_en_history.studYear', '=', '4')
                            ->where('program_en_history.campus', '=', $userCampus)
                            ->whereIn('program_en_history.status',  [2, 3])
                            ->count();

        return view('ossas.index', compact('collegesFirstSemester', 'collegesSecondSemester', 'semesteractive', 'prevsemesteractive', 'schlyearactiveYear', 'previousSchlyearYear',  'enrlstudcountfirst', 'enrlstudcountsecond', 'enrlstudcountthird', 'enrlstudcountfourth'));
    }

    public function store()
    {
        return view('ossas.rfidreg.add');
    }

    private function encryptStudId($string)
    {
        $key = 'fA7xB93kL0pTzWmQ';
        $cipher = 'AES-128-ECB';

        return rtrim(strtr(
            base64_encode(openssl_encrypt($string, $cipher, $key, 0)),
            '+/',
            '-_'
        ), '=');
    }

    public function getossaStudentById($id)
    {
        $campus = Auth::guard('web')->user()->campus;
        $campusArray = array_map('trim', explode(',', $campus));
        
        $student = Student::join('program_en_history', 'students.stud_id', '=', 'program_en_history.studentID')
            ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
            ->where('students.stud_id', $id)
            ->where(function ($q) use ($campusArray) {
                foreach ($campusArray as $campus) {
                    $q->orWhereRaw("FIND_IN_SET(?, REPLACE(students.campus, ' ', ''))", [$campus]);
                }
            })
            ->select([
                'students.stud_id',
                'students.fname',
                'students.mname',
                'students.lname',
                'students.ext',
                'students.civil_status',
                'students.address',
                'students.gender',
                'students.bday',
                'students.contact',
                'coasv2_db_schedule.programs.progAcronym'
            ])
            ->first();

        if ($student) {
            $student->encrypted_id = $this->encryptStudId($student->stud_id);
            return response()->json($student);
        } else {
            return response()->json(['error' => 'Student not found'], 404);
        }
    }

    public function create(Request $request) 
    {
        if ($request->isMethod('post')) {

            $request->validate([
                'stdntid' => 'required',
                //'stdntrfid' => 'required',
            ]);

            $studidName = $request->input('stdntid'); 
            //$studidRFID = $request->input('stdntrfid');
            $base64Image = $request->input('studphoto'); 
            $base64Signature = $request->input('studsignature');
            //$encryptedRFID = Hash::make($studidRFID);

            // $existingStudentID = StudentRFID::where('stdntid', $studidName)->first();
            // if ($existingStudentID) {
            //     return response()->json([
            //         'error' => true, 
            //         'message' => 'This Student ID already has an RFID assigned.'
            //     ], 409);
            // }

            // $existingRFID = StudentRFID::where('stdntrfid', $encryptedRFID)->first();
            // if ($existingRFID) {
            //     return response()->json([
            //         'error' => true,
            //         'message' => 'This RFID is already assigned to another Student ID.'
            //     ], 409);
            // }

            try {
                $imagePath = null;
                $signaturePath = null;
                $year = date('Y');

                if ($base64Image) {
                    $image = str_replace('data:image/png;base64,', '', $base64Image);
                    $image = str_replace(' ', '+', $image);

                    $folderPath = storage_path('app/public/studentPhotos/' . $year);

                    if (!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, 0755, true);
                    }

                    $imageName = $studidName . '_' . time() . '.png';
                    File::put($folderPath . '/' . $imageName, base64_decode($image));
                    $imagePath = 'studentPhotos/' . $year . '/' . $imageName;
                }

                if ($base64Signature) {
                    $signature = str_replace('data:image/png;base64,', '', $base64Signature);
                    $signature = str_replace(' ', '+', $signature);

                    $folderPath = storage_path('app/public/studentSignatures/' . $year);

                    if (!File::exists($folderPath)) {
                        File::makeDirectory($folderPath, 0755, true);
                    }

                    $signatureName = $studidName . '_signature_' . time() . '.png';
                    File::put($folderPath . '/' . $signatureName, base64_decode($signature));

                    $signaturePath = 'studentSignatures/' . $year . '/' . $signatureName;
                }

                StudentRFID::create([
                    'stdntid' => $studidName,
                    //'stdntrfid' => $encryptedRFID,
                    'studphoto' => $imagePath, 
                    'studsignature' => $signaturePath,
                    'campus' => Auth::guard('web')->user()->campus,
                    'postedBy' => Auth::guard('web')->user()->id
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Stored successfully'
                ], 200);

            } catch (\Exception $e) {
                return response()->json([
                    'error' => true,
                    'message' => 'Failed to store'
                ], 500);
            }
        }
    }

    public function verifyStudentIDrfid()
    {
        return view('ossas.rfidreg.verifyrfid');
    }
    
    public function verifyByRFID(Request $request)
    {
        $plainRfid = trim($request->input('stdntrfid', ''));

        if (empty($plainRfid)) {
            return response()->json([
                'success' => false,
                'message' => 'RFID is required'
            ], 400);
        }
        //try {
            $rfid = StudentRFID::all()->first(function ($item) use ($plainRfid) {
                return Hash::check($plainRfid, $item->stdntrfid);
            });

            if (!$rfid) {
                return response()->json([
                    'success' => false,
                    'message' => 'RFID card not registered'
                ], 404);
            }
            $studentId = $rfid->stdntid;

            if (empty($studentId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No student linked to this RFID'
                ], 400);
            }

            $student = Student::join('program_en_history', 'students.stud_id', '=', 'program_en_history.studentID')
                    ->leftJoin('coasv2_db_schedule.programs', 'program_en_history.progCod', '=', 'coasv2_db_schedule.programs.progCod')
                    ->where('students.stud_id', $studentId)
                    ->select([
                        'students.stud_id as studntid',
                        'students.fname',
                        'students.mname',
                        'students.lname',
                        DB::raw("TRIM(CONCAT(fname, ' ', COALESCE(mname, ''), ' ', lname)) as fullname"),
                        'coasv2_db_schedule.programs.progAcronym as progcourse',
                        'students.gender',
                        'students.civil_status',
                        'students.address',
                ])
                ->first();

            if (!$student) {
                return response()->json([
                    'success' => false,
                    'message' => 'Student record not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'student' => $student
            ]);

        //} catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred',
                'error'   => $e->getMessage() 
            ], 500);

        //} catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Server error while processing request'
            ], 500);
        //}
    }
}
