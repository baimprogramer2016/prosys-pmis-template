<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCustom extends Model
{
    use HasFactory;

    protected $table = 'master_custom';


    public function r_parent()
    {
        return $this->hasOne(MasterCustom::class, 'id', 'parent');
    }

}
