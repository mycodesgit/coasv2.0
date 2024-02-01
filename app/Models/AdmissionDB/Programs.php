<?php

namespace App\Models\AdmissionDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programs extends Model
{
    use HasFactory;

    protected $table = 'ad_programs';

    protected $fillable = [
        'campus', 
        'code', 
        'program', 
    ];
}
