<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ControlController extends Controller
{
    public function getGuard()
    {
        if(\Auth::guard('web')->check()) {
            return 'web';
        } elseif(\Auth::guard('faculty')->check()) {
            return 'faculty';
        }
    }

    public function master()
    {
        $guard= $this->getGuard();
        return view('layouts.master', compact('guard'));
    }

    public function home()
    {
        $guard= $this->getGuard();
        return view('control.home', compact('guard'));
    }
    
    public function logout()
    {
        if (\Auth::guard('web')->check() || \Auth::guard('faculty')->check()) {
            auth()->guard('web')->logout();
            auth()->guard('faculty')->logout();
            return redirect()->route('login')->with('success', 'You have been Successfully Logged Out');
        } else {
            return redirect()->route('home')->with('error', 'No authenticated user to log out');
        }
    }
}
