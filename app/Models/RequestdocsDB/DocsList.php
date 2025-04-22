<?php

namespace App\Models\RequestdocsDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsList extends Model
{
    use HasFactory;

    protected $connection = 'requestdocs';
    protected $table = 'docsreq';

    protected $fillable = [
        'docname',
    ];
}
