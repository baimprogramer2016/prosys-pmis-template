<?php

namespace App\Http\Controllers;

use App\Models\DynamicCustom;
use App\Models\DynamicCustomHistory;
use App\Models\MasterCategory;
use App\Models\MasterDiscipline;
use App\Models\MasterStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CustomReportController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.custom-report.report');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCustomReport(Request $request)
    {

        if ($request->ajax()) {
            $tableName = 'custom_' . $request->tab;
            $data = (new DynamicCustom())->setTableName($tableName)
            ->select(['id',
                'document_number',
                'description', 
                'version',
                'typeofreport',
                'author',
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), 
                'path',
                'ext',
             
            ]);
            
            return DataTables::of($data)
            ->addColumn('action', function($row) use($request){
                $fileUrl = asset('storage/' . $row->path);
                $addDropdown = "";
                if(in_array($row->ext,['pdf','jpg','png','jpeg','docx','doc','xls','xlsx','ppt','pptx'])){
                    $addDropdown = ' <a href="" data-bs-toggle="modal" data-bs-target="#modal-pdf" onClick="return viewPdf(' . $row->id . ')" class="dropdown-item cursor-pointer">View</a>';
                }
                $editBtn = '';
                if (Gate::allows('edit_custom_report')) {
                    $editBtn = ' <a class="dropdown-item" href="' . route('custom-report-edit', ['id' => $row->id, 'tab' => $request->tab]) . '">Edit</a>';
                }
            
                // Tombol Delete (Hanya tampil jika user memiliki izin 'delete_schedule')
                $deleteBtn = '';
                if (Gate::allows('delete_custom_report')) {
                    $deleteBtn = '<a href="#" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>';
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
                                <a class="dropdown-item" href="' . $fileUrl . '" download>Download</a>
                                  ' . $editBtn . '
                        ' . $deleteBtn . '
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewShare(' . $row->id . ')" class="dropdown-item cursor-pointer">Share</a>
                                ' . $addDropdown . '                        
                            </div>
                        </div>';
                return $btn;
    
            }) 
        
          
            ->addColumn('version_link', function($row) use($request) {
                $tableNameHistory = 'custom_' . $request->tab . '_history';
            
                $historyCount = DB::table($tableNameHistory)
                ->where('custom_id', $row->id)
                ->count();
            
                if ($historyCount > 0) {
                    $version_link = $row->version . 
                        '<br> <a href="#" data-bs-toggle="modal" data-bs-target="#modal-large" 
                        onClick="return viewHistory(' . $row->id . ')" class="text-center">(Check History)</a>';
                } else {
                    $version_link = $row->version;
                }
            
                return $version_link;
            })
            
                ->rawColumns(['action','version_link' ]) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }

    public function viewTambah(Request $request){
        try{
            $data_status = MasterStatus::get();
            $data_category = MasterCategory::where('category','engineering')->get();
            $data_discipline = MasterDiscipline::get();
            return view('pages.custom-report.report-tambah',[
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
        $typeofreport = $request->input('typeofreport');
        $version = $request->input('version');
        $tab = $request->input('tab');
        $tableName = 'custom_'.$request->input('tab');

        $savedFiles = [];
        foreach ($uploadedFiles as $file) {
            // Split nama file berdasarkan "~"
            $fileName = $file['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
            
           
            // Pindahkan file dari 'temp' ke 'public/engineer'
            $newPath = str_replace('temp', 'public/'.$tab, $file['path']);
            Storage::move($file['path'], $newPath);

            // Simpan ke database atau proses lainnya
            $doc = (new DynamicCustom())->setTableName($tableName);
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->version = $version;
            $doc->typeofreport = $typeofreport;
            $doc->author =Auth::User()->name;;
            $doc->path = str_replace('public/', '', $newPath);
            $doc->ext = $file_ext;
      
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
            $tab = $request->tab;
            $tableName = 'custom_'.$tab;
            $doc = (new DynamicCustom())->setTableName($tableName);
            $document =$doc->find($id);
            $data_status = MasterStatus::get();
            $data_category = MasterCategory::where('category','engineering')->get();
            $data_discipline = MasterDiscipline::get();
            return view('pages.custom-report.report-edit',[
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
        $tab = $request->input('tab');
        $typeofreport = $request->input('typeofreport');
        $tableName = 'custom_'.$tab;
        $tableNameHistory = 'custom_'.$tab.'_history';
        $tableCustom = (new DynamicCustom())->setTableName($tableName);
        $doc = $tableCustom->find($id);

        //masukan ke history dlu

            //insert ke history
            $docHistory = (new DynamicCustomHistory())->setTableName($tableNameHistory);
            $docHistory->custom_id = $doc->id;
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
   

            // Pindahkan file dari 'temp' ke 'public/engineer'
            $newPath = str_replace('temp', 'public/'.$tab, $uploadedFiles[0]['path']);
            Storage::move($uploadedFiles[0]['path'], $newPath);
            $path = str_replace('public/', '', $newPath);
        }
            // Simpan ke database atau proses lainnya
            $doc->document_number = trim($document_number);
            $doc->description = trim($description);
            $doc->version = $version;
            $doc->typeofreport = $typeofreport;
            $doc->author = Auth::User()->name;
            $doc->path = $path;
            $doc->ext = $file_ext;

            $doc->save();

    return response()->json([
        'status' =>'ok',
    ]);
    }

    
    public function pdf(Request $request, $id){ 
        try{

        $tab = $request->input('tab');
        $tableName = 'custom_'.$tab;
           
        $tableCustom = (new DynamicCustom())->setTableName($tableName);

            $document =$tableCustom->find($id);
            return view('pages.custom-report.report-pdf', [
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
            $tab = $request->input('tab');
            $tableName = 'custom_'.$tab;
            
            $tableCustom = (new DynamicCustom())->setTableName($tableName);

            $document =$tableCustom->find($id);
            return view('pages.custom-report.report-share', [
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
    
    
    public function viewDelete(Request $request, $id){
      
        try{
            $tab = $request->input('tab');
            $tableName = 'custom_'.$tab;
            
            $tableCustom = (new DynamicCustom())->setTableName($tableName);

            $document =$tableCustom->find($id);
      
            return view('pages.custom-report.report-delete', [
                "document" => $document,
                "tab" => $tab
            ]);
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 
    
    public function deleted(Request $request,$id){
        $tab = $request->input('tab');
        $tableName = 'custom_'.$tab;
        $tableNameHistory = 'custom_'.$tab.'_history';
        
        $tableCustom = (new DynamicCustom())->setTableName($tableName);

        $task =$tableCustom->find($id);
        $task->delete();

                
        $tableCustomHistory = (new DynamicCustomHistory())->setTableName($tableNameHistory);

        $taskHistory =$tableCustomHistory->where('custom_id',($id));
        $taskHistory->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    } 
    public function history(Request $request, $id){ 
        try{
            $tab = $request->input('tab');
            $tableName = 'custom_'.$tab.'_history';
         
            $tableCustom = (new DynamicCustom())->setTableName($tableName);
            $document =$tableCustom->where('custom_id', $id)->get();
            return view('pages.custom-report.report-history', [
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
