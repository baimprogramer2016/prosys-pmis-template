<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorSuratMasuk extends Model
{
    use HasFactory;

    protected $table = 'cor_surat_masuk';

    public function r_category()
    {
        return $this->hasOne(MasterCategory::class, 'id', 'category');
    }
    public function r_history()
    {
        return $this->hasMany(CorSuratMasukHistory::class, 'cor_surat_masuk_id', 'id');
    }
}
