<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Auth;

use App\Models\SettingDB\ConfigureCurrent;
use App\Models\ScheduleDB\FacDesignation;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getActiveFacultyDesignationData()
    {
        $activeConfig = ConfigureCurrent::where('set_status', 3)->first();

        if (!$activeConfig) {
            return null;
        }

        $previousConfig = ConfigureCurrent::where('id', '<', $activeConfig->id)
            ->orderBy('id', 'desc')
            ->first();

        $schlyear = $activeConfig->schlyear;
        $semester = $activeConfig->semester;

        $authfacdesig = FacDesignation::where('fac_id', Auth::guard('faculty')->user()->id)
            ->where('schlyear', $schlyear)
            ->where('semester', $semester)
            ->pluck('fac_id');

        return [
            'activeConfig' => $activeConfig,
            'previousConfig' => $previousConfig,
            'schlyear' => $schlyear,
            'semester' => $semester,
            'authfacdesig' => $authfacdesig,
        ];
    }
}
