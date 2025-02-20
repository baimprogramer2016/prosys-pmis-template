<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportMonthly extends Model
{
    use HasFactory;
    protected $table = 'report_monthly';
    public function r_history()
    {
        return $this->hasMany(ReportMonthlyHistory::class, 'report_monthly_id', 'id');
    }
}
