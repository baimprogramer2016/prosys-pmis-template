<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DynamicCustomHistory extends Model
{
    use HasFactory;
    protected $guarded = []; // Memungkinkan mass assignment

    // Menentukan tabel secara dinamis
    public function setTableName($tableName)
    {
        $this->setTable($tableName);
        return $this;
    }
}
