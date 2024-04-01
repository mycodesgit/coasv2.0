<?php

namespace App\Models\AssessmentDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAppraisal extends Model
{
    use HasFactory;

    protected $connection = 'assessment';
    protected $table = 'student_appraisal';

    protected $fillable = [
        'studID',
        'semester',
        'schlyear',
        'campus',
        'fundID',
        'account',
        'dateAssess',
        'amount',
        'postedBy', 
    ];
}
