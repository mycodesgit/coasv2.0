<?php

namespace App\Models\YearBookDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearbookIssuance extends Model
{
    use HasFactory;

    protected $connection = 'yearbook';
    protected $table = 'yearbook_issuances';

    protected $fillable = [
        'yearbook_id',
        'student_id',
        'issued_at',
        'issued_by',
        'remarks',
    ];

    /**
     * Relationship back to the Yearbook
     */
    public function yearbook()
    {
        return $this->belongsTo(Yearbooks::class, 'yearbook_id', 'id');
    }
}
