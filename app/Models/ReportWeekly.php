<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportWeekly extends Model
{
    use HasFactory;
    protected $table = 'report_weekly';

    public function r_history()
    {
        return $this->hasMany(ReportWeeklyHistory::class, 'report_weekly_id', 'id');
    }
}
