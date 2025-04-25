<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\SettingDB\Region;
use App\Models\SettingDB\Province;
use App\Models\SettingDB\City;
use App\Models\SettingDB\Barangay;

class SettingAddressController extends Controller
{
    public function regionsRead()
    {   
        return view('control.settings.address.listaddress');
    }

    public function getregionsShow()
    {
        $data = Region::orderBy('region_id', 'ASC')->get();

        return response()->json(['data' => $data]);
    }

    public function getprovincesShow()
    {
        $data = Province::join('regions', 'provinces.region_id', '=', 'regions.region_id')
                ->select('provinces.*', 'regions.name as region_name')
                ->orderBy('region_id', 'ASC')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function getcitiesShow()
    {
        $data = City::join('provinces', 'cities.province_id', '=', 'provinces.province_id')
                ->join('regions', 'provinces.region_id', '=', 'regions.region_id')
                ->select('cities.*', 'provinces.name as province_name', 'regions.name as region_name')
                ->orderBy('city_id', 'ASC')
                ->get();

        return response()->json(['data' => $data]);
    }

    public function cityUpdate(Request $request) 
    {
        $request->validate([
            'id' => 'required',
            'name' => 'required',
            'zip_code' => 'required',
        ]);

        try {
            $cityName = $request->input('name');
            $zipCode = $request->input('zip_code');
            $existingCity = City::where('name', $cityName)
                        ->where('zip_code', $request->input('zip_code'))
                        ->where('id', '!=', $request->input('id'))
                        ->first();

            if ($existingCity) {
                return response()->json(['error' => true, 'message' => 'City already exists'], 404);
            }

            $city = City::findOrFail($request->input('id'));
            $city->update([
                'name' => $cityName,
                'zip_code' => $zipCode,
        ]);
            return response()->json(['success' => true, 'message' => 'City update successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => true, 'message' => 'Failed to Update City'], 404);
        }
    }
}
