<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SCurveFile extends Model
{
    use HasFactory;

    protected $table = 's_curve_file';

    protected $fillable = ['description', 'path', 'tanggal'];
}
