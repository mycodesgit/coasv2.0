<?php

namespace App\Models\ScheduleDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // Importing the Authenticatable trait
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Faculty extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $connection = 'schedule';
    protected $table = 'faculty';

    protected $fillable = [
        'campus',
        'dept', 
        'fname', 
        'mname',
        'lname', 
        'ext', 
        'email',
        'password', 
        'isAdmin', 
        'rank',
        'adrID', 
        'remember_token', 
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'isAdmin' => 'string',
    ];

    public function hasRole($isAdmin)
    {
        return $this->isAdmin === $isAdmin;
    }
}
