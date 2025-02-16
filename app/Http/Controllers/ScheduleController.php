<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Link;
use Illuminate\Support\Facades\DB;
use Throwable;
use Yajra\DataTables\Facades\DataTables ;


class ScheduleController extends Controller
{
    public function index(Request $request){
        try{
         
            return view('pages.master-schedule.master-schedule');
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }   
    public function getSchedule(Request $request)
    {
     
        if ($request->ajax()) {
            $data = Task::select(['id',
                'text', 
                'duration', 
                DB::raw("DATE_FORMAT(start_date, '%Y-%m-%d') as start_date"), 
                DB::raw("DATE_FORMAT(end_date, '%Y-%m-%d') as end_date"), 
                'progress',
                'parent'
            ]);
            
            return DataTables::of($data)
            ->addColumn('action', function($row) {
                $btn = ' <a data-bs-toggle="modal" data-bs-target="#modal" onClick="return addView(' . $row->id . ')" class="edit btn btn-success btn-sm">Insert</a>';
                $btn = $btn.' <a data-bs-toggle="modal" data-bs-target="#modal" onClick="return editView(' . $row->id . ')" class="edit btn btn-primary btn-sm">Edit</a>';
                $btn = $btn.' <a data-bs-toggle="modal" data-bs-target="#modal" onClick="return deleteView(' . $row->id . ')" class="edit btn btn-danger btn-sm">Delete</a>';
                
                return $btn;
                })
            ->addColumn('progress1', function($row) {
                return ($row->progress * 100) . '%';
            })
            ->addColumn('parent_desc', function($row) {
                return optional($row->r_parent)->text;
            })
                ->rawColumns(['action']) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }

    public function viewTambah(Request $request,$id){
        try{
       
            $task_list = Task::find($id);
       
            $parent = Task::get();
            return view('pages.master-schedule.master-schedule-tambah', [
                "data_parent" => $parent,
                "task_list" => $task_list
            ]);
        }catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    } 
    private function findTopParent($parentId)
    {
        if (!$parentId || $parentId == 0) {
            return 0;  // Jika tidak ada parent, parent_top sementara 0
        }

        $currentParent = Task::find($parentId);

        while ($currentParent && $currentParent->parent) {
            $currentParent = Task::find($currentParent->parent);  // Telusuri terus ke atas
        }

        return $currentParent ? $currentParent->id : $parentId;
    }

    public function store(Request $request)
    {
        $task = new Task();

        $task->text = $request->activity;
        $task->start_date = $request->start_date;
        $task->end_date = $request->end_date;
        $task->duration = $request->duration;
        $task->progress = $request->has("progress") ? $request->progress / 100 : 0;
        $task->parent = $request->parent;
        
        // Simpan task terlebih dahulu untuk mendapatkan ID-nya
        $task->save();

        // Update parent_top
        $task->parent_top = $request->parent ? $this->findTopParent($request->parent) : $task->id;
        $task->save();

        return response()->json([
            "message" => "ok",
            "tid" => $task->id
        ]);
    }

    public function viewEdit(Request $request, $id){
        
        try{
            $parent = Task::find($id);
            $parent_list = Task::get();
            return view('pages.master-schedule.master-schedule-edit', [
                "data_parent" => $parent,
                "parent_list" => $parent_list
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
        $task = Task::find($id);
 
        $task->text = $request->activity;
        $task->start_date = $request->start_date;
        $task->end_date = $request->end_date;
        $task->duration = $request->duration;
        $task->progress = $request->has("progress") ? $request->progress / 100 : 0;
        $task->parent = $request->parent;
 
 
        $task->save();
 
        return response()->json([
            "message"=> "updated"
        ]);
    }

    public function viewDelete(Request $request, $id){
        
        try{
            $parent = Task::find($id);
          
            return view('pages.master-schedule.master-schedule-delete', [
                "data_parent" => $parent
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
        $task = Task::find($id);
        $task->delete();
 
        return response()->json([
            "action"=> "deleted"
        ]);
    }

}
