<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AdmissionDB\ButtonAccess;


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

    public function masterfaculty()
    {
        $guard= $this->getGuard();
        return view('layouts.master_faculty', compact('guard'));
    }

    public function homefaculty()
    {
        $guard= $this->getGuard();
        return view('control.facultyhome', compact('guard'));
    }
    
    public function logout()
    {
        // if (\Auth::guard('web')->check() || \Auth::guard('faculty')->check()) {
        //     auth()->guard('web')->logout();
        //     auth()->guard('faculty')->logout();
        //     return redirect()->route('login')->with('success', 'You have been Successfully Logged Out');
        // } else {
        //     return redirect()->route('home')->with('error', 'No authenticated user to log out');
        // }

        if (\Auth::guard('web')->check()) {
            auth()->guard('web')->logout();
            return redirect()->route('login')->with('success', 'You have been Successfully Logged Out');
        } elseif (\Auth::guard('faculty')->check()) {
            auth()->guard('faculty')->logout();
            return redirect()->route('loginfac')->with('success', 'You have been Successfully Logged Out');
        } elseif (\Auth::guard('kioskstudent')->check()) {
            $user = auth()->guard('kioskstudent')->user(); // Get the authenticated user
            
            if ($user && $user->campus !== 'MC') { // Check if campus is NOT 'MC'
                auth()->guard('kioskstudent')->logout();
                return redirect()->route('loginextkioskstud')->with('success', 'You have been Successfully Logged Out');
            } else {
                auth()->guard('kioskstudent')->logout();
                return redirect()->route('loginkioskstud')->with('success', 'You have been Successfully Logged Out');
            }
        } else {
            // Check for kioskstudent guard before redirecting to home
            if (\Auth::guard('kioskstudent')->check()) {
                return redirect()->route('kioskhome')->with('error', 'No authenticated user to log out');
            }
            return redirect()->route('home')->with('error', 'No authenticated user to log out');
        }
    }
}
