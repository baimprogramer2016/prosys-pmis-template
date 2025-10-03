<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvGroup extends Model
{
    use HasFactory;

    protected $table = 'cv_group';
    protected $fillable = [
        'description',
        'status',
        'created_by_id',
        'created_by',
        'reviewer_id',
        'reviewer_name',
    ];
}
