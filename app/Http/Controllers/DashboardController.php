<?php

namespace App\Http\Controllers;

use App\Models\ConstructionDocument;
use App\Models\CorSuratKeluar;
use App\Models\CorSuratMasuk;
use App\Models\DocumentEngineering;
use App\Models\DynamicCustom;
use App\Models\FieldInstruction;
use App\Models\MasterCategory;
use App\Models\MasterCustom;
use App\Models\Sop;
use App\Models\Surat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class DashboardController extends Controller
{
    public function index(Request $request)
    {


        try {
            $minDate = DB::table('s_curve')->min('tanggal');
            $maxDate = DB::table('s_curve')->max('tanggal');
            return view('pages.dashboard.dashboard', [
                "data_sub_category" => MasterCategory::where('category', 's_curve')->get(),
                "min_date" => date("Y-m-d", strtotime($minDate)),
                "max_date" => date("Y-m-d", strtotime($maxDate)),

            ]);
        } catch (Throwable $e) {
            // Tangani error


            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function slide(Request $request)
    {


        try {
            return view('pages.dashboard.slide');
        } catch (Throwable $e) {
            // Tangani error


            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function dashboardPieSurat(Request $request)
    {

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

        $surat_masuk_open = CorSuratMasuk::where('status', 'open')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();
        $surat_masuk_close = CorSuratMasuk::where('status', 'close')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

        $surat_keluar_open = CorSuratKeluar::where('status', 'open')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();
        $surat_keluar_close = CorSuratKeluar::where('status', 'close')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();



        return response()->json([
            "status" => "ok",
            "data_pie_surat" => [
                [
                    "id" => "pieChart1",
                    "title" => "Surat Masuk & Keluar",
                    "color" => ["#e25668", "#e256ae"],
                    "value" => [$surat_masuk_count, $surat_keluar_count],
                    "legend" => ["Masuk", "Keluar"],
                ],
                [
                    "id" => "pieChart2",
                    "title" => "Surat Masuk",
                    "color" => ["#8a56e2", "#5668e2"],
                    "value" => [$surat_masuk_open, $surat_masuk_close],
                    "legend" => ["Open", "Close"],
                ],
                [
                    "id" => "pieChart3",
                    "title" => "Surat Keluar",
                    "color" => ["#57aee2", "#6de257"],
                    "value" => [$surat_keluar_open, $surat_keluar_close],
                    "legend" => ["Open", "Close"],
                ]
            ]
        ]);
    }
    public function dashboardDrawings(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $tabel_drawings = MasterCustom::where('template', 'drawings')->whereNotNull('tab')->get();
        $array_drawings = [];

        // return $tabel_drawings;

        foreach ($tabel_drawings as $item_drawing) {
            $drawing['title'] = $item_drawing->name;

            //jumlah ambil dari dynamic model
            $model = (new DynamicCustom())->setTableName('custom_' . $item_drawing->tab);
            $drawing['jumlah'] = $model->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

            array_push($array_drawings, $drawing);
        }

        return response()->json($array_drawings);
    }

    public function dashboardProcurementLogistic(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $tabel_procurement_logistic = MasterCustom::where('template', 'procurement_logistic')->whereNotNull('tab')->get();
        $array_tab = [];

        // return $tabel_drawings;

        foreach ($tabel_procurement_logistic as $item) {
            $data['label'] = $item->name;

            //jumlah ambil dari dynamic model
            $model = (new DynamicCustom())->setTableName('custom_' . $item->tab);
            $data['value'] = $model->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

            $data['color'] = (optional($item->r_parent)->name == 'Procurement') ? "#245069" : "#9dd9e8";
            array_push($array_tab, $data);
        }

        return response()->json($array_tab);
    }
    public function dashboardDocumentManagement(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $array_tab = [];

        //engineer
        $jumlah_engineer = DocumentEngineering::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->count();
        $data_engineer['label'] = "Engineer";
        $data_engineer['value'] = $jumlah_engineer;

        array_push($array_tab, $data_engineer);

        $jumlah_contruction = ConstructionDocument::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->count();
        $data_construction['label'] = "Construction";
        $data_construction['value'] = $jumlah_contruction;

        array_push($array_tab, $data_construction);

        $jumlah_field = FieldInstruction::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('tanggal', [$startDate, $endDate]);
        })->count();
        $data_field['label'] = "Field Instruction";
        $data_field['value'] = $jumlah_field;

        array_push($array_tab, $data_field);

        $jumlah_sop = Sop::when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        })->count();

        $data_sop['label'] = "Project Procedure";
        $data_sop['value'] = $jumlah_sop;

        array_push($array_tab, $data_sop);

        $tabel_document_management = MasterCustom::where('template', 'document_management')->whereNotNull('tab')->get();

        // return $array_tab;
        foreach ($tabel_document_management as $item) {
            $data['label'] = $item->name;

            //jumlah ambil dari dynamic model
            $model = (new DynamicCustom())->setTableName('custom_' . $item->tab);
            $data['value'] = $model->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

            $data['color'] = (optional($item->r_parent)->name == 'Procurement') ? "#245069" : "#9dd9e8";
            array_push($array_tab, $data);
        }

        ///drawing
        $tabel_drawing = MasterCustom::where('template', 'drawings')->whereNotNull('tab')->get();
        $data_drawing['label'] = 'Drawings';
        // return $array_tab;
        $total_drawing = 0;
        foreach ($tabel_drawing as $item_drawing) {
            //jumlah ambil dari dynamic model
            $model = (new DynamicCustom())->setTableName('custom_' . $item_drawing->tab);
            $total = $model->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

            $total_drawing += $total;
        }
        $data_drawing['value'] = $total_drawing;
        array_push($array_tab, $data_drawing);

        return response()->json($array_tab);
    }

    public function dashboardPillings(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        $tabel_piling = MasterCustom::where('template', 'piling')->whereNotNull('tab')->get();
        $array_piling = [];

        // return $tabel_drawings;

        foreach ($tabel_piling as $item_piling) {
            $piling['title'] = $item_piling->name;

            //jumlah ambil dari dynamic model
            $model = (new DynamicCustom())->setTableName('custom_' . $item_piling->tab);
            $piling['jumlah'] = $model->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            })->count();

            array_push($array_piling, $piling);
        }

        return response()->json($array_piling);
    }

    public function newDashboardImage(Request $request)
    {
        $result = collect();
        $table_photographics = MasterCustom::select('tab')->where("template", 'photographic')->whereNotNull('tab')->get();

        foreach ($table_photographics as $item_tab) {
            $data_photographics = (new DynamicCustom())
                ->setTableName('custom_' . $item_tab->tab)
                ->select('description', 'path')
                ->when($request->start_date && $request->end_date, function ($query) use ($request) {
                    $query->whereBetween('tanggal', [$request->start_date, $request->end_date]);
                })
                ->get();
            if ($data_photographics->isNotEmpty()) {
                $result = $result->merge($data_photographics);
            }
        }

        return response()->json($result);
    }
}
