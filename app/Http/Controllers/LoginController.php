<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
            // 'email' => 'required|email',
            // 'password' => 'required|min:5|max:20',
            'email' => 'required_without:studid|email',
            'studid' => 'required_without:email',
        ]);

        // Attempt login for both 'web' and 'faculty' guards
        $validatedUser = auth()->guard('web')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $validatedStudent = auth()->guard('kioskstudent')->attempt([
            'studid' => $request->studid,
            'password' => $request->password,
        ]);

        if ($validatedUser) {
            return redirect()->route('home')->with('success', 'You have successfully logged in.');
        } 
        elseif($validatedStudent) {
            return redirect()->route('kioskhome')->with('success', 'You have successfully logged in.');
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

        $validatedStudent = auth()->guard('kioskstudent')->attempt([
            'studid' => $request->studid,
            'password' => $request->password,
        ]);

        if($validatedStudent) {
            return redirect()->route('kioskhome')->with('success', 'You have successfully logged in.');
        } 
        else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }

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
