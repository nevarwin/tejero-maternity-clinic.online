<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Municipal;

class Province extends Model
{
    use HasFactory;
    public function municipality(){
        return $this->hasMany(Municipal::class);
    }
}
