<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;

use Storage;
use Carbon\Carbon;
use App\Models\AdmissionDB\Applicant;
use App\Models\AdmissionDB\ClassEnroll;

class AdCaptureImageController extends Controller
{
    public function applicant_capture_image($id)
    {
        $applicant = Applicant::find($id);
        return view('admission.applicant.capture_image')
        ->with('applicant', $applicant);
    }  
    public function applicant_save_image(Request $request, $id)
    {
        $image = Applicant::find($id);
        $folderPath = "public/capture_images/";
        $img = $request->image;
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = uniqid() . '.jpg';
        $file = $folderPath . $fileName;
        Storage::put($file, $image_base64);
        $image->image = $fileName;
        $dt = Carbon::now();    
        $image->updated_at = $dt;
        $image->update();
        return back()->with('success', 'Success in capturing applicant image.');
    } 
}
