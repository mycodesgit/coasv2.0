<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class LoginFacultyController extends Controller
{
    public function loginfac()
    {
        return view('loginfaculty');
    }

    public function fac_login(Request $request)
    {
        $request->validate([
            'email' => 'required_without:studid|email',
            'password' => 'required',
        ]);

        $validatedFaculty = auth()->guard('faculty')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        if ($validatedFaculty) {
            return redirect()->route('homefaculty')->with('success', 'You have successfully logged in.');
        } 
        else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }
}
