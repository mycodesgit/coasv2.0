<?php

namespace App\Models\AdmissionDB;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'campus',
        'dept',
        'lname',
        'fname',
        'mname',
        'ext',
        'email',
        'password',
        'role',
        'remember_token'
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

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getIsAdminAttribute($value)
    {
        return (bool) $value;
    }
    public function hasRole($role)
    {
        return $this->role === $role;
    }
    public function buttonAccess(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne('App\Models\SettingDB\ButtonAccess', 'user_id');
    }
    public function hasPermissionToAccess($button)
    {
        $buttonAccess = $this->buttonAccess;
        $buttons = $buttonAccess ? $buttonAccess->buttons : [];
        return in_array($button, $buttons);
    }
}
