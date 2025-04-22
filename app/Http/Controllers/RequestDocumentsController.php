<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

use App\Models\RequestdocsDB\DocsList;

class RequestDocumentsController extends Controller
{
    public function index()
    {
        $docs = DocsList::orderBy('id', 'ASC')->get();
        
        return view('reqdocs.index', compact('docs'));
    }
}
