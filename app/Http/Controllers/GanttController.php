<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Link;
use Throwable;

class GanttController extends Controller
{
    
    //menampilkan view
    public function view(){
        try{

            return view('pages.master-schedule.gantt-chart');
        }catch (Throwable $e) {
       
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    //menampilkan datanya dengan route api
    public function data(Request $request)
    {
        $tasks = Task::query();
    
        if ($request->has('search')) {
            $searchTerm = $request->search;
            
            // Cari task yang sesuai dengan pencarian
            $matchedTasks = Task::where('text', 'like', "%$searchTerm%")->pluck('parent_top')->unique();
    
            // Ambil semua task yang punya parent_top dari hasil pencarian
            $tasks = $tasks->whereIn('parent_top', $matchedTasks);
        }
    
        $tasks = $tasks->get();
        $links = Link::all();
    
        return response()->json([
            "data" => $tasks,
            "links" => $links
        ]);
    }
    
    
}
