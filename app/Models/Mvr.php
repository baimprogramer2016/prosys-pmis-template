<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mvr extends Model
{
    use HasFactory;
    protected $table = 'material_verification_report';

    public function r_history()
    {
        return $this->hasMany(MvrHistory::class, 'mvr_id', 'id');
    }
}
