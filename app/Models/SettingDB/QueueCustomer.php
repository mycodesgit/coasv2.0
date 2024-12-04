<?php

namespace App\Models\SettingDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QueueCustomer extends Model
{
    use HasFactory;

    protected $connection = 'settings';
    protected $table = 'customers';

    protected $fillable = [
        'queue_number', 
        'counter_id', 
        'status'
    ];

    public function counter()
    {
        return $this->belongsTo(Counter::class);
    }
}
