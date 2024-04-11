<?php

namespace App\Models\ScholarshipDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UniSch extends Model
{
    use HasFactory;
    protected $connection = 'scholarship';
    protected $table = 'fscode';

    protected $fillable = [
        'unisch_name',
        
    ];

}
