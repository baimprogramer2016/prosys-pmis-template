<?php

namespace App\Http\Controllers;

use App\Models\Ncr;

use App\Models\NcrHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables ;

class NcrController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.ncr.report');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
  
    public function getNcr(Request $request)
    {
      
        if ($request->ajax()) {
            $data = Ncr::select(['id',
                'document_number',
                'title', 
                'description', 
                'pic',
                'category',
                'status',
                DB::raw("DATE_FORMAT(due_date, '%Y-%m-%d') as due_date"), 
                'path',
                'ext',
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
                        <a class="dropdown-item" href="'.route('ncr-edit', $row->id).'">Edit</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewShare(' . $row->id . ')" class="dropdown-item cursor-pointer">Share</a>
                        '.$addDropdown.'                        
                    </div>
                </div>';
                return $btn;
            }) 
          
                ->rawColumns(['action']) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }

    public function tambah(Request $request){
        try{
          
            return view('pages.ncr.report-tambah');
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
        $title = $request->input('title');
        $description = $request->input('description');
        $category = $request->input('category');
        $status = $request->input('status');
        $due_date = $request->input('due_date');
        $pic = $request->input('pic');

        $savedFiles = [];
        foreach ($uploadedFiles as $file) {
            // Split nama file berdasarkan "~"
            $fileName = $file['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
           
            // Pindahkan file dari 'temp' ke 'public/engineer'
            $newPath = str_replace('temp', 'public/ncr', $file['path']);
            Storage::move($file['path'], $newPath);

            // Simpan ke database atau proses lainnya
            $doc = new Ncr();
            $doc->document_number = trim($document_number);
            $doc->title = trim($title);
            $doc->description = trim($description);
            $doc->path = str_replace('public/', '', $newPath);
            $doc->ext = $file_ext;
            $doc->category = $category;
            $doc->status = $status;
            $doc->due_date = $due_date;
            $doc->pic = $pic;
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
            $document = Ncr::find($id);
        
          return view('pages.ncr.report-edit',[
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
        $uploadedFiles = $request->input('uploaded_files');
        $document_number = $request->input('document_number');
        $title = $request->input('title');
        $description = $request->input('description');
        $category = $request->input('category');
        $status = $request->input('status');
        $due_date = $request->input('due_date');
        $pic = $request->input('pic');
       
        $doc = Ncr::find($id);

        $path = $doc->path;
        $file_ext = $doc->ext;
   
        if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
            // Split nama file berdasarkan "~"
            $fileName = $uploadedFiles[0]['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
         
            $newPath = str_replace('temp', 'public/ncr', $uploadedFiles[0]['path']);
            Storage::move($uploadedFiles[0]['path'], $newPath);
            $path = str_replace('public/', '', $newPath);
        }
            // Simpan ke database atau proses lainnya
            $doc->document_number = trim($document_number);
            $doc->title = trim($title);
            $doc->description = trim($description);
            $doc->category = trim($category);
            $doc->status = trim($status);
            $doc->due_date = $due_date;
            $doc->pic = $pic;
            $doc->path = $path;
            $doc->ext = $file_ext;
          
            $doc->save();

    return response()->json([
        'status' =>'ok',
    ]);
    }

    
    public function viewDelete(Request $request, $id){
      
        try{
            $document = Ncr::find($id);
            return view('pages.ncr.report-delete', [
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
            $document = Ncr::find($id);
            return view('pages.ncr.report-share', [
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
            $document = Ncr::find($id);
            return view('pages.ncr.report-pdf', [
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
        $task = Ncr::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
    
    
}
