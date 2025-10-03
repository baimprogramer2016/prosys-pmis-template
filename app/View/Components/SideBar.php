<?php

namespace App\View\Components;

use App\Models\ConstructionDocument;
use App\Models\CorSuratKeluar;
use App\Models\CorSuratMasuk;
use App\Models\Cv;
use App\Models\DocumentEngineering;
use App\Models\FieldInstruction;
use App\Models\IssueLog;
use App\Models\Mom;
use App\Models\Mrr;
use App\Models\Mvr;
use App\Models\Ncr;
use App\Models\ReportDaily;
use App\Models\ReportMonthly;
use App\Models\ReportWeekly;
use App\Models\Rfi;
use App\Models\ScheduleManagement;
use App\Models\SCurve;
use App\Models\Sop;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class SideBar extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $data = [];
        return view('components.side-bar', [
            "jml_schedule" => ScheduleManagement::count(),
            "jml_scurve" => SCurve::count(),
            "jml_sop" => Sop::count(),
            "jml_mdr" => DocumentEngineering::count(),
            "jml_bd" => DocumentEngineering::where('category', '1')->count(),
            "jml_ded" => DocumentEngineering::where('category', '2')->count(),
            "jml_construction" => ConstructionDocument::count(),
            "jml_field_construction" => FieldInstruction::count(),
            "jml_cor_masuk" => CorSuratMasuk::count(),
            "jml_cor_keluar" => CorSuratKeluar::count(),
            "jml_daily_report" => ReportDaily::count(),
            "jml_weekly_report" => ReportWeekly::count(),
            "jml_monthly_report" => ReportMonthly::count(),
            "jml_rfi" => Rfi::count(),
            "jml_mvr" => Mvr::count(),
            "jml_mrr" => Mrr::count(),
            "jml_mom" => Mom::count(),
            "jml_ncr" => Ncr::count(),
            "jml_cv" => Cv::count(),
            "jml_issue_log" => IssueLog::count(),
        ]);
    }
}
