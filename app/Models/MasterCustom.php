<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterCustom extends Model
{
    use HasFactory;

    protected $table = 'master_custom';

    protected $fillable = ['name','type','icon','tab', 'tab_history','parent','template'];


    public function r_parent()
    {
        return $this->hasOne(MasterCustom::class, 'id', 'parent');
    }

    public function r_child()
    {
        return $this->hasMany(MasterCustom::class, 'parent', 'id');
    }
    
}
