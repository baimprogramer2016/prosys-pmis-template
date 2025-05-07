<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
use App\Models\ScheduleManagement;
use App\Models\SCurve;
use App\Models\SCurveFile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;
use Illuminate\Support\FacadesDB;
use Yajra\DataTables\Facades\DataTables;

class SCurveController extends Controller
{
    public function index(Request $request)
    {
        try {


            $minDate = DB::table('s_curve')->min('tanggal');
            $maxDate = DB::table('s_curve')->max('tanggal');

            $currentDate = Carbon::parse($minDate)->startOfWeek();
            $endDate = Carbon::parse($maxDate);
            $weeks = [];
            $weekIndex = 0;

            // cari misal week 1 dari tanggal berapa sampai tanggal berapa
            while ($currentDate <= $endDate) {
                $startOfWeek = $currentDate->copy();
                $endOfWeek = $currentDate->copy()->endOfWeek();

                $weeks[] = [
                    'week_label' =>  $weekIndex,
                    'start_date' => $startOfWeek->format('Y-m-d'),
                    'end_date' => $endOfWeek->format('Y-m-d'),
                ];

                $currentDate->addWeek();
                $weekIndex++;
            }


            $data_tanggal = SCurve::distinct()->orderBy('tanggal', 'asc')->pluck('tanggal');
            $data_scurve = SCurve::when($request->has('filter_category'), function ($query) use ($request) {
                $query->where('description', $request->filter_category);
            })
                ->when($request->has('filter_tanggal'), function ($query) use ($request) {
                    $query->where('tanggal', $request->filter_tanggal);
                })
                ->orderBy('tanggal', 'ASC')->get();
            $data_response_curve = [];

            foreach ($data_tanggal as $tanggal_curve) {
                foreach (['Actual', 'Planned'] as $desc) {
                    $item = [
                        'week' => "",
                        'tanggal' => $tanggal_curve,
                        'description' => $desc,
                        'engineering' => null,
                        'procurement' => null,
                        'construction' => null,
                        'commissioning' => null,
                        'path' => null
                    ];

                    foreach ($data_scurve as $row) {
                        if ($row->tanggal == $tanggal_curve && $row->description == $desc) {
                            switch ($row->category) {
                                case 'Engineering':
                                    $item['engineering'] = $row->percent;
                                    break;
                                case 'Procurement':
                                    $item['procurement'] = $row->percent;
                                    break;
                                case 'Construction':
                                    $item['construction'] = $row->percent;
                                    break;
                                case 'Commissioning':
                                    $item['commissioning'] = $row->percent;
                                    break;
                            }
                        }

                        foreach ($weeks as $week) {
                            if ($tanggal_curve >= $week['start_date'] && $tanggal_curve <= $week['end_date']) {
                                $item['week'] = $week['week_label'];
                            }
                        }
                    }
                    //check file
                    $check_file = SCurveFile::where('tanggal', $tanggal_curve)->where('description', $desc)->first();
                    if ($check_file) {
                        $item['path'] = $check_file->path;
                    }

                    $data_response_curve[] = $item;
                }
            }

            //pada data yang sudah di labal week,, ambil dan disitinct week nya
            $weeks_colom = array_column($data_response_curve, 'week');

            // Hapus duplikat
            $uniqueWeeks = array_unique($weeks_colom);
            sort($uniqueWeeks);
            // Bentuk ulang jadi array asosiatif ['week' => x]
            $data_week = array_map(function ($week) {
                return ['week' => $week];
            }, $uniqueWeeks);


            //buat filter week
            if ($request->has('filter_week')) {
                $data_response_curve = array_filter($data_response_curve, function ($item) use ($request) {
                    return $item['week'] == $request->filter_week;
                });
            }

            // return $data_response_curve;
            return view('pages.s-curve.s-curve', [
                "data_category" => MasterCategory::where('category', 'schedule_management')->get(),
                "data_sub_category" => MasterCategory::where('category', 's_curve')->get(),
                "data_scurve" => $data_response_curve,
                "data_week" => $data_week
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getSCurve(Request $request)
    {

        if ($request->ajax()) {
            $data = SCurve::select([
                'id',
                'description',
                'author',
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal"),
                'percent',
                'category',

            ]);

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $rowJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');  // Konversi $row ke JSON string

                    $editBtn = '';
                    if (Gate::allows('edit_input_s_curve')) {
                        $editBtn = '<a onClick="return viewEdit(' . $rowJson . ')" class="dropdown-item cursor-pointer">Edit</a>';
                    }
                    $deleteBtn = '';
                    if (Gate::allows('delete_input_s_curve')) {
                        $deleteBtn = '<a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>';
                    }
                    $btn = '<div class="dropdown">
                    <button
                        class="btn btn-icon btn-clean me-0"
                        type="button"
                        id="dropdownMenuButton"
                        data-bs-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >
                        <i class="fas fa-ellipsis-h"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                         ' . $editBtn . '
                        ' . $deleteBtn . '
                    </div>
                </div>';
                    return $btn;
                })
                ->rawColumns(['action']) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }


    public function save(Request $request)
    {
        // Simpan ke database atau proses lainnya
        $doc = SCurve::updateOrCreate(
            [
                'tanggal' => $request->input('tanggal'),   // Kondisi untuk mencocokkan data
                'category' => $request->input('category'),
                'description' => $request->input('description')
            ],
            [
                'percent' => $request->input('percent'),  // Data yang akan di-update atau di-insert
                'author' => Auth::user()->name
            ]
        );
        return response()->json([
            'status' => 'ok',

        ]);
    }

    public function viewEdit(Request $request)
    {

        try {

            return view('pages.s-curve.s-curve-edit', [
                "category" => $request->category,
                "tanggal" => $request->tanggal,
                "description" => $request->description,
                "percent" => $request->percent,
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request)
    {
        $description = $request->input('description');
        $category = $request->input('category');
        $tanggal = $request->input('tanggal');
        $percent = $request->input('percent');

        // $doc = SCurve::where('description', $description)
        // ->where('category', $category)
        // ->where('tanggal', $tanggal)
        // ->first();

        $doc = SCurve::updateOrCreate(
            [
                'tanggal' => $request->input('tanggal'),   // Kondisi untuk mencocokkan data
                'category' => $request->input('category'),
                'description' => $request->input('description')
            ],
            [
                'percent' => $request->input('percent'),  // Data yang akan di-update atau di-insert
                'author' => Auth::user()->name
            ]
        );

        // if ($doc) {
        //     $doc->percent = $percent;
        //     $doc->save();
        // }


        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function viewWeight()
    {
        try {

            return view('pages.s-curve.s-curve-weight', [
                "data_weight"  => MasterCategory::where('category', 's_curve')->get()
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function weightUpdate(Request $request)
    {
        $inputs = $request->except('_token');

        foreach ($inputs as $key => $value) {
            $doc = MasterCategory::where('category', 's_curve')
                ->where('description', ucfirst($key))->first(); // 'engineering' → 'Engineering'
            $doc->weight = $value;
            $doc->save();
        }

        return response()->json([
            'status' => 'ok',
        ]);
    }


    public function viewDelete(Request $request, $id)
    {

        try {
            $document = SCurve::find($id);
            return view('pages.s-curve.s-curve-delete', [
                "document" => $document,
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }



    public function deleted($id)
    {
        $task = SCurve::find($id);
        $task->delete();

        return response()->json([
            "action" => "deleted"
        ]);
    }

    public function sCurveChart(Request $request)
    {

        try {

            $minDate = DB::table('s_curve')->min('tanggal');
            $maxDate = DB::table('s_curve')->max('tanggal');
            return view('pages.s-curve.s-curve-chart', [
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
    public function sCurveBar(Request $request)
    {

        try {
            return view('pages.s-curve.s-curve-bar');
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function dataScurve(Request $request)
    {

        // 1. Cari min dan max tanggal
        $minDate = DB::table('s_curve')->min('tanggal');
        $maxDate = DB::table('s_curve')->max('tanggal');

        //jika ada filter
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $category = $request->query('category');

        if ($startDate != '' && $endDate != '') {
            $minDate = $startDate;
            $maxDate = $endDate;
        }

        // 2. Buat array tanggal mingguan, termasuk Week 0
        $currentDate = Carbon::parse($minDate)->startOfWeek();
        $endDate = Carbon::parse($maxDate);
        $weeks = [];
        $weekIndex = 0;

        // cari misal week 1 dari tanggal berapa sampai tanggal berapa
        while ($currentDate <= $endDate) {
            $startOfWeek = $currentDate->copy();
            $endOfWeek = $currentDate->copy()->endOfWeek();

            $weeks[] = [
                'week_label' => "Week " . $weekIndex,
                'week_label_date' => "Week " . $weekIndex . ' ' . $startOfWeek->format('Y-m-d'),
                'start_date' => $startOfWeek->format('Y-m-d'),
                'end_date' => $endOfWeek->format('Y-m-d'),
            ];

            $currentDate->addWeek();
            $weekIndex++;
        }


        // 3. Ambil dan kelompokkan data per minggu
        $data = DB::table('s_curve')->select('description', 'tanggal', 'percent')
            ->when($startDate && $endDate, function ($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal', [$startDate, $endDate]);
            })->when($category != 'all', function ($query) use ($category) {
                $query->where('category', $category);
            })->get();

        $data_path = SCurveFile::select('description', 'tanggal', 'path')->get();

        $result = [];
        $jsonWeek = [];
        $jsonWeekLabel = [];
        $jsonTanggal = [];
        $jsonPlanned = [];
        $jsonActual = [];
        $jsonPathPlanned = [];
        $jsonPathActual = [];

        //setelah itu cari di db, data yang dianatara tanggal per weeknya
        foreach ($weeks as $week) {
            $weekData = [
                'week_label' => $week['week_label'],
                'start_date' => $week['start_date'],
                'end_date' => $week['end_date'],
                'planned_total' => 0,
                'actual_total' => 0,
                'planned_path' => "",
                'actual_path' => ""
            ];

            //jika ke data, jika tanggal pada data ada di antara week update data masing sesuai planned atau actual
            foreach ($data as $row) {
                $date = Carbon::parse($row->tanggal);
                if ($date >= Carbon::parse($week['start_date']) && $date <= Carbon::parse($week['end_date'])) {
                    if ($row->description === 'Planned') {
                        $weekData['planned_total'] += ROUND($row->percent, 2);
                    } elseif ($row->description === 'Actual') {
                        $weekData['actual_total'] += ROUND($row->percent, 2);
                    }
                }
            }

            //paksa 100
            if ($weekData['planned_total'] > 100 && $weekData['planned_total'] < 100.10) {
                $weekData['planned_total'] = 100;
            }
            if ($weekData['actual_total'] > 100 && $weekData['actual_total'] < 100.10) {
                $weekData['actual_total'] = 100;
            }
            // Pastikan nilai persentase ditampilkan dengan dua desimal
            $weekData['planned_total'] = $weekData['planned_total'] . '%';
            $weekData['actual_total'] = $weekData['actual_total'] . '%';

            //cari path
            foreach ($data_path as $row_path) {
                $date = Carbon::parse($row_path->tanggal);
                if ($date >= Carbon::parse($week['start_date']) && $date <= Carbon::parse($week['end_date'])) {
                    if ($row_path->description === 'Planned') {
                        $weekData['planned_path'] = $row_path->path;
                    } elseif ($row_path->description === 'Actual') {
                        $weekData['actual_path'] = $row_path->path;
                    }
                }
            }


            $result[] = $weekData;
            array_push($jsonWeek, $week['week_label']);
            array_push($jsonWeekLabel, $week['week_label_date']);
            array_push($jsonTanggal, $week['start_date']);
            array_push($jsonPlanned, str_replace('%', '', $weekData['planned_total']));
            array_push($jsonActual, str_replace('%', '', $weekData['actual_total']));
            array_push($jsonPathPlanned, $weekData['planned_path']);
            array_push($jsonPathActual, $weekData['actual_path']);
        }

        //result bentuknya 
        /*
                {
                    "week_label": "Week 0",
                    "start_date": "2025-01-27",
                    "planned_total": "11%",
                    "actual_total": "3%"
                },
                {
                    "week_label": "Week 1",
                    "start_date": "2025-02-03",
                    "planned_total": "144%",
                    "actual_total": "70.9%"
                },
       */
        //modif sesuai dengan curve


        $final = [
            "status" => "ok",
            "weeks" => $jsonWeek,
            "weeks_label" => $jsonWeekLabel,
            "tanggal" => $jsonTanggal,
            "planned" => $jsonPlanned,
            "actual" => $jsonActual,
            "data" => $result,
            "path_planned" => $jsonPathPlanned,
            "path_actual" => $jsonPathActual,
        ];

        // 4. Return hasil dalam JSON
        return response()->json($final);
    }
    public function dataScurveBar(Request $request)
    {

        // 1. Cari min dan max tanggal

        $end_date = $request->end_date;

        // $data_planned = DB::table('s_curve')->select(DB::raw('percent '))
        //     ->when($end_date, function ($query) use ($end_date) {
        //         $query->whereBetween('tanggal', [$end_date]);
        //     })->where('description', 'Planned')->first();
        // $data_actual = DB::table('s_curve')->select(DB::raw('percent as total'))
        //     ->when($end_date, function ($query) use ($end_date) {
        //         $query->whereBetween('tanggal', [$end_date]);
        //     })->where('description', 'Actual')->first();

        $data_planned = DB::table('s_curve')->select(DB::raw('tanggal,sum(percent) as total'))
            ->when($end_date, function ($query) use ($end_date) {
                $query->where('tanggal', '<=', $end_date);
            })
            ->where('description', 'Planned')
            ->where('percent', '!=', 0)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->first();
        $data_actual = DB::table('s_curve')->select(DB::raw('tanggal,sum(percent) as total'))
            ->when($end_date, function ($query) use ($end_date) {
                $query->where('tanggal', '<=', $end_date);
            })
            ->where('description', 'Actual')
            ->where('percent', '!=', 0)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->first();
        $planned = [];
        $actual = [];



        // if ($data_planned->total > 0) {
        //     $actual_percent = $data_actual->total / $data_planned->total * 100;
        // } else {
        //     $actual_percent = 0; // atau null, atau ‘-’, tergantung kebutuhan
        // }

        if ($data_planned->total > 100 && $data_planned->total < 100.10) {
            $data_planned->total = 100;
        }
        if ($data_actual->total > 100 && $data_actual->total < 100.10) {
            $data_actual->total = 100;
        }
        array_push($planned, round(($data_planned->total != null ? $data_planned->total : 0), 2));
        array_push($actual, round(($data_actual->total != null ? $data_actual->total : 0), 2));


        $final = [
            "status" => "ok",
            "title" => ['Planned', 'Actual'],
            "planned" => $planned,
            "actual" => $actual
        ];

        // 4. Return hasil dalam JSON
        return response()->json($final);
    }



    public function viewEditTanggal(Request $request)
    {
        return view('pages.s-curve.s-curve-edit-tanggal', [
            "description" => $request->description,
            "tanggal" => Carbon::parse($request->tanggal)->format('Y-m-d'),
        ]);
    }

    public function viewEditTanggalUpdate(Request $request)
    {

        $check = SCurve::where('description', $request->description)
            ->where('tanggal', $request->tanggal_baru)
            ->first();

        if ($check) {
            return response()->json([
                'status' => 'Tanggal Sudah Ada, silahkan edit langsung di Tabel',
            ]);
        }


        SCurve::where('description', $request->description)
            ->where('tanggal', $request->tanggal_lama)
            ->update(['tanggal' => $request->tanggal_baru]);

        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function viewHapusTanggal(Request $request)
    {
        return view('pages.s-curve.s-curve-hapus-tanggal', [
            "description" => $request->description,
            "tanggal" => Carbon::parse($request->tanggal)->format('Y-m-d'),
        ]);
    }
    public function viewHapusTanggalDelete(Request $request)
    {

        try {
            SCurve::where('description', $request->description)
                ->where('tanggal', $request->tanggal)
                ->delete();


            return response()->json([
                'status' => 'ok',
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'Terjadi Kesalahan',
            ]);
        }
    }


    public function viewUploadTanggal(Request $request)
    {
        return view('pages.s-curve.s-curve-upload-tanggal', [
            "description" => $request->description,
            "tanggal" => Carbon::parse($request->tanggal)->format('Y-m-d'),
        ]);
    }

    public function viewUploadTanggalUpload(Request $request)
    {

        $file = $request->file('file');
        // Cek apakah ada file
        if (!$file) {
            return response()->json([
                "status" => "File belum di pilih",
            ]);
        }
        // Cek ekstensi file yang diizinkan
        $allowedExtensions = ['pdf', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'ppt', 'doc', 'docx', 'pptx', 'cad', 'dwg'];
        $extension = $file->getClientOriginalExtension();

        if (!in_array(strtolower($extension), $allowedExtensions)) {
            return response()->json([
                "status" => "File tidak diizinkan",
            ]);
        }

        $folder = 'public/s-curve-files';
        $filePath = $request->file('file')->store($folder);
        SCurveFile::updateOrCreate(

            [
                'description' => $request->description,
                'tanggal' => $request->tanggal,
            ], // Data yang akan disimpan/diupdate
            [
                'path' => str_replace("public/", "", $filePath),
            ]
        );


        return response()->json([
            "status" => "ok",
        ]);
    }

    public function viewFile(Request $request)
    {

        try {
            $data_scurve_file = SCurveFile::where('description', $request->description)
                ->where('tanggal', $request->tanggal)
                ->first();

            return view('pages.s-curve.s-curve-pdf', [
                "ext" => explode(".", $data_scurve_file->path)[1] ?? null,
                "path" => $data_scurve_file->path ?? null,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => "notok",
            ]);
        }
    }
    public function viewFile2(Request $request)
    {
        try {
            return view('pages.s-curve.s-curve-pdf', [
                "ext" => explode(".", $request->path)[1] ?? null,
                "path" => $request->path ?? null,
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => "notok",
            ]);
        }
    }
}



/*
{
    planned : {
            {
                    "waktu" : "tanggal 1",
                    percent : 0.7
            },
            {
                    "waktu" : "tanggal 2",
                    percent : 0.7
            }
    },
    Actual : {
    {
                    "waktu" : "tanggal 1",
                    percent : 0.7
            },
            {
                    "waktu" : "tanggal 2",
                    percent : 0.7
            }
    
    }
}

*/