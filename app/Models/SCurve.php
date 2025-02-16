<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SCurve extends Model
{
    use HasFactory;

    protected $table = 's_curve';

    protected $fillable = ['description', 'percent', 'tanggal', 'category'];
}
