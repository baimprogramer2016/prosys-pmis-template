<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rfi extends Model
{
    use HasFactory;
    protected $table = 'rfi';

    public function r_history()
    {
        return $this->hasMany(RfiHistory::class, 'rfi_id', 'id');
    }
}
