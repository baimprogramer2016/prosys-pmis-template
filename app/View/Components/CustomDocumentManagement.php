<?php

namespace App\View\Components;

use App\Models\MasterCustom;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CustomDocumentManagement extends Component
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
        $sidebar_document_management = MasterCustom::where("template",'document_management')->where('type','parent')->get();
        return view('components.custom-document-management',[
            "sidebar_document_management" => $sidebar_document_management
        ]);
    }
}
