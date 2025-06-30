<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use App\Models\DynamicCustom;
use App\Models\MasterCustom;

class CustomRfi extends Component
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
        $sidebar_rfi = MasterCustom::where("template", 'rfi_in_quality_management')->where('type', 'parent')->get();

        foreach ($sidebar_rfi as $item_parent) {

            foreach ($item_parent->r_child as $item_child) {
                $item_child->jml_doc = (new DynamicCustom())->setTableName('custom_' . $item_child->tab)->count();
            }
        }
        return view('components.custom-rfi', [
            "sidebar_rfi_in_quality_management" => $sidebar_rfi
        ]);
    }
}
