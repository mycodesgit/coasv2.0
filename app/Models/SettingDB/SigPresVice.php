<?php

namespace App\Models\SettingDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigPresVice extends Model
{
    use HasFactory;
    protected $connection = 'settings';
    protected $table = 'sigpresvice';

    protected $fillable = [
        'fulname',
        'titledeg',
        'position',
        'schlyear',
        'semester',
        'status',
        'esign',
    ];
}
