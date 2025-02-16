<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorSuratKeluar extends Model
{
    use HasFactory;

    protected $table ='cor_surat_keluar';
    public function r_category()
    {
        return $this->hasOne(MasterCategory::class, 'id', 'category');
    }
}
