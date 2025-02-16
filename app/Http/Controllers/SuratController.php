<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Surat;
use App\Models\Task;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables ;


class SuratController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.surat.surat');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
    public function getSurat(Request $request)
    {
     
        if ($request->ajax()) {
            $data = Surat::select(['id',
                'nomor',
                'perihal', 
                'jenis', 
                'status', 
                DB::raw("DATE_FORMAT(tanggal, '%Y-%m-%d') as tanggal"), 
                'path',
                'ext'
            ]);
            
            return DataTables::of($data)
            ->addColumn('action', function($row) {
                $fileUrl = asset('storage/' . $row->path);
                $btn = '<a href="'.$fileUrl.'" download class="btn btn-primary btn-sm">Download</a>';                
                $btn .= ' <a data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="btn btn-danger btn-sm">Delete</a>';
                if ($row->status == 'close') {
                    $btn .= ' <span class="btn btn-sm" style="background-color:gray;color:#fff;">Close</span>';
                } else {
                    $btn .= ' <a data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewEdit(' . $row->id . ')" class="btn btn-success btn-sm">Close</a>';
                }
        
                return $btn;
            })
            
            ->addColumn('status_badge', function($row) {
                if($row->status == 'open'){
                    $badge = "<span class='badge badge-secondary'>".$row->status."</span>";
                }else{
                    $badge = "<span class='badge badge-success'>".$row->status."</span>";
                }
                    
                    return $badge;
                    })
                ->rawColumns(['action','status_badge']) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }

    public function viewTambah(Request $request){
        try{
            return view('pages.surat.surat-tambah');
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
        $jenis = $request->input('jenis');
        $tanggal = $request->input('tanggal');

        $savedFiles = [];
        foreach ($uploadedFiles as $file) {
            // Split nama file berdasarkan "~"
            $fileName = $file['fileName'];
            [$nomor, $perihal] = explode('~', pathinfo($fileName, PATHINFO_FILENAME) );
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

            // Pindahkan file dari 'temp' ke 'public/surat'
            $newPath = str_replace('temp', 'public/surat', $file['path']);
            Storage::move($file['path'], $newPath);

            // Simpan ke database atau proses lainnya
            $surat = new Surat();
            $surat->nomor = trim($nomor);
            $surat->perihal = trim($perihal);
            $surat->jenis = $jenis;
            $surat->tanggal = $tanggal;
            $surat->status = 'open';
            $surat->path = str_replace('public/', '', $newPath);
            $surat->ext = $file_ext;
            $surat->save();

            $savedFiles[] = $surat;
        }

    return response()->json([
        'status' =>'ok',
        'data' => $savedFiles
    ]);
    }

    public function viewEdit(Request $request, $id){
        
        try{
            $surat = Surat::find($id);
            return view('pages.surat.surat-edit', [
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
    
    public function update($id, Request $request){
        $task = Surat::find($id);
        $task->status = 'close';
        
        $task->save();
 
        return response()->json([
            "message"=> "updated"
        ]);
    }
    
    public function viewDelete(Request $request, $id){
        
        try{
            $surat = Surat::find($id);
            return view('pages.surat.surat-delete', [
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

    
    public function destroy($id){
        $task = Surat::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }

    public function viewPdf($id)
    {
      $surat = Surat::find($id);
      return view('pages.surat.surat-view',[
        "data_surat" => $surat->path
      ]);
    }
    
}


