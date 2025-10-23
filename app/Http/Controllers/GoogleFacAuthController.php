<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\FacultyOtpMail;

use App\Models\ScheduleDB\Faculty;

class GoogleFacAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $google_user = Socialite::driver('google')->user();
            $email = $google_user->getEmail();
        
            $employee = Faculty::where('email', $email)->first();
            
            if (!$employee) {
                return redirect()->back()->with('error', 'We couldn\'t find your email. Please contact MIS for assistance.');
            }
    
            $verification_code = mt_rand(100000, 999999);
    
            if ($employee) {
                $employee->verification_code = $verification_code;
                $employee->save();

                $emailData = [
                    'faculty_name' => $employee->fname . ' ' . $employee->lname,
                    'verification_code' => $verification_code,
                ];
                
                // Mail::raw("Your OTP Code is: $verification_code", function ($message) use ($employee) {
                //     $message->to($employee->email)
                //             ->subject('Verification Code');
                // });
                Mail::to($employee->email)->send(new \App\Mail\FacultyOtpMail($emailData));
    
                session()->flash('email', $employee->email);
            }
    
            return redirect()->route('verify');
    
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'There was an issue with Google OAuth. Please try again.');
        }
    }

    public function verifyForm(Request $request)
    {
        $email = $request->session()->get('email');
        return view('loginfacultyverifycode', compact('email'));
    }

    public function verify(Request $request)
    {
        $verification_code = $request->input('verification_code');
        $email = $request->input('email'); 

        $employee = Faculty::where('email', $email)->first();
        
        if ($employee && $employee->verification_code == $verification_code) {
            $employee->verification_code = null;
            $employee->save();
    
            if ($employee->status == 1) {
                Auth::guard('faculty')->login($employee);
                return redirect()->route('homefaculty')->with('success', 'Login Successfully');
            } else {
                return redirect()->back()->with('error', 'Account Suspended');
            }
        }
    
        return redirect()->back()->with('error', 'Invalid Verification Code');
    }
}
