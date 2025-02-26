<?php

namespace App\Http\Controllers;

use App\Models\CorSuratKeluar;
use App\Models\CorSuratMasuk;
use App\Models\DynamicCustom;
use App\Models\MasterCategory;
use App\Models\MasterCustom;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DashboardController extends Controller
{
    public function index (Request $request){
        

        try{
            return view('pages.dashboard.dashboard',[
                "data_sub_category" => MasterCategory::where('category','s_curve')->get()
            ]);
        }catch (Throwable $e) {
            // Tangani error
           
    
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function slide (Request $request){
        

        try{
            return view('pages.dashboard.slide');
        }catch (Throwable $e) {
            // Tangani error
           
    
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function dashboardPieSurat(Request $request){

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $surat_masuk_count = CorSuratMasuk::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })
        ->count();
       $surat_keluar_count = CorSuratKeluar::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
           $query->whereBetween('created_at', [$startDate, $endDate]);
       })
       ->count();;

       $surat_masuk_open = CorSuratMasuk::where('status','open')
       ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();
       $surat_masuk_close = CorSuratMasuk::where('status','close')
       ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

       $surat_keluar_open = CorSuratKeluar::where('status','open')
       ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();
       $surat_keluar_close = CorSuratKeluar::where('status','close')
       ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

 

        return response()->json([
            "status" => "ok",
            "data_pie_surat" => [
                [
                    "id" => "pieChart1",
                    "title" =>"Surat Masuk & Keluar",
                    "color" => ["#1d7af3", "#f3545d"],
                    "value" =>[$surat_masuk_count, $surat_keluar_count],
                    "legend" =>["Masuk", "Keluar"],
                ],
                [
                    "id" => "pieChart2",
                    "title" =>"Surat Masuk",
                    "color" => ["orange", "red"],
                    "value" => [$surat_masuk_open, $surat_masuk_close],
                    "legend" =>["Open", "Close"],
                ],
                [
                    "id" => "pieChart3",
                    "title" =>"Surat Keluar",
                    "color" => ["pink", "indigo"],
                    "value" =>[$surat_keluar_open, $surat_keluar_close],
                    "legend" =>["Open", "Close"],
                ]
                ]
                ]);

    }
    public function dashboardDrawings(Request $request){
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $tabel_drawings = MasterCustom::where('template','drawings')->whereNotNull('tab')->get();
        $array_drawings = [];

        // return $tabel_drawings;

        foreach($tabel_drawings as $item_drawing) {
            $drawing['title'] = $item_drawing->name;

            //jumlah ambil dari dynamic model
            $model = (new DynamicCustom())->setTableName('custom_'.$item_drawing->tab);
            $drawing['jumlah'] = $model->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

            array_push($array_drawings, $drawing);
        }

        return response()->json($array_drawings);
    }
}
