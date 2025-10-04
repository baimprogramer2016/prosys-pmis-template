<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresensiBreak extends Model
{
    use HasFactory;
    protected $table = 'presensi_break';
    protected $fillable = [
        'status',
        'presensi_id',
        'break_time',
        'work_time'
    ];
}
