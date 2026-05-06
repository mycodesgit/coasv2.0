<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentForgotPassController extends Controller
{
    public function index()
    {
        return view('studforgotpass');
    }
}
