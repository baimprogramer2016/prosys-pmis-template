<?php

namespace App\View\Components;

use App\Models\DynamicCustom;
use App\Models\MasterCustom;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomPhotographic extends Component
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
        $sidebar_photographic = MasterCustom::where("template",'photographic')->where('type','parent')->get();
        
        foreach($sidebar_photographic as $item_parent){
        
            foreach($item_parent->r_child as $item_child){
                $item_child->jml_doc = (new DynamicCustom())->setTableName('custom_'.$item_child->tab)->count();
            }
        }
        return view('components.custom-photographic',[
            "sidebar_photographic" => $sidebar_photographic
        ]);
    }
}
