<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\EnrollmentDB\Student;
use App\Models\EnrollmentDB\KioskUser;
use App\Models\EnrollmentDB\KioskLogs;
use App\Models\EnrollmentDB\StudResetPass;

class PassResetController extends Controller
{
    public function index()
    {
        return view('portal.resetpass');
    }

    public function store(Request $request)
    {
        $request->validate([
            'studid' => 'required|string|max:20',
            'email' => 'required|email|max:100',
        ]);

        $studid = $request->input('studid');
        $email = $request->input('email');

        $student = Student::where('studid', $studid)
                          ->where('email', $email)
                          ->first();

        if (!$student) {
            return redirect()->back()->withErrors(['msg' => 'Student ID and Email do not match our records.'])->withInput();
        }

        StudResetPass::updateOrCreate(
            ['studid' => $studid],
            [
                'email' => $email,
                'fname' => $student->fname,
                'lname' => $student->lname,
            ]
        );

        return redirect()->back()->with('success', 'Password reset request submitted successfully.');
    }
}
