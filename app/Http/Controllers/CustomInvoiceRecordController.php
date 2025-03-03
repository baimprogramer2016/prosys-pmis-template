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
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CustomInvoiceRecordController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.custom-invoice-record.report');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCustomInvoiceRecord(Request $request)
    {

        if ($request->ajax()) {
            $tableName = 'custom_' . $request->tab;
            $data = (new DynamicCustom())->setTableName($tableName)
            ->select(['id',
                'no_invoice', 
                'description', 
                'status',
                DB::raw("DATE_FORMAT(invoice_date, '%Y-%m-%d') as invoice_date"), 
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
                                <a class="dropdown-item" href="' . route('custom-invoice-record-edit', ['id' => $row->id, 'tab' => $request->tab]) . '">Edit</a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewShare(' . $row->id . ')" class="dropdown-item cursor-pointer">Share</a>
                                ' . $addDropdown . '                        
                            </div>
                        </div>';
                return $btn;
    
            }) 
        
            
                ->rawColumns(['action' ]) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }

    public function viewTambah(Request $request){
        try{
            $data_status = MasterStatus::get();
            $data_category = MasterCategory::where('category','engineering')->get();
            $data_discipline = MasterDiscipline::get();
            return view('pages.custom-invoice-record.report-tambah',[
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
        $no_invoice = $request->input('no_invoice');
        $description = $request->input('description');
        $status = $request->input('status');
        $invoice_date = $request->input('invoice_date');
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
            $doc->no_invoice = trim($no_invoice);
            $doc->description = trim($description);
            $doc->status = trim($status);
            $doc->invoice_date = $invoice_date;
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
            return view('pages.custom-invoice-record.report-edit',[
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
        $no_invoice = $request->input('no_invoice');
        $status = $request->input('status');
        $description = $request->input('description');
        $invoice_date = $request->input('invoice_date');
        $tab = $request->input('tab');
        $tableName = 'custom_'.$tab;
     

        $tableCustom = (new DynamicCustom())->setTableName($tableName);
        $doc = $tableCustom->find($id);
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
            $doc->no_invoice = trim($no_invoice);
            $doc->description = trim($description);
            $doc->status = trim($status);
            $doc->invoice_date = $invoice_date;
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
            return view('pages.custom-invoice-record.report-pdf', [
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
            return view('pages.custom-invoice-record.report-share', [
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
      
            return view('pages.custom-invoice-record.report-delete', [
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
 
        return response()->json([
            "action"=> "deleted"
        ]);
    } 
    
}
