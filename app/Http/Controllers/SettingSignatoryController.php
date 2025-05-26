<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\SettingDB\GradesheetSignatory;

class SettingSignatoryController extends Controller
{
    public function gradesheetSignatoryRead()
    {   
        return view('control.settings.signatory.listsignatories');
    }
}
