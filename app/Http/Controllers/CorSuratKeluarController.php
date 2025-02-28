<?php

namespace App\Http\Controllers;

use App\Models\CorSuratKeluar;
use App\Models\CorSuratKeluarHistory;
use App\Models\MasterCategory;
use App\Models\Mom;
use App\Models\ReportDaily;
use App\Models\Sop;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables ;

class CorSuratKeluarController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.surat-keluar.surat-keluar');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
  
    public function getSuratKeluar(Request $request)
    {
      
        if ($request->ajax()) {
            $data = CorSuratKeluar::select(['id',
                'document_number',
                'description', 
                'recipient', 
                'author',
                'attn',
                'version',
                'hardcopy',
                'email',
                'category',
                'status',
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal"), 
                'path',
                'ext',
        ]);
            
            return DataTables::of($data)
            ->addColumn('action', function($row) {
                $fileUrl = asset('storage/' . $row->path);

                $addDropdown = "";
                if(in_array($row->ext,['pdf','jpg','png','jpeg','docx','doc'])){
                    $addDropdown = ' <a href="" data-bs-toggle="modal" data-bs-target="#modal-pdf" onClick="return viewPdf(' . $row->id . ')" class="dropdown-item cursor-pointer">View</a>';
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
                        <a class="dropdown-item" href="'.$fileUrl.'" download>Download</a>
                        <a class="dropdown-item" href="'.route('surat-keluar-edit', $row->id).'">Edit</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewShare(' . $row->id . ')" class="dropdown-item cursor-pointer">Share</a>
                        '.$addDropdown.'                        
                    </div>
                </div>';
                return $btn;
            }) 
            ->addColumn('isHardCopy', function($row) {
                if($row->hardcopy == '1'){
                    $hardcopycheck = '<i class="fas fa-check-circle text-success text-center"> Done</i>';
                    
                }else{
                    $hardcopycheck = '';
                }
                
                return $hardcopycheck;
            }) 
            ->addColumn('isEmail', function($row) {
                if($row->email == '1'){
                    $emailcheck = '<i class="fas fa-check-circle text-success text-center"> Done</i>';
                    
                }else{
                    $emailcheck = '';
                }
                
                return $emailcheck;
            }) 
            ->addColumn('category_desc', function($row) {
                
                return optional($row->r_category)->description;
            }) 
            ->addColumn('version_link', function($row) {
                if($row->r_history->count() >0){
                    $version_link = $row->version.'<br> <a href="" data-bs-toggle="modal" data-bs-target="#modal-large" onClick="return viewHistory(' . $row->id . ')" class="text-center">(Check_History)</a>';
                }else{
                    $version_link =$row->version;
                }
                            
                return $version_link;
            })
            ->addColumn('status_badge', function($row) {
                if($row->status == 'open'){
                    $badge = "<span class='badge badge-secondary'>".$row->status."</span>";
                }else{
                    $badge = "<span class='badge badge-success'>".$row->status."</span>";
                }
                    
                    return $badge;
                    })
            ->addColumn('btn_status', function($row) {
                if ($row->status == 'close') {
                    $btn_status = ' <span class="btn btn-sm" style="background-color:gray;color:#fff;">Close</span>';
                } else {
                    $btn_status = ' <a data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewUpdateStatus(' . $row->id . ')" class="btn btn-success btn-sm">Close</a>';
                }
                    return $btn_status;
                    })
            ->rawColumns(['action','isHardCopy','isEmail','version_link','btn_status','status_badge']) // Agar HTML di kolom 'action' dirender
            ->make(true);
        }
    }

    public function tambah(Request $request){
        try{
          
            return view('pages.surat-keluar.surat-keluar-tambah',[
                "data_category" => MasterCategory::where('category','surat')->get()
            ]);
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 
    public function uploadTemp(Request $request)
    {
        $file = $request->file('file');
        $path = $file->store('temp');  // Simpan sementara di folder 'temp'
        
        return response()->json([
            'path' => $path,
            'name' => $file->getClientOriginalName(),
        ]);
    }

    public function saveUploads(Request $request)
    {
        $uploadedFiles = $request->input('uploaded_files');
        $document_number = $request->input('document_number');
        $description = $request->input('description');
        $recipient = $request->input('recipient');
        $attn = $request->input('attn');
        $version = $request->input('version');
        $hardcopy = $request->input('hardcopy');
        $email = $request->input('email');
        $status = $request->input('status');
        $category = $request->input('category');
   
        $savedFiles = [];
        foreach ($uploadedFiles as $file) {
            // Split nama file berdasarkan "~"
            $fileName = $file['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
           
            // Pindahkan file dari 'temp' ke 'public/engineer'
            $newPath = str_replace('temp', 'public/suratkeluar', $file['path']);
            Storage::move($file['path'], $newPath);

            // Simpan ke database atau proses lainnya
            $doc = new CorSuratKeluar();
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->recipient = trim($recipient);
            $doc->attn = trim($attn);
            $doc->version = trim($version);
            $doc->hardcopy = trim($hardcopy);
            $doc->email = trim($email);
            $doc->category = trim($category);
            $doc->status = trim($status);
            $doc->path = str_replace('public/', '', $newPath);
            $doc->ext = $file_ext;
            $doc->author =Auth::User()->name;
            $doc->tanggal = Carbon::now()->format('Y-m-d');
            $doc->save();

            $savedFiles[] = $doc;
        }

    return response()->json([
        'status' =>'ok',
        'data' => $savedFiles
    ]);
    }

    public function viewEdit(Request $request, $id){
        
        try{
            $document = CorSuratKeluar::find($id);
        
          return view('pages.surat-keluar.surat-keluar-edit',[
            "document" => $document,
            "data_category" => MasterCategory::where('category','surat')->get()
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
        $uploadedFiles = $request->input('uploaded_files');
        $document_number = $request->input('document_number');
        $description = $request->input('description');
        $recipient = $request->input('recipient');
        $attn = $request->input('attn');
        $version = $request->input('version');
        $hardcopy = $request->input('hardcopy');
        $status = $request->input('status');
        $email = $request->input('email');
        $category = $request->input('category');
      
        $doc = CorSuratKeluar::find($id);
          //insert ke history
          $docHistory = new CorSuratKeluarHistory();
          $docHistory->cor_surat_keluar_id = $doc->id;
          $docHistory->document_number = $doc->document_number;
          $docHistory->description = $doc->description;
          $docHistory->recipient = $doc->recipient;
          $docHistory->attn = $doc->attn;
          $docHistory->hardcopy = $doc->hardcopy;
          $docHistory->email = $doc->email;
          $docHistory->category = $doc->category;
          $docHistory->status = $doc->status;
          $docHistory->version = $doc->version;
          $docHistory->author = $doc->author;
          $docHistory->tanggal = $doc->created_at;
          $docHistory->path = $doc->path;
          $docHistory->ext = $doc->ext;
          $docHistory->save();        
 

        $path = $doc->path;
        $file_ext = $doc->ext;
   
        if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
            // Split nama file berdasarkan "~"
            $fileName = $uploadedFiles[0]['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
         
            $newPath = str_replace('temp', 'public/suratkeluar', $uploadedFiles[0]['path']);
            Storage::move($uploadedFiles[0]['path'], $newPath);
            $path = str_replace('public/', '', $newPath);
        }
            // Simpan ke database atau proses lainnya
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->author = Auth::User()->name;
            $doc->path = $path;
            $doc->recipient = $recipient;
            $doc->attn = $attn;
            $doc->version = $version;
            $doc->hardcopy = $hardcopy;
            $doc->status = $status;
            $doc->email = $email;
            $doc->category = $category;
            $doc->ext = $file_ext;
          
            $doc->save();

    return response()->json([
        'status' =>'ok',
    ]);
    }

    
    public function viewDelete(Request $request, $id){
      
        try{
            $document = CorSuratKeluar::find($id);
            return view('pages.surat-keluar.surat-keluar-delete', [
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
    
    public function share(Request $request, $id){
      
        try{
            $document = CorSuratKeluar::find($id);
            return view('pages.surat-keluar.surat-keluar-share', [
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

    public function pdf(Request $request, $id){ 
        try{
            $document = CorSuratKeluar::find($id);
            return view('pages.surat-keluar.surat-keluar-pdf', [
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
        $task = CorSuratKeluar::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
    public function history(Request $request, $id){ 
        try{
            $document = CorSuratKeluarHistory::where('cor_surat_keluar_id', $id)->get();
            return view('pages.surat-keluar.surat-keluar-history', [
                "documents" => $document,
            ]);
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 
    
    public function viewUpdateStatus(Request $request, $id){ 
        try{
            $surat = CorSuratKeluar::find($id);
            return view('pages.surat-keluar.surat-keluar-update-status', [
                "data_surat" => $surat,
            ]);
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 

    public function updateStatus($id, Request $request){
        $task = CorSuratKeluar::find($id);
        $task->status = 'close';
        
        $task->save();
 
        return response()->json([
            "message"=> "updated"
        ]);
    }
}
