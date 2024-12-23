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
        'windowname', 
        'category',
        'useridlog',
        'activeidnumber',
        'callid',
        'campus'
    ];

    public function customers()
    {
        return $this->hasOne(QueueCustomer::class, 'id', 'callid');
    }
}
