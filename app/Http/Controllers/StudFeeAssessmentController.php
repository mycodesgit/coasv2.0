<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use App\Models\AdmissionDB\Programs;

class StudFeeAssessmentController extends Controller
{
    public function index() 
    {
        return view('assessment.index');
    }

    public function searchStudfee()
    {   
        return view('assessment.studentfee.list_studfee');
    }

    public function list_searchStudfee()
    {   
        $data = ClassEnroll::query();
        if ($request->schlyear) {
            $data->where('schlyear', $request->schlyear);
        }
        if ($request->semester) {
            $data->where('semester', $request->semester);
        }
        if ($request->campus) {
            $data->where('campus', $request->campus);
        }
        $data = $data->get();

        $request->session()->put('recent_search', $data);
        $totalSearchResults = count($data);

        return view('assessment.studentfee.listsearch_studfee');
    }
}
