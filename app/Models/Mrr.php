<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mrr extends Model
{
    use HasFactory;

    protected $table = 'material_receiving_report';

    public function r_history()
    {
        return $this->hasMany(MrrHistory::class, 'mrr_id', 'id');
    }
}
