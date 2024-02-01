<?php

namespace App\Models\ScheduleDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $connection = 'schedule';
    protected $table = 'subjects';

    protected $fillable = [
        'sub_code',
        'sub_name', 
        'sub_desc', 
        'sub_unit',
    ];
}
