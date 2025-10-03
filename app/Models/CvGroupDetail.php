<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvGroupDetail extends Model
{
    use HasFactory;


    protected $table = 'cv_group_detail';
    protected $fillable = [
        'cv_id',
        'cv_group_id',
        'created_by_id',
        'status',
    ];
}
