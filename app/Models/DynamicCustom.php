<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicCustom extends Model
{
    use HasFactory;
    protected $guarded = []; // Memungkinkan mass assignment
    public $tab_parent; 
    // Menentukan tabel secara dinamis
    public function setTableName($tableName)
    {
        $this->tab_parent = $tableName;
        $this->setTable($tableName);
        return $this;
    }

    public function r_discipline()
    {
        return $this->hasOne(MasterDiscipline::class, 'id', 'discipline');
    }
    public function r_category()
    {
        return $this->hasOne(MasterCategory::class, 'id', 'category');
    }
    public function r_status()
    {
        return $this->hasOne(MasterStatus::class, 'code', 'status');
    }
   
}
