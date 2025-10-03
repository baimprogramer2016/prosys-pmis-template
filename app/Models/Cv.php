<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cv extends Model
{
    use HasFactory;

    protected $table = 'cv';
    protected $fillable = [
        'name',
        'academy',
        'major',
        'degree',
        'path',
        'ext',
        'author',
    ];
}
