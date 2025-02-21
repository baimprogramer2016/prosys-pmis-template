<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
use App\Models\MasterCustom;
use Illuminate\Http\Request;
use Throwable;

class CustomController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.custom.custom',[
                "data_type" => MasterCategory::where('category','=','custom_menu')->get(),
                "data_parent" => MasterCustom::where('type','=','parent')->get(),
                "data_template" => MasterCategory::where('category','=','template')->get(),
            ]);
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
  

    public function getParent(){
        $data_parent =  MasterCustom::where('type','=','parent')->get();
        return response()->json($data_parent);
    }
}
