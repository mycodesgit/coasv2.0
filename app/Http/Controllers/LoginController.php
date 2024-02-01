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

    public function emp_login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5|max:20',
        ]);

        // Attempt login for both 'web' and 'faculty' guards
        $validatedUser = auth()->guard('web')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $validatedFaculty = auth()->guard('faculty')->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // if(\Auth::guard('web')->check()) {
        //     return 'web';
        // } elseif(\Auth::guard('faculty')->check()) {
        //     return 'faculty';
        // }

        if ($validatedUser || $validatedFaculty) {
            return redirect()->route('home')->with('success', 'You have successfully logged in.');
        } else {
            return redirect()->back()->with('error', 'Invalid Credentials');
        }
    }

}
