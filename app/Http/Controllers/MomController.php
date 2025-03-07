<?php

namespace App\Http\Controllers;

use App\Models\Mom;
use App\Models\ReportDaily;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables ;

class MomController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.mom.mom');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
  
    public function getReport(Request $request)
    {
      
        if ($request->ajax()) {
            $data = Mom::select(['id',
                'document_number',
                'description', 
                'author',
                'typeofmeeting',
                'meetinglocation',
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), 
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
                $editBtn = '';
                if (Gate::allows('edit_minutes_of_meeting')) {
                    $editBtn = '<a class="dropdown-item" href="'.route('mom-edit', $row->id).'">Edit</a>';
                }
            
                // Tombol Delete (Hanya tampil jika user memiliki izin 'delete_schedule')
                $deleteBtn = '';
                if (Gate::allows('delete_minutes_of_meeting')) {
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
                        <a class="dropdown-item" href="'.$fileUrl.'" download>Download</a>
                         ' . $editBtn . '
                        ' . $deleteBtn . '
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
          
            return view('pages.mom.mom-tambah');
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
            'path' => $path, //lokasi dan nama file disimpan ditemp
            'name' => $file->getClientOriginalName(),
        ]);
    }

    public function saveUploads(Request $request)
    {
        $uploadedFiles = $request->input('uploaded_files');
        $document_number = $request->input('document_number');
        $description = $request->input('description');
        $typeofmeeting = $request->input('typeofmeeting');
        $meetinglocation = $request->input('meetinglocation');

        $savedFiles = [];
        foreach ($uploadedFiles as $file) {
            // Split nama file berdasarkan "~"
            $fileName = $file['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
           
            // Pindahkan file dari 'temp' ke 'public/engineer'
            $newPath = str_replace('temp', 'public/mom', $file['path']);
            Storage::move($file['path'], $newPath);

            // Simpan ke database atau proses lainnya
            $doc = new Mom();
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->path = str_replace('public/', '', $newPath);
            $doc->ext = $file_ext;
            $doc->typeofmeeting = $typeofmeeting;
            $doc->meetinglocation = $meetinglocation;
            $doc->author =Auth::User()->name;
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
            $document = Mom::find($id);
        
          return view('pages.mom.mom-edit',[
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
        $description = $request->input('description');
        $typeofmeeting = $request->input('typeofmeeting');
        $meetinglocation = $request->input('meetinglocation');
       
        $doc = Mom::find($id);

        $path = $doc->path;
        $file_ext = $doc->ext;
   
        if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
            // Split nama file berdasarkan "~"
            $fileName = $uploadedFiles[0]['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
         
            $newPath = str_replace('temp', 'public/mom', $uploadedFiles[0]['path']);
            Storage::move($uploadedFiles[0]['path'], $newPath);
            $path = str_replace('public/', '', $newPath);
        }
            // Simpan ke database atau proses lainnya
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->typeofmeeting = trim($typeofmeeting);
            $doc->meetinglocation = trim($meetinglocation);
            $doc->author = Auth::User()->name;
            $doc->path = $path;
            $doc->ext = $file_ext;
          
            $doc->save();

    return response()->json([
        'status' =>'ok',
    ]);
    }

    
    public function viewDelete(Request $request, $id){
      
        try{
            $document = Mom::find($id);
            return view('pages.mom.mom-delete', [
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
            $document = Mom::find($id);
            return view('pages.mom.mom-share', [
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
            $document = Mom::find($id);
            return view('pages.mom.mom-pdf', [
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
        $task = Mom::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
}
