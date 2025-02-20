<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportDaily extends Model
{
    use HasFactory;
    protected $table = 'report_daily';

    public function r_history()
    {
        return $this->hasMany(ReportDailyHistory::class, 'report_daily_id', 'id');
    }
}
