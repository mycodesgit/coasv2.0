<?php

namespace App\Models\AdmissionDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeptRating extends Model
{
    use HasFactory;

    protected $table = 'ad_applicant_dept_rating';

    protected $fillable = [
        'app_id',
        'camp',
        'admission_id', 
        'interviewer',
        'rating', 
        'remarks',
        'course',
        'reason',
    ];
}
