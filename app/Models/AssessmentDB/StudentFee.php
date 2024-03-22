<?php

namespace App\Models\AssessmentDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentFee extends Model
{
    use HasFactory;

    protected $connection = 'assessment';
    protected $table = 'student_fee';

    protected $fillable = [
        'prog_Code',
        'yrlevel',
        'schlyear',
        'semester',
        'campus',
        'fundname_code',
        'amountFee',
        'accountName',
        'remember_token', 
    ];
}
