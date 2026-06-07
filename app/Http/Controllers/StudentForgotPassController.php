<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\KioskUser;
use App\Models\EnrollmentDB\KioskLogs;
use App\Models\EnrollmentDB\StudResetPass;

class StudentForgotPassController extends Controller
{
    public function index()
    {
        return view('studforgotpass');
    }

    // Step 1: Verify Student ID and Email match from Student model
    public function verifyStudent(Request $request)
    {
        $request->validate([
            'stud_id' => 'required',
            'email' => 'required|email'
        ]);
        
        // Find student in Student model (information only)
        $student = Student::where('stud_id', $request->stud_id)
                          ->where('email', $request->email)
                          ->first();
        
        if (!$student) {
            // Log failed attempt
            Log::warning('Password reset verification failed', [
                'stud_id' => $request->stud_id,
                'email' => $request->email,
                'ip' => $request->ip()
            ]);
            
            return back()->with('error', 'No matching student found. Please check your Student ID and Email.');
        }
        
        // Check for excessive attempts (security)
        $attemptKey = 'reset_attempts_' . $request->stud_id;
        $attempts = Cache::get($attemptKey, 0);
        
        if ($attempts >= 5) {
            return back()->with('error', 'Too many reset attempts. Please try again after 30 minutes.');
        }
        
        // Check if KioskUser account exists for this student
        $kioskUser = KioskUser::where('studid', $request->stud_id)->first();
        
        if (!$kioskUser) {
            return back()->with('error', 'No login account found for this student. Please contact administrator.');
        }
        
        return view('studforgotpass', [
            'student' => $student,
            'hasAccount' => true
        ]);
    }
    
