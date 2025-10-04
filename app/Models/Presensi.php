<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    protected $table = 'presensi';
    protected $fillable = [
        'user_id',
        'name',
        'nik',
        'latitude_in',
        'longitude_in',
        'latitude_out',
        'longitude_out',
        'check_in',
        'check_in_address',
        'check_out',
        'check_out_address',
        'status',
        'approval_by',
        'position',
        'departement',
        'work_description',
        'photo_in',
        'photo_out',
    ];


    public function r_presensi_break()
    {
        return $this->hasMany(PresensiBreak::class, 'presensi_id', 'id');
    }
}
