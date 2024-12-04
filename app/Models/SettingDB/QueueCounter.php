<?php

namespace App\Models\SettingDb;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueCounter extends Model
{
    use HasFactory;

    protected $connection = 'settings';
    protected $table = 'counters';

    protected $fillable = [
        'name', 
        'current_number',
        'campus'
    ];

    public function customers()
    {
        return $this->hasMany(QueueCustomer::class);
    }
}
