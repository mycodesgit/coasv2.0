<?php

namespace App\Models\AdmissionDB;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApplicantDocs extends Model
{
    use HasFactory;

    protected $table = 'ad_applicant_docs';

    protected $fillable = [
        'admission_id', 
        'r_card', 
        'g_moral',
        'b_cert',
        'h_dismissal',
        'm_cert',
        'doc_image',
    ];
}
