<?php

namespace App\Models\AdmissionDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassEnroll extends Model
{
    use HasFactory;
    protected $table = 'classenroll';

    protected $fillable = [
        'class_semester', 
        'class', 
        'class_year', 
        'class_section', 
    ];
}
