<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
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

    public function dashboardPieSurat(Request $request){

        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $surat_masuk_count = Surat::where('jenis', 'masuk')
        ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })
        ->count();
       $surat_keluar_count = Surat::where('jenis', 'keluar')
       ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
           $query->whereBetween('tanggal', [$startDate, $endDate]);
       })
       ->count();;

       $surat_masuk_open = Surat::where('jenis','masuk')->where('status','open')
       ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->count();
       $surat_masuk_close = Surat::where('jenis','masuk')->where('status','close')
       ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->count();

       $surat_keluar_open = Surat::where('jenis','keluar')->where('status','open')
       ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->count();
       $surat_keluar_close = Surat::where('jenis','keluar')->where('status','close')
       ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
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
}
