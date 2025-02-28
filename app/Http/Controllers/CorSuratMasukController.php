<?php

namespace App\Http\Controllers;

use App\Models\CorSuratMasuk;
use App\Models\CorSuratMasukHistory;
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

class CorSuratMasukController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.surat-masuk.surat-masuk');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
  
    public function getSuratMasuk(Request $request)
    {
      
        if ($request->ajax()) {
            $data = CorSuratMasuk::select(['id',
                'document_number',
                'description', 
                'typeofincomingdocument', 
                'author',
                'from_',
                'version',
                'hardcopy',
                'status',
                'email',
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal"), 
                'path',
                'ext',
                'category'
        ]);
            
            return DataTables::of($data)
            ->addColumn('action', function($row) {
                $fileUrl = asset('storage/' . $row->path);
                $addDropdown = "";
                if(in_array($row->ext,['pdf','jpg','png','jpeg','docx','doc','xls','xlsx','ppt','pptx'])){
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
                        <a class="dropdown-item" href="'.route('surat-masuk-edit', $row->id).'">Edit</a>
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
          
            return view('pages.surat-masuk.surat-masuk-tambah',[
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
        $category = $request->input('category');
        $typeofincomingdocument = $request->input('typeofincomingdocument');
        $from = $request->input('from');
        $status = $request->input('status');
        $version = $request->input('version');
        $hardcopy = $request->input('hardcopy');
        $email = $request->input('email');
      

        $savedFiles = [];
        foreach ($uploadedFiles as $file) {
            // Split nama file berdasarkan "~"
            $fileName = $file['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
           
            // Pindahkan file dari 'temp' ke 'public/engineer'
            $newPath = str_replace('temp', 'public/suratmasuk', $file['path']);
            Storage::move($file['path'], $newPath);

            // Simpan ke database atau proses lainnya
            $doc = new CorSuratMasuk();
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->typeofincomingdocument = trim($typeofincomingdocument);
            $doc->category = trim($category);
            $doc->status = trim($status);
            $doc->from_ = trim($from);
            $doc->path = str_replace('public/', '', $newPath);
            $doc->ext = $file_ext;
            $doc->author =Auth::User()->name;
            $doc->tanggal = Carbon::now()->format('Y-m-d');
            $doc->version = trim($version);
            $doc->hardcopy = trim($hardcopy);
            $doc->email = trim($email);
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
            $document = CorSuratMasuk::find($id);
        
          return view('pages.surat-masuk.surat-masuk-edit',[
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
        $category = $request->input('category');
        $typeofincomingdocument = $request->input('typeofincomingdocument');
        $from = $request->input('from');
        $version = $request->input('version');
        $hardcopy = $request->input('hardcopy');
        $status = $request->input('status');
        $email = $request->input('email');
      
        $doc = CorSuratMasuk::find($id);
          //insert ke history
          $docHistory = new CorSuratMasukHistory();
          $docHistory->cor_surat_masuk_id = $doc->id;
          $docHistory->document_number = $doc->document_number;
          $docHistory->description = $doc->description;
          $docHistory->version =$doc->version;
          $docHistory->category = $doc->category;
          $docHistory->typeofincomingdocument = $doc->typeofincomingdocument;
          $docHistory->from_ = $doc->from_;
          $docHistory->hardcopy = $doc->hardcopy;
          $docHistory->status = $doc->status;
          $docHistory->email = $doc->email;
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
         
            $newPath = str_replace('temp', 'public/suratmasuk', $uploadedFiles[0]['path']);
            Storage::move($uploadedFiles[0]['path'], $newPath);
            $path = str_replace('public/', '', $newPath);
        }
            // Simpan ke database atau proses lainnya
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->author = Auth::User()->name;
            $doc->path = $path;
            $doc->category = $category;
            $doc->typeofincomingdocument = $typeofincomingdocument;
            $doc->from_ = $from;
            $doc->ext = $file_ext;
            $doc->version = $version;
            $doc->status = $status;
            $doc->hardcopy = $hardcopy;
            $doc->email = $email;
          
            $doc->save();

    return response()->json([
        'status' =>'ok',
    ]);
    }

    
    public function viewDelete(Request $request, $id){
      
        try{
            $document = CorSuratMasuk::find($id);
            return view('pages.surat-masuk.surat-masuk-delete', [
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
            $document = CorSuratMasuk::find($id);
            return view('pages.surat-masuk.surat-masuk-share', [
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
            $document = CorSuratMasuk::find($id);
            return view('pages.surat-masuk.surat-masuk-pdf', [
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
        $task = CorSuratMasuk::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
    public function history(Request $request, $id){ 
        try{
            $document = CorSuratMasukHistory::where('cor_surat_masuk_id', $id)->get();
            return view('pages.surat-masuk.surat-masuk-history', [
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
            $surat = CorSuratMasuk::find($id);
            return view('pages.surat-masuk.surat-masuk-update-status', [
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
        $task = CorSuratMasuk::find($id);
        $task->status = 'close';
        
        $task->save();
 
        return response()->json([
            "message"=> "updated"
        ]);
    }
    
}
