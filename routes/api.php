<?php

use App\Http\Controllers\CustomController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\GanttController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/data', [GanttController::class,'data'])->name('api-gantt-data');
Route::post("/dashboard-pie-surat",[DashboardController::class,'dashboardPieSurat'])->name('dashboard-pie-surat');
Route::post("/send-mail", [EmailController::class,'index'])->name('send-mail');
Route::get("/get-parent", [CustomController::class,'getParent'])->name('get-parent');
Route::get("/dashboard-drawings", [DashboardController::class,'dashboardDrawings'])->name('dashboard-drawings');
