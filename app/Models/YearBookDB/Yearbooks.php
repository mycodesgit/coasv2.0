<?php

namespace App\Models\YearBookDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Yearbooks extends Model
{
    use HasFactory;

    protected $connection = 'yearbook';
    protected $table = 'yearbooks';

    protected $fillable = [
        'school_year',
        'edition_title',
        'total_ordered',
        'total_received',
        'unit_cost',
        'campus',
    ];

    public function shipments(): HasMany
    {
        return $this->hasMany(YearbookShipment::class);
    }

    public function issuances()
{
    return $this->hasMany(YearbookIssuance::class, 'yearbook_id', 'id');
}
}
