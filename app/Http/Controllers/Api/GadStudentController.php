<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

use App\Models\SettingDB\Campus;

class GadStudentController extends Controller
{
    public function genderCount() 
    {
        $allcampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();

        $maincampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'MC')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();
 
        $byCampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->join('coasv2_db_settings.campus', 'students.campus', '=', 'coasv2_db_settings.campus.code')
            ->select('coasv2_db_settings.campus.name', 'students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('coasv2_db_settings.campus.name', 'students.gender')
            ->get();
 
        return response()->json([
            'allcampus' => $allcampus,
            'maincampus' => $maincampus,
            'bycampus' => $byCampus
        ]);
    }
}
