<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Support\Facades\DB;
use App\Models\MasterCategory;
class NewDashboard extends Component
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
        $minDate = DB::table('s_curve')->min('tanggal');
        $maxDate = DB::table('s_curve')->max('tanggal');
        return view('components.new-dashboard',[
            "dataSubCategory" => MasterCategory::where('category','s_curve')->get(),
            "minDate" => date("Y-m-d", strtotime($minDate)),
            "maxDate" => date("Y-m-d", strtotime($maxDate)),
        ]);
    }
}
