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
        $data = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
                ->join('campus', 'program_en_history.campus', '=', 'campus.code') // adjust if needed
                ->where('program_en_history.semester', 2)
                ->where('program_en_history.schlyear', '2025-2026')
                ->whereIn('students.p_status', [5, 6])
                ->whereIn('students.gender', ['Male', 'Female'])
                ->select('campus.name as campus', 'students.gender')
                ->selectRaw('COUNT(*) as count')
                ->groupBy('campus.name', 'students.gender')
                ->get();

            // 👉 Transform into table format
            $formatted = $data->groupBy('campus')->map(function ($items) {
                return [
                    'campus' => $items->first()->campus,
                    'male' => optional($items->where('gender', 'Male')->first())->count ?? 0,
                    'female' => optional($items->where('gender', 'Female')->first())->count ?? 0,
                ];
            })->values();

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
            ->where('program_en_history.campus', '=', 'MC')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();
        
        $victoriascampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'VC')
            ->where('program_en_history.campus', '=', 'VC')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();
        
        $sancarloscampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'SCC')
            ->where('program_en_history.campus', '=', 'SCC')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();
        
        $hinigarancampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'HC')
            ->where('program_en_history.campus', '=', 'HC')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();
        
        $moisepadillacampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'MP')
            ->where('program_en_history.campus', '=', 'MP')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();
        
        $ilogcampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'IC')
            ->where('program_en_history.campus', '=', 'IC')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();
        
        $candonicampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'CA')
            ->where('program_en_history.campus', '=', 'CA')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();

        $cauayancampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'CC')
            ->where('program_en_history.campus', '=', 'CC')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();

        $sipalaycampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'SC')
            ->where('program_en_history.campus', '=', 'SC')
            ->select('students.gender')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('students.gender')
            ->get();
        
        $hinobaancampus = StudEnrolmentHistory::join('students', 'program_en_history.studentID', '=', 'students.stud_id')
            ->where('program_en_history.semester', '=', 2)
            ->where('program_en_history.schlyear', '=', '2025-2026')
            ->whereIn('students.p_status', [5, 6])
            ->whereIn('students.gender', ['Male', 'Female'])
            ->where('students.campus', '=', 'HinC')
            ->where('program_en_history.campus', '=', 'HinC')
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
            'data' => $formatted,
            'allcampus' => $allcampus,
            'maincampus' => $maincampus,
            'victoriascampus' => $victoriascampus,
            'sancarloscampus' => $sancarloscampus,
            'hinigarancampus' => $hinigarancampus,
            'moisepadillacampus' => $moisepadillacampus,
            'ilogcampus' => $ilogcampus,
            'candonicampus' => $candonicampus,
            'cauayancampus' => $cauayancampus,
            'sipalaycampus' => $sipalaycampus,
            'hinobaancampus' => $hinobaancampus,
            'bycampus' => $byCampus
        ]);
    }
}
