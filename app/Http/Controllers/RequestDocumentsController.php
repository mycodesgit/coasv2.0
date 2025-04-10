<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestDocumentsController extends Controller
{
    public function index()
    {
        return view('reqdocs.index');
    }
}
