<?php

namespace App\Models\ScheduleDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryMode extends Model
{
    use HasFactory;

    protected $connection = 'schedule';
    protected $table = 'subjectdelmode';

    protected $fillable = [
        'delmode', 
        'remember_token',
    ];
}
