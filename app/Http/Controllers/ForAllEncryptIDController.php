<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ForAllEncryptIDController extends Controller
{
    public function idcrypt(Request $request) 
    {
        $enrcryptedID = encrypt($request->data);

        return $enrcryptedID;
    }
    
    public function idFacCrypt(Request $request) 
    {
        $enrcryptedFacID = encrypt($request->data);

        return $enrcryptedFacID;
    }
}
