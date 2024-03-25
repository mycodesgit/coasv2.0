<?php

namespace App\Models\ScheduleDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubjectOffered extends Model
{
    use HasFactory;
    protected $connection = 'schedule';
    protected $table = 'sub_offered';

    protected $fillable = [
        'subCode',
        'subSec', 
        'schlyear', 
        'semester',
        'campus',
        'lecFee',
        'labFee',
        'subUnit',
        'postedBy',
        'datePosted',
        'lecUnit',
        'labUnit',
        'maxstud',
        'isOJT',
        'isTemp',
        'isType',
        'fund',
        'fundAccount',
    ];
}
