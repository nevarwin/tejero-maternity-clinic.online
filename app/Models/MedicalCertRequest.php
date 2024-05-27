<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalCertRequest extends Model {
    use HasFactory;

    protected $fillable = [
        'full_name',
        'doctors_name',
        'contact_number',
        'case_number',
        'description',
        'status',
    ];
}
