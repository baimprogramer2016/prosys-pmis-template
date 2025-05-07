<?php

namespace App\View\Components;

use App\Models\DynamicCustom;
use App\Models\MasterCustom;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NewDashboardImage extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {

        // $data = (new DynamicCustom())->setTableName($tableName)
        $result = collect();
        $table_photographics = MasterCustom::select('tab')->where("template", 'photographic')->whereNotNull('tab')->get();

        foreach ($table_photographics as $item_tab) {
            $data_photographics = (new DynamicCustom())->setTableName('custom_' . $item_tab->tab)->select('description', 'path')->get();
            if ($data_photographics->isNotEmpty()) {
                $result = $result->merge($data_photographics);
            }
        }

        return view('components.new-dashboard-image', [
            "data_photographics" => $result
        ]);
    }
}
