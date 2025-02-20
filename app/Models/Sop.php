<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sop extends Model
{
    use HasFactory;

    protected $table = 'sop';

    public function r_history()
    {
        return $this->hasMany(SopHistory::class, 'sop_id', 'id');
    }

}
