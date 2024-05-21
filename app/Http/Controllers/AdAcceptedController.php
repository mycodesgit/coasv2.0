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
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ApplicantDocs;
use App\Models\AdmissionDB\DeptRating;
use App\Models\AdmissionDB\ExamineeResult;
use App\Models\AdmissionDB\Strands;

use App\Models\EnrollmentDB\Student;

class AdAcceptedController extends Controller
{
    public function applicant_accepted()
    {
        $strand = Strands::all();
        return view('admission.acceptedapp.index', compact('strand'));
    }

    public function srchacceptedList(Request $request)
    {
        $strand = Strands::all();
        return view('admission.acceptedapp.acceptedlist_search', compact('strand'));
    }

    public function getsrchacceptedListapp(Request $request)
    {   
        
        $year = $request->query('year');
        $campus = $request->query('campus');
        $strand = $request->query('strand');
        $user = Auth::guard('web')->user()->dept;

        $query = Applicant::join('ad_applicant_dept_rating', 'ad_applicant_admission.id', '=', 'ad_applicant_dept_rating.app_id')
                        ->select('ad_applicant_admission.*', 'ad_applicant_admission.id as adid', 'ad_applicant_admission.strand as appstrand', 'ad_applicant_dept_rating.*')
                        ->where('ad_applicant_admission.year', $year)
                        ->where('ad_applicant_admission.campus', $campus)
                        ->where('ad_applicant_dept_rating.deptcol', $user)
                        ->whereIn('p_status', [5, 6]);
        if ($strand) {
            $query->where('ad_applicant_admission.strand', $strand);
        }

        $data = $query->get();

        return response()->json(['data' => $data]);
    }

    // public function applicant_enrolled()
    // {
    //     $confirm = Applicant::orderBy('admission_id', 'desc')->where('p_status', '=', 6)->get();
    //     return view('admission.enrolled.list_enrolled')
    //     ->with('confirm', $confirm);
    // }

    // public function srchacceptedEnrolledList(Request $request)
    // {
    //     $data = Applicant::where('p_status', '=', 6)->get();
    //     if ($request->year){$data = $data->where('year',$request->year);}
    //     if ($request->campus){$data = $data->where('campus',$request->campus);}
    //     if ($request->admission_id){$data = $data->where('admission_id',$request->admission_id);}
    //     if ($request->lname){$data = $data->where('lname',$request->lname);}
    //     if ($request->strand){$data = $data->where('strand',$request->strand);}
    //     $request->session()->put('recent_search', $data);
    //     $totalSearchResults = count($data);
    //     return view('admission.enrolled.list_enrolled_search', ['data' => $data,'totalSearchResults' => $totalSearchResults] );
    // }

    public function save_enroll_applicant(Request $request)
    {
        $decryptedId = Crypt::decrypt($request->input('id'));
        $applicant = Applicant::findOrFail($decryptedId);

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
        
        //$enrollmentStudent->year = $applicant->year;
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
        $enrollmentStudent->award = $applicant->award;
        $enrollmentStudent->image = $applicant->image;
        $enrollmentStudent->created_at = Carbon::now();

        $enrollmentStudent->save();

        $applicant->en_status = 2;
        $applicant->p_status = 6;
        $applicant->updated_at = Carbon::now();
        $applicant->update();
        //return back()->with('success', 'Applicant can now proceed to enrollment');
        return response()->json(['success' => true, 'message' => 'Applicant can now proceed to enrollment'], 200);
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
}
