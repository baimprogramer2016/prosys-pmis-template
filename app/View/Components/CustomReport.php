<?php

namespace App\View\Components;

use App\Models\DynamicCustom;
use App\Models\MasterCustom;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomReport extends Component
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
        $sidebar_report = MasterCustom::where("template", 'report')->where('type', 'parent')->get();

        foreach ($sidebar_report as $item_parent) {

            foreach ($item_parent->r_child as $item_child) {
                $item_child->jml_doc = (new DynamicCustom())->setTableName('custom_' . $item_child->tab)->count();
            }
        }
        return view('components.custom-report', [
            "sidebar_report" => $sidebar_report
        ]);
    }
}
