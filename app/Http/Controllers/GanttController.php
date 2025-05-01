<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\Link;
use App\Models\SCurve;
use Illuminate\Support\Facades\DB;
use Throwable;

class GanttController extends Controller
{

    //menampilkan view
    public function view()
    {
        try {

            return view('pages.master-schedule.gantt-chart');
        } catch (Throwable $e) {

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

    public function newDashboarGantt(Request $request)
    {
        $data = SCurve::get();

        $category = SCurve::select('category as text', DB::raw('min(tanggal) as start_date, max(tanggal) as end_date'))
            ->groupBy('category')
            ->get();

        foreach ($category as $key => $value) {
            $result = $data->where('category', $value->text)
                ->where('description', 'Actual')
                ->sortByDesc('tanggal')
                ->first();
            $value->progress = (float) number_format((float) $result->percent, 2, '.', '');
            // hasil: float(2.72)

            $value->parent = 0;

            $value->duration = \Carbon\Carbon::parse($value->start_date)
                ->diffInDays(\Carbon\Carbon::parse($value->end_date)) + 1;
        }

        Task::truncate();
        Task::insert($category->toArray());

        return response()->json([
            "data" => Task::get()->map(function ($task) {
                return [
                    "id" => $task->id,
                    "text" => $task->text,
                    "start_date" => $task->start_date,
                    "duration" => $task->duration,
                    "progress" => round($task->progress / 100, 4), // untuk bar (0–1)
                    "raw_progress" => number_format($task->progress, 2, '.', ''), // untuk label
                    "parent" => 0
                ];
            })
        ]);
    }
}
