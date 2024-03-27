<?php

namespace App\Models\AssessmentDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAppraisal extends Model
{
    use HasFactory;

    protected $connection = 'assessment';
    protected $table = 'accounts';

    protected $fillable = [
        'studId',
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
