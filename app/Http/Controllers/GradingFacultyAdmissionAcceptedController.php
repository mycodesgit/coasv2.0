<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\Year;

use App\Models\EnrollmentDB\Student;

use App\Models\SettingDB\ConfigureCurrent;

class GradingFacultyAdmissionAcceptedController extends Controller
{
    public function index()
    {
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        $curryear = Year::orderBy('adyear', 'DESC')->get();
        
        $strand = Strands::all();

        return view('grading.gradesheet.faculty.admssion.accepted', compact('authfacdesig', 'curryear', 'strand'));
    }

    public function store(Request $request)
    {
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        $curryear = Year::orderBy('adyear', 'DESC')->get();

        $strand = Strands::all();

        $preference_1 = $applicant->preference_1 ?? null;
        $preference_2 = $applicant->preference_2 ?? null;

        $program = Programs::where('code', $preference_1)
            ->orWhere('code', $preference_2)
            ->orderBy('id', 'asc')
            ->get();

        return view('grading.gradesheet.faculty.admssion.acceptedresult', compact('authfacdesig', 'curryear', 'strand', 'program'));
    }

    public function show(Request $request)
    {   
        $year = $request->query('year');
        $campus = Auth::guard('faculty')->user()->campus;
        $strand = $request->query('strand');
        $user = Auth::guard('faculty')->user()->faccollege;

        $query = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                ->leftJoin('coasv2_db_enrollment.students', 'ad_applicant_admission.id', '=', 'coasv2_db_enrollment.students.app_id')
                ->select(
                    'ad_applicant_admission.*', 
                    'ad_applicant_admission.id as adid', 
                    'ad_applicant_admission.strand as appstrand', 
                    'ad_applicant_dept_rating.*', 
                    'coasv2_db_enrollment.students.stud_id'
                )
                ->where('ad_applicant_admission.year', $year)
                ->where('ad_applicant_admission.campus', $campus)
                //->whereYear('coasv2_db_enrollment.students.created_at', now()->year)
                ->where('ad_applicant_dept_rating.deptcol', $user)
                ->whereIn('ad_applicant_admission.p_status', [5, 6]);

        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        $data->transform(function ($item) {
            $item->adid = Crypt::encryptString($item->adid);
            return $item;
        });

        return response()->json(['data' => $data]);
    }

    public function savefacenroll_applicant(Request $request)
    {
        $decryptedId = Crypt::decryptString($request->input('id'));
        $applicant = Applicant::findOrFail($decryptedId);

        $existingApplicant = Student::where('stud_id', $applicant->admission_id)
            ->orWhere(function ($query) use ($applicant) {
                $query->where('fname', $applicant->fname)
                    ->where('mname', $applicant->mname)
                    ->where('lname', $applicant->lname)
                    ->where('campus', Auth::guard('faculty')->user()->campus);
            })
            ->first();

        if ($existingApplicant) {
            return Redirect::back()
                ->withInput()
                ->with('fail', 'Error: Name or Student ID already exists!');
        }

        $enrollmentStudent = new Student();
        $enrollmentStudent->stud_id = $this->generateAdmissionId($applicant->admission_id, $applicant->campus);
        $enrollmentStudent->app_id = $applicant->id;
        $enrollmentStudent->status = $applicant->status;
        $enrollmentStudent->en_status = 2;
        $enrollmentStudent->p_status = $applicant->p_status;
        $enrollmentStudent->type = $applicant->type;
        $enrollmentStudent->campus = $applicant->campus;
        $enrollmentStudent->lname = $applicant->lname;
        $enrollmentStudent->fname = $applicant->fname;
        $enrollmentStudent->mname = $applicant->mname;
        $enrollmentStudent->ext = $applicant->ext;
        $enrollmentStudent->gender = $applicant->gender;
        $enrollmentStudent->civil_status = $applicant->civil_status;
        $enrollmentStudent->contact = $applicant->contact;
        $enrollmentStudent->email = $applicant->email;
        $enrollmentStudent->religion = $applicant->religion;
        $enrollmentStudent->address = $applicant->address;
        $enrollmentStudent->bday = $applicant->bday;
        $enrollmentStudent->pbirth = $applicant->pbirth;
        $enrollmentStudent->monthly_income = $applicant->monthly_income;
        $enrollmentStudent->hnum = $applicant->hnum;
        $enrollmentStudent->brgy = $applicant->brgy;
        $enrollmentStudent->city = $applicant->city;
        $enrollmentStudent->province = $applicant->province;
        $enrollmentStudent->region = $applicant->region;
        $enrollmentStudent->zcode = $applicant->zcode;
        $enrollmentStudent->lstsch_attended = $applicant->lstsch_attended;
        $enrollmentStudent->suc_lst_attended = $applicant->suc_lst_attended;
        $enrollmentStudent->stud_pic = $applicant->stud_pic;
        $enrollmentStudent->created_at = Carbon::now();

        $enrollmentStudent->save();

        $applicant->en_status = 2;
        $applicant->p_status = 6;
        $applicant->updated_at = Carbon::now();
        $applicant->update();

        return response()->json(['success' => true, 'message' => 'Applicant can now proceed to enrollment'], 200);
    }

    protected function generateAdmissionId($baseId, $campus)
    {
        $campusLetter = $this->getCampusLetter($campus);
        $year = substr($baseId, 0, 4);

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
        $role = Auth::guard('faculty')->user()->role;
        
        $campusMappings = [
            'MC' => $role == 15 ? 'G' : 'K',
            'VC' => 'V',
            'SCC' => 'R',
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
}
