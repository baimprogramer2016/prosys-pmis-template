<?php

namespace App\Http\Controllers;

use App\Models\ConstructionDocument;
use App\Models\ConstructionDocumentHistory;
use App\Models\MasterCategory;
use App\Models\MasterDiscipline;
use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables ;

class ConstructionDocumentController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.construction-document.construction-document');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
    public function check(Request $request){
        try{
            return view('pages.construction-document.construction-document-check');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
    public function getConstructionDocument(Request $request,$field, $status)
    {
      
        if ($request->ajax()) {
            $data = ConstructionDocument::select(['id',
                'document_number',
                'description', 
                'discipline', 
                'version',
                'author',
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal"), 
                'category',
                'status',
                'path',
                'ext',
                'size',
                'checker',
                'reviewer',
                'approver',
                'uploader'
            ])->when($field == 'verifikasi', function ($query) use ($status) {
                return $query->where('status', $status);
            })->when($field == 'design', function ($query) use ($status) {
                return $query->where('category', $status);
            });
            
            return DataTables::of($data)
            ->addColumn('action', function($row) {
                $fileUrl = asset('storage/' . $row->path);

                if($row->ext == 'pdf'){
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
                        <a class="dropdown-item" href="'.route('construction-document-edit', $row->id).'">Edit</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewShare(' . $row->id . ')" class="dropdown-item cursor-pointer">Share</a>
                        '.$addDropdown.'                        
                    </div>
                </div>';
                return $btn;
            }) 
            ->addColumn('status_badge', function($row) {
                if($row->status == 'new'){
                    $text_status = 'Click_Check';
                }elseif($row->status =='check'){
                    $text_status = 'Click_Review';
                }elseif($row->status =='review'){
                    $text_status = 'Click_Approve';
                }else{
                    $text_status = '';
                }

                $badge = '<button data-bs-toggle="modal" data-bs-target="#modal"
                                class="btn btn-success btn-xs me-2"
                                onClick="return viewModal(' . $row->id . ')"
                              >
                                '.$text_status.'
                              </button>';
                    
                    return $badge;
            })
            ->addColumn('discipline_desc', function($row) {
            
                $discipline_desc = optional($row->r_discipline)->description;
                            
                return $discipline_desc;
            })
            ->addColumn('category_desc', function($row) {
            
                $category_desc = optional($row->r_category)->description;
                            
                return $category_desc;
            })
            ->addColumn('version_link', function($row) {
                if($row->r_history->count() >0){
                    $version_link = $row->version.'<br> <a href="" data-bs-toggle="modal" data-bs-target="#modal-large" onClick="return viewHistory(' . $row->id . ')" class="text-center">(Check_History)</a>';
                }else{
                    $version_link =$row->version;
                }
                            
                return $version_link;
            })
                ->rawColumns(['action','status_badge','discipline_desc','category_desc','version_link' ]) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }

    

    public function viewTambah(Request $request){
        try{
            $data_status = MasterStatus::get();
            $data_category = MasterCategory::where('category','engineering')->get();
            $data_discipline = MasterDiscipline::get();
            return view('pages.construction-document.construction-document-tambah',[
                "data_status" => $data_status,
                "data_category" => $data_category,
                "data_discipline" => $data_discipline
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
        $version = $request->input('version');
        $tanggal = $request->input('tanggal');
        $discipline = $request->input('discipline');
        $category = $request->input('category');
        $status = $request->input('status');

        $savedFiles = [];
        foreach ($uploadedFiles as $file) {
            // Split nama file berdasarkan "~"
            $fileName = $file['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileSize =0; // dalam byte
           
            // Pindahkan file dari 'temp' ke 'public/constructiondocument'
            $newPath = str_replace('temp', 'public/constructiondocument', $file['path']);
            Storage::move($file['path'], $newPath);

            // Simpan ke database atau proses lainnya
            $doc = new ConstructionDocument();
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->version = $version;
            $doc->author =Auth::User()->name;;
            $doc->tanggal = $tanggal;
            $doc->discipline = $discipline;
            $doc->category = $category;
            $doc->status = $status;
            $doc->path = str_replace('public/', '', $newPath);
            $doc->ext = $file_ext;
            $doc->size = $fileSize;
            $doc->uploader =Auth::User()->name;
            $doc->save();

            $savedFiles[] = $doc;
        }

    return response()->json([
        'status' =>'ok',
        'data' => $savedFiles
    ]);
    }

    public function viewCheckModal(Request $request, $id){
      
        try{
            $document = ConstructionDocument::find($id);
            return view('pages.construction-document.construction-document-check-modal', [
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
    
    public function updateCheck($id, Request $request){
        $task = ConstructionDocument::find($id);
        $task->status = 'check';
        $task->checker = Auth::User()->name;
        
        $task->save();
 
        return response()->json([
            "message"=> "updated"
        ]);
    }
    
    

    public function viewEdit(Request $request, $id){
        
        try{
            $document = ConstructionDocument::find($id);
         
            
            $data_status = MasterStatus::get();
            $data_category = MasterCategory::where('category','engineering')->get();
            $data_discipline = MasterDiscipline::get();
            return view('pages.construction-document.construction-document-edit',[
                "data_status" => $data_status,
                "data_category" => $data_category,
                "data_discipline" => $data_discipline,
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
    public function updateUploads(Request $request, $id)
    {
        $uploadedFiles = $request->input('uploaded_files');
        $document_number = $request->input('document_number');
        $description = $request->input('description');
        $version = $request->input('version');
        $tanggal = $request->input('tanggal');
        $discipline = $request->input('discipline');
        $category = $request->input('category');
        $status = $request->input('status');


        $doc = ConstructionDocument::find($id);

        //masukan ke history dlu

            //insert ke history
            $docHistory = new ConstructionDocumentHistory();
            $docHistory->construction_document_id = $doc->id;
            $docHistory->document_number = $doc->document_number;
            $docHistory->description = $doc->description;
            $docHistory->version = $doc->version;
            $docHistory->author = $doc->author;
            $docHistory->tanggal = $doc->tanggal;
            $docHistory->discipline = $doc->discipline;
            $docHistory->category = $doc->category;
            $docHistory->status = $doc->status;
            $docHistory->path = $doc->path;
            $docHistory->ext = $doc->ext;
            $docHistory->size = $doc->size;
            $docHistory->uploader = $doc->uploader;
            $docHistory->checker = $doc->uploader;
            $docHistory->reviewer = $doc->reviewer;
            $docHistory->approver = $doc->approver;
            $docHistory->save();        


        $path = $doc->path;
        $file_ext = $doc->ext;
        $fileSize = $doc->size;
        if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
            // Split nama file berdasarkan "~"
            $fileName = $uploadedFiles[0]['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
            $fileSize =0; // dalam byte

            // Pindahkan file dari 'temp' ke 'public/constructiondocument'
            $newPath = str_replace('temp', 'public/constructiondocument', $uploadedFiles[0]['path']);
            Storage::move($uploadedFiles[0]['path'], $newPath);
            $path = str_replace('public/', '', $newPath);
        }
            // Simpan ke database atau proses lainnya
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->version = $version;
            $doc->author = Auth::User()->name;
            $doc->tanggal = $tanggal;
            $doc->discipline = $discipline;
            $doc->category = $category;
            $doc->status = $status;
            $doc->path = $path;
            $doc->ext = $file_ext;
            $doc->size = $fileSize;
            $doc->save();

    return response()->json([
        'status' =>'ok',
    ]);
    }

    
    public function viewDelete(Request $request, $id){
      
        try{
            $document = ConstructionDocument::find($id);
            return view('pages.construction-document.construction-document-delete', [
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
            $document = ConstructionDocument::find($id);
            return view('pages.construction-document.construction-document-share', [
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
            $document = ConstructionDocument::find($id);
            return view('pages.construction-document.construction-document-pdf', [
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

    public function history(Request $request, $id){ 
        try{
            $document = ConstructionDocumentHistory::where('construction_document_id', $id)->get();
            return view('pages.construction-document.construction-document-history', [
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

    
    public function deleted($id){
        $task = ConstructionDocument::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }

   //REVIEWER
   public function review(Request $request){
    try{
        return view('pages.construction-document.construction-document-review');
    }catch (Throwable $e) {
        // Tangani error
        return response()->json([
            'message' => 'Terjadi kesalahan saat menyimpan data.',
            'error' => $e->getMessage()
        ], 500);
    }
    }
    
    public function viewReviewModal(Request $request, $id){
      
        try{
            $document = ConstructionDocument::find($id);
            return view('pages.construction-document.construction-document-review-modal', [
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
      
    public function updateReview($id, Request $request){
        $task = ConstructionDocument::find($id);
        $task->status = 'review';
        $task->reviewer = Auth::User()->name;
        
        $task->save();
 
        return response()->json([
            "message"=> "updated"
        ]);
    }

    
   //APPROVE
   public function approve(Request $request){
    try{
        return view('pages.construction-document.construction-document-approve');
    }catch (Throwable $e) {
        // Tangani error
        return response()->json([
            'message' => 'Terjadi kesalahan saat menyimpan data.',
            'error' => $e->getMessage()
        ], 500);
    }
    }
    
    public function viewApproveModal(Request $request, $id){
      
        try{
            $document = ConstructionDocument::find($id);
            return view('pages.construction-document.construction-document-approve-modal', [
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
      
    public function updateApprove($id, Request $request){
        $task = ConstructionDocument::find($id);
        $task->status = $request->approval_status;
        $task->approver = Auth::User()->name;
        
        $task->save();
 
        return response()->json([
            "message"=> "updated"
        ]);
    }
    
    
    public function cdr(Request $request){
        try{
            $documents_group_status = ConstructionDocument::select('status', DB::raw('count(*) as total'))
                ->groupBy('status')
                ->get();
            foreach($documents_group_status as $item_group){
                if($item_group->status == 'new'){
                    $item_group_color = 'icon-success'; 
                }else if($item_group->status == 'check'){
                    $item_group_color = 'icon-warning'; 
                }else if($item_group->status == 'review'){
                    $item_group_color = 'icon-secondary'; 
                }else if($item_group->status == 'approve'){
                    $item_group_color = 'icon-primary'; 
                }else if($item_group->status == 'notapprove'){
                    $item_group_color = 'icon-danger'; 
                }
                $item_group->color = $item_group_color;
            }    
            return view('pages.construction-document.construction-document',[
                "documents_group_status" => $documents_group_status
            ]);
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
        
}