    // Step 2: Generate and send OTP
    public function sendOTP(Request $request)
    {
        $request->validate([
            'stud_id' => 'required',
            'email' => 'required|email'
        ]);
        
        // Verify student exists
        $student = Student::where('stud_id', $request->stud_id)
                          ->where('email', $request->email)
                          ->first();
        
        if (!$student) {
            return redirect()->route('reset-password')->with('error', 'Student verification failed.');
        }
        
        // Verify kiosk user exists
        $kioskUser = KioskUser::where('studid', $request->stud_id)->first();
        if (!$kioskUser) {
            return redirect()->route('reset-password')->with('error', 'No login account found.');
        }
        
        // Generate 6-digit OTP
        $otp = rand(100000, 999999);
        $token = Hash::make($otp . $student->stud_id . now()->timestamp);
        
        // Store OTP in cache with 15-minute expiry
        $cacheKey = 'password_reset_' . $student->stud_id;
        Cache::put($cacheKey, [
            'otp_hash' => Hash::make($otp),
            'token' => $token,
            'attempts' => 0,
            'stud_id' => $student->stud_id,
            'email' => $student->email,
            'created_at' => now()
        ], now()->addMinutes(15));
        
        // Send email with OTP
        try {
            Mail::send('emails.password-reset-otp', [
                'name' => $student->firstname . ' ' . $student->lastname,
                'otp' => $otp,
                'expiry' => '15 minutes',
                'stud_id' => $student->stud_id
            ], function($message) use ($student) {
                $message->to($student->email)
                        ->subject('Password Reset OTP - CISS V.1.0');
            });
            
            // Increment attempt counter for rate limiting
            $attemptKey = 'reset_attempts_' . $student->stud_id;
            Cache::increment($attemptKey);
            Cache::put($attemptKey, Cache::get($attemptKey, 0), now()->addMinutes(30));
            
            Log::info('Password reset OTP sent', [
                'stud_id' => $student->stud_id,
                'email' => $student->email,
                'ip' => $request->ip()
            ]);
            
            return view('studforgotpass', [
                'student' => $student,
                'reset_token' => $token
            ])->with('success', 'A 6-digit OTP has been sent to your registered email. Valid for 15 minutes.');
            
        } catch (\Exception $e) {
            Log::error('Failed to send password reset OTP', [
                'stud_id' => $student->stud_id,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('error', 'Failed to send OTP. Please try again later or contact support.');
        }
    }
    
    // Step 3: Verify OTP and update password in KioskUser model
    public function updatePassword(Request $request)
    {
        $request->validate([
            'stud_id' => 'required',
            'token' => 'required',
            'otp' => 'required|digits:6',
            'new_password' => [
                'required',
                'string',
                'min:8',
                'regex:/[A-Z]/',     // at least 1 uppercase
                'regex:/[a-z]/',     // at least 1 lowercase
                'regex:/[0-9]/',     // at least 1 number
                'regex:/[!@#$%^&*]/', // at least 1 special character
                'confirmed'
            ],
            'confirm_password' => 'required'
        ], [
            'new_password.regex' => 'Password must contain at least 1 uppercase, 1 lowercase, 1 number, and 1 special character (!@#$%^&*)'
        ]);
        
        $cacheKey = 'password_reset_' . $request->stud_id;
        $resetData = Cache::get($cacheKey);
        
        // Verify reset session exists
        if (!$resetData || $resetData['token'] !== $request->token) {
            return back()->with('error', 'Invalid or expired reset session. Please start the process again.');
        }
        
        // Verify OTP
        if (!Hash::check($request->otp, $resetData['otp_hash'])) {
            // Increment failed attempts
            $resetData['attempts']++;
            Cache::put($cacheKey, $resetData, now()->addMinutes(15));
            
            $remainingAttempts = 3 - $resetData['attempts'];
            
            if ($resetData['attempts'] >= 3) {
                Cache::forget($cacheKey);
                Log::warning('Password reset OTP attempts exhausted', [
                    'stud_id' => $request->stud_id,
                    'ip' => $request->ip()
                ]);
                return redirect()->route('reset-password')
                    ->with('error', 'Too many invalid OTP attempts. Please restart the password reset process.');
            }
            
            return back()->with('error', "Invalid OTP. {$remainingAttempts} attempt(s) remaining.");
        }
        
        // Update password in KioskUser model
        $kioskUser = KioskUser::where('studid', $request->stud_id)->first();
        
        if (!$kioskUser) {
            Cache::forget($cacheKey);
            return redirect()->route('reset-password')
                ->with('error', 'User account not found. Please contact administrator.');
        }
        
        // Optional: Check password history (prevent reusing last 5 passwords)
        if (method_exists($kioskUser, 'checkPasswordHistory')) {
            if ($kioskUser->checkPasswordHistory($request->new_password)) {
                return back()->with('error', 'You cannot reuse your recent passwords. Please choose a new password.');
            }
        }
        
        // Update password
        $kioskUser->password = Hash::make($request->new_password);
        $kioskUser->password_changed_at = now();
        $kioskUser->save();
        
        // Store password in history (if you have password_history table)
        $this->storePasswordHistory($kioskUser, $request->new_password);
        
        // Clear reset session
        Cache::forget($cacheKey);
        
        // Log successful password change
        Log::notice('Password reset successful', [
            'stud_id' => $request->stud_id,
            'ip' => $request->ip(),
            'timestamp' => now()
        ]);
        
        // Optional: Invalidate all sessions (if using session-based auth)
        // You can add a session token column in KioskUser and regenerate it here
        
        return redirect()->route('login')->with('success', 
            '✓ Password reset successful! Please login with your new password.');
    }
    
    // Resend OTP
    public function resendOTP(Request $request)
    {
        $request->validate([
            'stud_id' => 'required',
            'email' => 'required|email'
        ]);
        
        $student = Student::where('stud_id', $request->stud_id)
                          ->where('email', $request->email)
                          ->first();
        
        if (!$student) {
            return response()->json(['success' => false, 'message' => 'Student not found'], 404);
        }
        
        // Generate new OTP
        $otp = rand(100000, 999999);
        $cacheKey = 'password_reset_' . $student->stud_id;
        $oldData = Cache::get($cacheKey, []);
        
        Cache::put($cacheKey, [
            'otp_hash' => Hash::make($otp),
            'token' => $oldData['token'] ?? Hash::make($otp . $student->stud_id . now()->timestamp),
            'attempts' => 0,
            'stud_id' => $student->stud_id,
            'email' => $student->email,
            'created_at' => now()
        ], now()->addMinutes(15));
        
        // Resend email
        try {
            Mail::send('emails.password-reset-otp', [
                'name' => $student->firstname . ' ' . $student->lastname,
                'otp' => $otp,
                'expiry' => '15 minutes',
                'stud_id' => $student->stud_id
            ], function($message) use ($student) {
                $message->to($student->email)
                        ->subject('New Password Reset OTP - CISS V.1.0');
            });
            
            Log::info('Password reset OTP resent', [
                'stud_id' => $student->stud_id,
                'email' => $student->email
            ]);
            
            return response()->json(['success' => true, 'message' => 'New OTP sent successfully']);
            
        } catch (\Exception $e) {
            Log::error('Failed to resend OTP', [
                'stud_id' => $student->stud_id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json(['success' => false, 'message' => 'Failed to send OTP'], 500);
        }
    }
    
    // Helper method to store password history (optional)
    private function storePasswordHistory($kioskUser, $newPassword)
    {
        // If you have a password_history table
        // \DB::table('password_history')->insert([
        //     'user_id' => $kioskUser->id,
        //     'password' => Hash::make($newPassword),
        //     'created_at' => now()
        // ]);
        
        // Keep only last 5 passwords
        // \DB::table('password_history')->where('user_id', $kioskUser->id)
        //     ->orderBy('created_at', 'desc')
        //     ->skip(5)
        //     ->delete();
    }
}
