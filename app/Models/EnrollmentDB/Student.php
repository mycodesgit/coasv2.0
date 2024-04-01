<?php

namespace App\Models\EnrollmentDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $connection = 'enrollment';
    protected $table = 'students';

    protected $fillable = [
        'year',
        'stud_id',
        'app_id', 
        'status', 
        'en_status',
        'p_status',
        'type',
        'fname', 
        'lname', 
        'mname',
        'ext',
        'courseeee',
        'gender',
        'contact',
        'email',
        'address',
        'bday',
        'pbirth',
        'annual_income',
        'level',
        'hnum',
        'brgy',
        'mdc',
        'province',
        'region',
        'zcode',
        'lstsch_attended',
        'suc_lst_attended',
        'award',
        'image',
        'created_at'
    ];
}
