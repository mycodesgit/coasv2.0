<?php

namespace App\Models\ScheduleDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacDesignation extends Model
{
    use HasFactory;
    protected $connection = 'schedule';
    protected $table = 'fac_designation';

    protected $fillable = [
        'schlyear', 
        'semester', 
        'campus',
        'facdept',
        'fac_id',
        'designation',
        'rankcomma', 
        'dunit', 
    ];
}
