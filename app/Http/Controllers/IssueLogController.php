<?php

namespace App\Http\Controllers;

use App\Models\IssueLog;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables ;

class IssueLogController extends Controller
{
    public function index(Request $request){
        try{
            return view('pages.issue-log.report');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
  
    public function getIssueLog(Request $request)
    {
      
        if ($request->ajax()) {
            $data = IssueLog::select(['id',
                'number',
                'title', 
                'description', 
                'raised_on',
                'report_by',
                'priority',
                'status',
                'remark',
                DB::raw("DATE_FORMAT(closure_date, '%Y-%m-%d') as closure_date"), 
                'path',
                'ext',
        ]);
            
            return DataTables::of($data)
            ->addColumn('action', function($row) {
                $fileUrl = asset('storage/' . $row->path);
                $editBtn = '';
                if (Gate::allows('edit_issue_log')) {
                    $editBtn = '<a class="dropdown-item" href="'.route('issue-log-edit', $row->id).'">Edit</a>';
                }
            
                // Tombol Delete (Hanya tampil jika user memiliki izin 'delete_schedule')
                $deleteBtn = '';
                if (Gate::allows('delete_issue_log')) {
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
                       ' . $editBtn . '
                        ' . $deleteBtn . '      
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
          
            return view('pages.issue-log.report-tambah');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 


    public function saveUploads(Request $request)
    {
        $number = $request->input('number');
        $title = $request->input('title');
        $description = $request->input('description');
        $raised_on = $request->input('raised_on');
        $report_by = $request->input('report_by');
        $priority = $request->input('priority');
        $status = $request->input('status');
        $remark = $request->input('remark');
        $closure_date = $request->input('closure_date');
    

            // Simpan ke database atau proses lainnya
            $doc = new IssueLog();
            $doc->number = trim($number);
            $doc->title = trim($title);
            $doc->description = trim($description);
            $doc->raised_on = $raised_on;
            $doc->report_by = $report_by;
            $doc->priority = $priority;
            $doc->status = $status;
            $doc->remark = $remark;
            $doc->closure_date = $closure_date;
            $doc->save();


    return response()->json([
        'status' =>'ok',
        'data' => $doc
    ]);
    }

    public function viewEdit(Request $request, $id){
        
        try{
            $document = IssueLog::find($id);
        
          return view('pages.issue-log.report-edit',[
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
        $number = $request->input('number');
        $title = $request->input('title');
        $description = $request->input('description');
        $raised_on = $request->input('raised_on');
        $report_by = $request->input('report_by');
        $priority = $request->input('priority');
        $status = $request->input('status');
        $remark = $request->input('remark');
        $closure_date = $request->input('closure_date');
       
        $doc = IssueLog::find($id);

        $doc->number = trim($number);
        $doc->title = trim($title);
        $doc->description = trim($description);
        $doc->raised_on = $raised_on;
        $doc->report_by = $report_by;
        $doc->priority = $priority;
        $doc->status = $status;
        $doc->remark = $remark;
        $doc->closure_date = $closure_date;
          
        $doc->save();

    return response()->json([
        'status' =>'ok',
    ]);
    }

    
    public function viewDelete(Request $request, $id){
      
        try{
            $document = IssueLog::find($id);
            return view('pages.issue-log.report-delete', [
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
            $document = IssueLog::find($id);
            return view('pages.issue-log.report-share', [
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
            $document = IssueLog::find($id);
            return view('pages.issue-log.report-pdf', [
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
        $task = IssueLog::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }
    
    
}
