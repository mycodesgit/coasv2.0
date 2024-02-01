<?php

namespace App\Models\ScheduleDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassEnroll extends Model
{
    use HasFactory;

    protected $connection = 'schedule';
    protected $table = 'class_enroll';

    protected $fillable = [
        'prog_id',
        'schlyear', 
        'semester', 
        'campus',
        'class', 
        'class_section', 
    ];
}
