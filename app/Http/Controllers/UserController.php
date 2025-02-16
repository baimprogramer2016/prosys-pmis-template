<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
use App\Models\ScheduleManagement;
use App\Models\SCurve;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\FacadesDB;
use Yajra\DataTables\Facades\DataTables ;

class UserController extends Controller
{
    public function index(Request $request){
        try{
        
            return view('pages.s-curve.s-curve',[
                "data_category" => MasterCategory::where('category','schedule_management')->get(),
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
                        <a onClick="return viewEdit('.$rowJson. ')" class="dropdown-item cursor-pointer">Edit</a>               
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>               
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

    public function viewEdit(Request $request, $id){
        
        try{
            $document = SCurve::find($id);
        
          return view('pages.s-curve.s-curve-edit',[
            "document" => $document
          ]);
           
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 
    public function update(Request $request, $id)
    {
       
        $description = $request->input('description');
        $category = $request->input('category');
        $tanggal = $request->input('tanggal');
        $percent = $request->input('percent');
       
        $doc = SCurve::find($id);
        $doc->description = $description;
        $doc->category = $category;
        $doc->tanggal = $tanggal;
        $doc->percent = $percent;
        $doc->author = Auth::User()->name;

        $doc->save();

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

}