<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\EnrollmentDB\Student;
use App\Models\SettingDB\Campus;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function loginkioskstud()
    {
        return view('loginkiosk');
    }

    public function loginextkioskstud()
    {
        return view('loginkioskext');
    }

    public function loginstudonline()
    {
        return view('loginstudent');
    }

    public function adminloginme()
    {
        return view('loginzeusadmin');
    }

    public function emp_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5|max:20',
        ]);

        $validatedUser = auth()->guard('web')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($validatedUser) {
            return redirect()->route('home')->with('success', 'You have successfully logged in.');
        } 
        else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }

    public function stud_login(Request $request)
    {
        $request->validate([
            'studid' => 'required',
            'password' => 'required|min:5|max:20',
        ]);

        $student = Student::where('stud_id', $request->studid)->first();

        if (!$student) {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }

        $campusCodes = array_map('trim', explode(',', $student->campus));
        $latestCampusCode = end($campusCodes);

        $campus = Campus::where('code', $latestCampusCode)
            ->where('login_enabled', 1)
            ->first();

        if (!$campus) {
            return redirect()->back()->with('error', 'Login is currently disabled for your campus.');
        }
        $validatedStudent = auth()->guard('kioskstudent')->attempt([
            'studid' => $request->studid,
            'password' => $request->password,
        ]);

        if($validatedStudent) {
            return redirect()->route('index.student')->with('success', 'You have successfully logged in.');
        } 
        else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }

    // public function stud_login(Request $request)
    // {
    //     $request->validate([
    //         'studid' => 'required',
    //         'password' => 'required|min:5|max:20',
    //     ]);

    //     $student = \App\Models\EnrollmentDB\Student::where('stud_id', $request->studid)->first();
        
    //     if ($student && $student->campus === 'MC') {
    //         $validatedStudent = auth()->guard('kioskstudent')->attempt([
    //             'studid' => $request->studid,
    //             'password' => $request->password,
    //         ]);

    //         if($validatedStudent) {
    //             return redirect()->route('index.student')->with('success', 'You have successfully logged in.');
    //         } 
    //         else {
    //             return redirect()->back()->with('error', 'Invalid Credentials');
    //         }
    //     } else {
    //         return redirect()->back()->with('error', 'Access restricted to Main campus students only.');
    //     }
    // }

    public function extensionstud_login(Request $request)
    {
        $request->validate([
            'studid' => 'required',
            'studid' => 'required',
        ]);

        $validatedextensionStudent = auth()->guard('kioskstudent')->attempt([
            'studid' => $request->studid,
            'password' => $request->password,
        ]);
        
        if($validatedextensionStudent) {
            return redirect()->route('kioskhome')->with('success', 'You have successfully logged in.');
        } 
        else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }

}
