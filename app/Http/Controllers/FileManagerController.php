<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Throwable;

class FileManagerController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.file-manager.index');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
}
