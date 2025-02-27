<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\ScheduleManagement;
use App\Models\SCurve;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\FacadesDB;
use Monolog\Handler\RollbarHandler;
use Yajra\DataTables\Facades\DataTables ;

class PermissionController extends Controller
{
    public function index(Request $request){
        try{
        
            return view('pages.permission.permission');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
  
    public function getPermission(Request $request)
    {
      
        if ($request->ajax()) {
            $data = Permission::select([
                'name',
                'guard_name'
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
            $verifikasi = User::where('username', $request->input('username'))->first();

            if($verifikasi){
                if($verifikasi->password == $request->input('password')){
                    $pass = $verifikasi->password;
                }else{
                    $pass = bcrypt($request->password);
                }
                $verifikasi->name = $request->input('name');
                $verifikasi->email = $request->input('email');
                $verifikasi->password = $pass;
                $verifikasi->save();
            }else{
                $user = new User();
                $user->name = $request->input('name');
                $user->username = $request->input('username');
                $user->email =$request->input('email');
                $user->password = bcrypt($request->input('password')); // Menggunakan bcrypt untuk mengenkripsi password
                $user->save();
            }

            if($request->input('model_id')){
                UserRole::updateOrInsert(
                    ['model_id' => $request->input('model_id')], // Kondisi pencarian
                    [
                        'role_id' => $request->input('role'),
                        'model_type' => 'App\Models\User'
                    ] 
                );
            }else{
                UserRole::create([
                    'role_id' => $request->input('role'),
                    'model_id' => $user->id,
                     'model_type' => 'App\Models\User'
                ]);
            }
            
            

            return response()->json([
                'status' =>'ok',
            
            ]);
    }

    public function viewEdit(Request $request, $id){
        
        try{
            $document = User::find($id);
        
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
            $document = Role::find($id);
            return view('pages.role.role-delete', [
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
      
        $task = Role::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }

}