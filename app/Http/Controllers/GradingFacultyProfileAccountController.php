<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

use App\Models\ScheduleDB\College;
use App\Models\ScheduleDB\Department;
use App\Models\ScheduleDB\Faculty;
use App\Models\ScheduleDB\FacultyDesignation;

class GradingFacultyProfileAccountController extends Controller
{
    public function index()
    {
        $data = $this->getActiveFacultyDesignationData();
        $authfacdesig = $data['authfacdesig'];

        $authfaculty = Faculty::join('college', 'faculty.faccollege', '=', 'college.college_abbr')
            ->leftJoin('department', 'faculty.facdept', '=', 'department.deptCod')
            ->leftJoin('fac_designation', 'faculty.id', '=', 'fac_designation.fac_id')
            ->select('faculty.*', 'college.college_name', 'department.deptName', 'fac_designation.designation')
            ->where('faculty.id', Auth::guard('faculty')->user()->id)
            ->first();

        return view('grading.gradesheet.faculty.profile.account', compact('authfacdesig', 'authfaculty'));
    }
}
