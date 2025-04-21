<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
use App\Models\ScheduleManagement;
use App\Models\SCurve;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Throwable;
use Illuminate\Support\FacadesDB;
use Yajra\DataTables\Facades\DataTables ;

class SCurveController extends Controller
{
    public function index(Request $request){
        try{

                $data_tanggal = SCurve::distinct()->pluck('tanggal');
                $data_scurve = SCurve::when($request->has('filter_category') , function($query) use ($request){
                    $query->where('description', $request->filter_category);
                })
                ->when($request->has('filter_tanggal') , function($query) use ($request){
                    $query->where('tanggal', $request->filter_tanggal);
                })
                ->get();
                $data_response_curve = [];

                foreach ($data_tanggal as $tanggal_curve) {
                    foreach (['Actual', 'Planned'] as $desc) {
                        $item = [
                            'tanggal' => $tanggal_curve,
                            'description' => $desc,
                            'engineering' => null,
                            'procurement' => null,
                            'construction' => null,
                            'commissioning' => null,
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
                        }

                        $data_response_curve[] = $item;
                    }
                }

                
            

            // return $data_response_curve;
            return view('pages.s-curve.s-curve',[
                "data_category" => MasterCategory::where('category','schedule_management')->get(),
                "data_sub_category" => MasterCategory::where('category','s_curve')->get(),
                "data_scurve" => $data_response_curve
            ]);
        }catch (Throwable $e) {
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
            $data = SCurve::select(['id',
                'description', 
                'author',
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal"), 
                'percent',
                'category',
                
        ]);
            
            return DataTables::of($data)
            ->addColumn('action', function($row) {
                $rowJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');  // Konversi $row ke JSON string
             
                $editBtn = '';
                if (Gate::allows('edit_input_s_curve')) {
                    $editBtn = '<a onClick="return viewEdit('.$rowJson. ')" class="dropdown-item cursor-pointer">Edit</a>';
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
        'status' =>'ok',
       
    ]);
    }

    public function viewEdit(Request $request){
        
        try{
            
          return view('pages.s-curve.s-curve-edit',[
            "category" => $request->category,
            "tanggal" => $request->tanggal,
            "description" => $request->description,
            "percent" => $request->percent,
          ]);
           
        }catch (Throwable $e) {
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
       
        $doc = SCurve::where('description', $description)
        ->where('category', $category)
        ->where('tanggal', $tanggal)
        ->first();

        if ($doc) {
            $doc->percent = $percent;
            $doc->save();
        }


    return response()->json([
        'status' =>'ok',
    ]);
    }

    
    public function viewDelete(Request $request, $id){
      
        try{
            $document = SCurve::find($id);
            return view('pages.s-curve.s-curve-delete', [
                "document" => $document,
            ]);
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 
    
   
   
    public function deleted($id){
        $task = SCurve::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }

    public function sCurveChart(Request $request){
        
        try{
            
            $minDate = DB::table('s_curve')->min('tanggal');
            $maxDate = DB::table('s_curve')->max('tanggal');
          return view('pages.s-curve.s-curve-chart',[
            "data_sub_category" => MasterCategory::where('category','s_curve')->get(),
            "min_date" => date("Y-m-d", strtotime($minDate)),
            "max_date" => date("Y-m-d", strtotime($maxDate)),
          ]);
           
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 
    public function sCurveBar(Request $request){
        
        try{
          return view('pages.s-curve.s-curve-bar');
           
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 

    public function dataScurve(Request $request){
      
         // 1. Cari min dan max tanggal
       $minDate = DB::table('s_curve')->min('tanggal');
       $maxDate = DB::table('s_curve')->max('tanggal');
       
        //jika ada filter
       $startDate = $request->query('start_date');
       $endDate = $request->query('end_date');
       $category = $request->query('category');
       
       if($startDate != '' && $endDate != ''){
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
               'week_label_date' => "Week " . $weekIndex.' '.$startOfWeek->format('Y-m-d'),
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
        })->when($category !='all', function ($query) use ($category) {
            $query->where('category', $category);
        })->get();

       $result = [];
       $jsonWeek = [];
       $jsonWeekLabel = [];
       $jsonTanggal = [];
       $jsonPlanned = [];
       $jsonActual = [];

       //setelah itu cari di db, data yang dianatara tanggal per weeknya
       foreach ($weeks as $week) {
           $weekData = [
               'week_label' => $week['week_label'],
               'start_date' => $week['start_date'],
               'end_date' => $week['end_date'],
               'planned_total' => 0,
               'actual_total' => 0,
           ];
       
           //jika ke data, jika tanggal pada data ada di antara week update data masing sesuai planned atau actual
           foreach ($data as $row) {
               $date = Carbon::parse($row->tanggal);
               if ($date >= Carbon::parse($week['start_date']) && $date <= Carbon::parse($week['end_date'])) {
                   if ($row->description === 'Planned') {
                       $weekData['planned_total'] += $row->percent;
                   } elseif ($row->description === 'Actual') {
                       $weekData['actual_total'] += $row->percent;
                   }
               }
           }
       
           // Pastikan nilai persentase ditampilkan dengan dua desimal
           $weekData['planned_total'] = round($weekData['planned_total'], 2) . '%';
           $weekData['actual_total'] = round($weekData['actual_total'], 2) . '%';
       
           $result[] = $weekData;
           array_push($jsonWeek, $week['week_label']);
           array_push($jsonWeekLabel, $week['week_label_date']);
           array_push($jsonTanggal, $week['start_date']);
           array_push($jsonPlanned, str_replace('%','',$weekData['planned_total']));
           array_push($jsonActual, str_replace('%','',$weekData['actual_total']));
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
        "data" => $result
      ];
       
       // 4. Return hasil dalam JSON
       return response()->json($final);
       
        
    }
    public function dataScurveBar(Request $request){
      
        // 1. Cari min dan max tanggal
      $start_date = $request->start_date;
      $end_date = $request->end_date;
      
      $data_planned = DB::table('s_curve')->select(DB::raw('sum(percent) as total'))
      ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
               $query->whereBetween('tanggal', [$start_date, $end_date]);
       })->where('description','Planned')->first();
      $data_actual = DB::table('s_curve')->select(DB::raw('sum(percent) as total'))
      ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
               $query->whereBetween('tanggal', [$start_date, $end_date]);
       })->where('description','Actual')->first();

       $planned = [];
       $actual = [];
       array_push($planned, ROUND($data_planned->total,2));
       array_push($actual, ROUND($data_actual->total),2);

    
      $final = [
        "status" => "ok",
        "title" => ['Planned','Actual'],
        "planned" => $planned,
        "actual" => $actual
      ];
       
       // 4. Return hasil dalam JSON
       return response()->json($final);
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