<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\Programs;
use App\Models\AdmissionDB\Strands;
use App\Models\AdmissionDB\ExamineeResult;

use App\Models\EnrollmentDB\Student;


class AdConfirmController extends Controller
{
    public function examinee_confirm()
    {
        $strand = Strands::all();
        return view('admission.conapp.list', compact('strand'));
    }

    public function srchconfirmList(Request $request)
    {
        $strand = Strands::all();
        $preference_1 = $applicant->preference_1 ?? null;
        $preference_2 = $applicant->preference_2 ?? null;

        $program = Programs::where('code', $preference_1)
            ->orWhere('code', $preference_2)
            ->orderBy('id', 'asc')
            ->get();

        return view('admission.conapp.list-search', compact('strand', 'program'));
    }

    public function getsrchconfirmList(Request $request)
    {
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');

        $query  = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_applicant_dept_rating.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->where('p_status', '=', 4);

        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }

    public function getCampPrograms(Request $request)
    {
        $campus = $request->input('campus');
        $programs = Programs::where('campus', 'like', '%' . $campus . '%')->get();

        return response()->json($programs);
    }

    public function deptInterview($id)
    {
        $appID = decrypt($id);
        $applicant = Applicant::find($appID);
        $preference_1 = $applicant->preference_1 ?? null;
        $preference_2 = $applicant->preference_2 ?? null;

        $selectedProgram = $applicant->course;
        //$program = Programs::orderBy('id', 'asc')->where('campus', '=', Auth::user()->campus)->get();
        $program = Programs::where('code', $preference_1)
            ->orWhere('code', $preference_2)
            ->orderBy('id', 'asc')
            ->get();

        return view('admission.conapp.deptinterview')
                ->with('applicant', $applicant)
                ->with('program', $program)
                ->with('selectedProgram', $selectedProgram);
    }

    public function accept($id)
    {
        $applicant = Applicant::findOrFail($id);
        $assign = ExamineeResult::where('admission_id', '=', $applicant->admission_id)->get();
        $per = ExamineeResult::where('admission_id', '=', $applicant->admission_id)->get();
        return view('admission.conapp.listappaccept')->with('applicant',$applicant )->with('assign',$assign)->with('per',$per);
    }

    public function save_applicant_rating(Request $request, $id)
    {
        $dt = Carbon::now();
        $applicant = Applicant::findOrFail($id);
        $applicant->update([
            'course' => $request->input('course'),
        ]);
        $assign = DeptRating::where('admission_id', $applicant->admission_id)
        ->update([
            'interviewer' => Auth::user()->fname . ' ' .Auth::user()->lname ,
            'rating' => $request->input('rating'), 
            'remarks' => $request->input('remarks'),
            'course' => $request->input('course'),
            'reason' => $request->input('reason'),
            'created_at' => $dt,
        ]);
        return Redirect::route('deptInterview', encrypt($id))->with('success','The applicant interview result has been saved');
    }

    public function save_accepted_applicant($id)
    {
        $applicant = Applicant::findOrFail($id);
        $applicant->p_status = 5;
        $dt = Carbon::now();  
        $applicant->updated_at = $dt;
        $applicant->update();
        return back()->with('success', 'Applicant has been accepted for enrolment');
    }

    public function accepted_push_enroll_applicant($id)
    {
        $appID = decrypt($id);
        $applicant = Applicant::findOrFail($appID);

        return view('admission.acceptedapp.push_applcnt', compact('applicant'));
    }

    public function save_enroll_applicant($id)
    {
        $applicant = Applicant::findOrFail($id);

        $existingApplicant = Student::where('stud_id', $applicant->admission_id)
            ->orWhere(function ($query) use ($applicant) {
                $query->where('fname', $applicant->fname)
                    ->where('mname', $applicant->mname)
                    ->where('lname', $applicant->lname);
            })
            ->first();

        if ($existingApplicant) {
            return Redirect::back()
                ->withInput()
                ->with('fail', 'Error: Name or Student ID already exists!');
        }

        $enrollmentStudent = new Student();
        
        $enrollmentStudent->year = $applicant->year;
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
        $enrollmentStudent->course = $applicant->course;
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
        $enrollmentStudent->award = $applicant->award;
        $enrollmentStudent->image = $applicant->image;
        $enrollmentStudent->created_at = Carbon::now();

        $enrollmentStudent->save();

        $applicant->en_status = 2;
        $applicant->updated_at = Carbon::now();
        $applicant->update();
        return back()->with('success', 'Applicant can now proceed to enrollment');
    }

    protected function generateAdmissionId($baseId, $campus)
    {
        $campusLetter = $this->getCampusLetter($campus);
        $formattedId = substr($baseId, 0, 4) . '-' . substr($baseId, 4) . '-' . $campusLetter;

        return $formattedId;
    }


    protected function getCampusLetter($campus)
    {
        $campusMappings = [
            'MC' => 'K',
            'CC' => 'C',
            'HIN' => 'N',
        ];
        return $campusMappings[$campus] ?? 'X';
    }
}
