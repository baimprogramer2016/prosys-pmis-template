<?php

namespace App\Http\Controllers;

use App\Models\ReportMonthly;
use App\Models\ReportMonthlyHistory;
use App\Models\ReportWeekly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables ;

class ReportMonthlyController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.report-monthly.report');
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
            $data = ReportMonthly::select(['id',
                'document_number',
                'description', 
                'author',
                'typeofreport',
                'version',
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
                        <a class="dropdown-item" href="'.route('report-monthly-edit', $row->id).'">Edit</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewShare(' . $row->id . ')" class="dropdown-item cursor-pointer">Share</a>
                        '.$addDropdown.'                        
                    </div>
                </div>';
                return $btn;
            }) 
            ->addColumn('version_link', function($row) {
                if($row->r_history->count() >0){
                    $version_link = $row->version.'<br> <a href="" data-bs-toggle="modal" data-bs-target="#modal-large" onClick="return viewHistory(' . $row->id . ')" class="text-center">(Check_History)</a>';
                }else{
                    $version_link =$row->version;
                }
                            
                return $version_link;
            })
                ->rawColumns(['action','version_link']) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }

    public function tambah(Request $request){
        try{
          
            return view('pages.report-monthly.report-tambah');
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
        $typeofreport = $request->input('typeofreport');
        $version = $request->input('version');

        $savedFiles = [];
        foreach ($uploadedFiles as $file) {
            // Split nama file berdasarkan "~"
            $fileName = $file['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
           
            // Pindahkan file dari 'temp' ke 'public/engineer'
            $newPath = str_replace('temp', 'public/reportmonthly', $file['path']);
            Storage::move($file['path'], $newPath);

            // Simpan ke database atau proses lainnya
            $doc = new ReportMonthly();
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->path = str_replace('public/', '', $newPath);
            $doc->ext = $file_ext;
            $doc->typeofreport = $typeofreport;
            $doc->version = $version;
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
            $document = ReportMonthly::find($id);
        
          return view('pages.report-monthly.report-edit',[
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
        $typeofreport = $request->input('typeofreport');
        $version = $request->input('version');
       
        $doc = ReportMonthly::find($id);
          //insert ke history
          $docHistory = new ReportMonthlyHistory();
          $docHistory->report_monthly_id = $doc->id;
          $docHistory->document_number = $doc->document_number;
          $docHistory->description = $doc->description;
          $docHistory->version = $doc->version;
          $docHistory->author = $doc->author;
          $docHistory->typeofreport = $doc->typeofreport;
          $docHistory->path = $doc->path;
          $docHistory->ext = $doc->ext;
          $docHistory->save();        

        $path = $doc->path;
        $file_ext = $doc->ext;
   
        if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
            // Split nama file berdasarkan "~"
            $fileName = $uploadedFiles[0]['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
         
            $newPath = str_replace('temp', 'public/reportmonthly', $uploadedFiles[0]['path']);
            Storage::move($uploadedFiles[0]['path'], $newPath);
            $path = str_replace('public/', '', $newPath);
        }
            // Simpan ke database atau proses lainnya
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->typeofreport = trim($typeofreport);
            $doc->version = trim($version);
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
            $document = ReportMonthly::find($id);
            return view('pages.report-monthly.report-delete', [
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
            $document = ReportMonthly::find($id);
            return view('pages.report-monthly.report-share', [
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
            $document = ReportMonthly::find($id);
            return view('pages.report-monthly.report-pdf', [
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
        $task = ReportMonthly::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
    public function history(Request $request, $id){ 
        try{
            $document = ReportMonthlyHistory::where('report_monthly_id', $id)->get();
            return view('pages.report-daily.report-history', [
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
}
