<?php

use App\Http\Controllers\ConstructionDocumentController;
use App\Http\Controllers\CorSuratKeluarController;
use App\Http\Controllers\CorSuratMasukController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FileManagerController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\GanttController;
use App\Http\Controllers\SuratController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentEngineeringController;
use App\Http\Controllers\FieldInstructionController;
use App\Http\Controllers\MomController;
use App\Http\Controllers\ReportDailyController;
use App\Http\Controllers\ReportMonthlyController;
use App\Http\Controllers\ReportWeeklyController;
use App\Http\Controllers\ScheduleManagementController;
use App\Http\Controllers\SCurveController;
use App\Http\Controllers\SopController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get("/insert", [LoginController::class,'insertAdmin']);


Route::get('/', [LoginController::class,'index']);
Route::get('/login', [LoginController::class,'index']);
Route::post('/login', [LoginController::class, 'prosesLogin'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class,'index'])->name('dashboard');


    Route::get('/master-schedule', [ScheduleController::class, 'index'])->name('master-schedule');
    Route::get('/master-schedule-tambah/{id}', [ScheduleController::class, 'viewTambah'])->name('master-schedule-tambah');
    Route::POST('/master-schedule-store', [ScheduleController::class, 'store'])->name('master-schedule-store');
    Route::get('/master-schedule-edit/{id}', [ScheduleController::class, 'viewEdit'])->name('master-schedule-edit');
    Route::POST('/master-schedule-update/{id}', [ScheduleController::class, 'update'])->name('master-schedule-update');
    Route::get('/master-schedule-delete/{id}', [ScheduleController::class, 'viewDelete'])->name('master-schedule-delete');
    Route::POST('/master-schedule-destroy/{id}', [ScheduleController::class, 'destroy'])->name('master-schedule-destroy');
    Route::get('/get-schedule', [ScheduleController::class, 'getSchedule'])->name('get-schedule');

    Route::get('/gantt-chart', [GanttController::class,'view'])->name('gantt-chart');

    Route::get('/surat', [SuratController::class, 'index'])->name('surat');
    Route::get('/get-surat', [SuratController::class, 'getSurat'])->name('get-surat');
    Route::get('/surat-tambah', [SuratController::class, 'viewTambah'])->name('surat-tambah');
    Route::post('/surat-upload-temp', [SuratController::class, 'uploadTemp'])->name('surat-upload-temp');
    Route::post('/surat-save-uploads', [SuratController::class, 'saveUploads'])->name('surat-save-uploads');
    Route::get('/surat-edit/{id}', [SuratController::class, 'viewEdit'])->name('surat-edit');
    Route::POST('/surat-update/{id}', [SuratController::class, 'update'])->name('surat-update');
    Route::get('/surat-delete/{id}', [SuratController::class, 'viewDelete'])->name('surat-delete');
    Route::POST('/surat-destroy/{id}', [SuratController::class, 'destroy'])->name('surat-destroy');
    Route::get('/surat-view-pdf/{id}', [SuratController::class, 'viewPdf'])->name('surat-view-pdf');

    Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

    Route::get("/file-manager", [FileManagerController::class,'index'])->name('file-manager');

    Route::get("/document", [DocumentController::class,'index'])->name('document');


    Route::get('/document-engineer', [DocumentEngineeringController::class, 'index'])->name('document-engineer');
    Route::get('/get-document-engineer/{field}/{status}', [DocumentEngineeringController::class, 'getDocumentEngineer'])->name('get-document-engineer');
    Route::get('/document-engineer-tambah', [DocumentEngineeringController::class, 'viewTambah'])->name('document-engineer-tambah');
    Route::post('/document-engineer-upload-temp', [DocumentEngineeringController::class, 'uploadTemp'])->name('document-engineer-upload-temp');
    Route::post('/document-engineer-save-uploads', [DocumentEngineeringController::class, 'saveUploads'])->name('document-engineer-save-uploads');
    Route::get('/document-engineer-edit/{id}', [DocumentEngineeringController::class, 'viewEdit'])->name('document-engineer-edit');
    Route::post('/document-engineer-update-uploads/{id}', [DocumentEngineeringController::class, 'updateUploads'])->name('document-engineer-update-uploads');

    Route::get('/document-engineer-history/{id}', [DocumentEngineeringController::class, 'history'])->name('document-engineer-history');
    Route::get('/document-engineer-share/{id}', [DocumentEngineeringController::class, 'share'])->name('document-engineer-share');
    Route::get('/document-engineer-pdf/{id}', [DocumentEngineeringController::class, 'pdf'])->name('document-engineer-pdf');
    Route::get('/document-engineer-delete/{id}', [DocumentEngineeringController::class, 'viewDelete'])->name('document-engineer-delete');
    Route::post('/document-engineer-deleted/{id}', [DocumentEngineeringController::class, 'deleted'])->name('document-engineer-deleted');
  
    Route::get('/document-engineer-check', [DocumentEngineeringController::class, 'check'])->name('document-engineer-check');
    Route::get('/document-engineer-check-modal/{id}', [DocumentEngineeringController::class, 'viewCheckModal'])->name('document-engineer-check-modal');
    Route::post('/document-engineer-check-update/{id}', [DocumentEngineeringController::class, 'updateCheck'])->name('document-engineer-check-update');
 
    Route::get('/document-engineer-review', [DocumentEngineeringController::class, 'review'])->name('document-engineer-review');
    Route::get('/document-engineer-review-modal/{id}', [DocumentEngineeringController::class, 'viewReviewModal'])->name('document-engineer-review-modal');
    Route::post('/document-engineer-review-update/{id}', [DocumentEngineeringController::class, 'updateReview'])->name('document-engineer-review-update');

    Route::get('/document-engineer-approve', [DocumentEngineeringController::class, 'approve'])->name('document-engineer-approve');
    Route::get('/document-engineer-approve-modal/{id}', [DocumentEngineeringController::class, 'viewApproveModal'])->name('document-engineer-approve-modal');
    Route::post('/document-engineer-approve-update/{id}', [DocumentEngineeringController::class, 'updateApprove'])->name('document-engineer-approve-update');

    
    Route::get('/document-engineer-basic-design', [DocumentEngineeringController::class, 'basicDesign'])->name('document-engineer-basic-design');
    Route::get('/document-engineer-detail-engineering-design', [DocumentEngineeringController::class, 'ded'])->name('document-engineer-detail-engineering-design');
    Route::get('/document-engineer-master-deliverables-register', [DocumentEngineeringController::class, 'mdr'])->name('document-engineer-master-deliverables-register');
    
    Route::get('/schedule-management', [ScheduleManagementController::class, 'index'])->name('schedule-management');
    Route::get('/get-schedule-management', [ScheduleManagementController::class, 'getScheduleManagement'])->name('get-schedule-management');
    Route::get('/schedule-management-tambah', [ScheduleManagementController::class, 'tambah'])->name('schedule-management-tambah');
    Route::post('/schedule-management-upload-temp', [ScheduleManagementController::class, 'uploadTemp'])->name('schedule-management-upload-temp');
    Route::post('/schedule-management-save-uploads', [ScheduleManagementController::class, 'saveUploads'])->name('schedule-management-save-uploads');
   
    Route::get('/schedule-management-edit/{id}', [ScheduleManagementController::class, 'viewEdit'])->name('schedule-management-edit');
    Route::post('/schedule-management-update/{id}', [ScheduleManagementController::class, 'update'])->name('schedule-management-update');
    Route::get('/schedule-management-share/{id}', [ScheduleManagementController::class, 'share'])->name('schedule-management-share');
    Route::get('/schedule-management-pdf/{id}', [ScheduleManagementController::class, 'pdf'])->name('schedule-management-pdf');
    Route::get('/schedule-management-delete/{id}', [ScheduleManagementController::class, 'viewDelete'])->name('schedule-management-delete');
    Route::post('/schedule-management-deleted/{id}', [ScheduleManagementController::class, 'deleted'])->name('schedule-management-deleted');
  
    Route::get('/s-curve', [SCurveController::class, 'index'])->name('s-curve');
    Route::get('/get-s-curve', [SCurveController::class, 'getSCurve'])->name('get-s-curve');
    Route::post('/s-curve-save', [SCurveController::class, 'save'])->name('s-curve-save');
    Route::get('/s-curve-delete/{id}', [SCurveController::class, 'viewDelete'])->name('s-curve-delete');
    Route::post('/s-curve-deleted/{id}', [SCurveController::class, 'deleted'])->name('s-curve-deleted');
  
    Route::get('/s-curve-chart', [SCurveController::class, 'sCurveChart'])->name('s-curve-chart');
    Route::get('/s-curve-chart-data', [SCurveController::class, 'dataScurve'])->name('s-curve-chart-data');
    Route::get('/s-curve-bar', [SCurveController::class, 'sCurveBar'])->name('s-curve-bar');
    Route::get('/s-curve-bar-data', [SCurveController::class, 'dataScurveBar'])->name('s-curve-bar-data');

    Route::get("/users", [UserController::class,'index'])->name('users');
    
    Route::get('/report-daily', [ReportDailyController::class, 'index'])->name('report-daily');
    Route::get('/get-report-daily', [ReportDailyController::class, 'getReport'])->name('get-report-daily');
    Route::get('/report-daily-tambah', [ReportDailyController::class, 'tambah'])->name('report-daily-tambah');
    Route::post('/report-daily-upload-temp', [ReportDailyController::class, 'uploadTemp'])->name('report-daily-upload-temp');
    Route::post('/report-daily-save-uploads', [ReportDailyController::class, 'saveUploads'])->name('report-daily-save-uploads');
   
    Route::get('/report-daily-edit/{id}', [ReportDailyController::class, 'viewEdit'])->name('report-daily-edit');
    Route::post('/report-daily-update/{id}', [ReportDailyController::class, 'update'])->name('report-daily-update');
    Route::get('/report-daily-share/{id}', [ReportDailyController::class, 'share'])->name('report-daily-share');
    Route::get('/report-daily-pdf/{id}', [ReportDailyController::class, 'pdf'])->name('report-daily-pdf');
    Route::get('/report-daily-delete/{id}', [ReportDailyController::class, 'viewDelete'])->name('report-daily-delete');
    Route::post('/report-daily-deleted/{id}', [ReportDailyController::class, 'deleted'])->name('report-daily-deleted');
  
    Route::get('/report-weekly', [ReportWeeklyController::class, 'index'])->name('report-weekly');
    Route::get('/get-report-weekly', [ReportWeeklyController::class, 'getReport'])->name('get-report-weekly');
    Route::get('/report-weekly-tambah', [ReportWeeklyController::class, 'tambah'])->name('report-weekly-tambah');
    Route::post('/report-weekly-upload-temp', [ReportWeeklyController::class, 'uploadTemp'])->name('report-weekly-upload-temp');
    Route::post('/report-weekly-save-uploads', [ReportWeeklyController::class, 'saveUploads'])->name('report-weekly-save-uploads');
   
    Route::get('/report-weekly-edit/{id}', [ReportWeeklyController::class, 'viewEdit'])->name('report-weekly-edit');
    Route::post('/report-weekly-update/{id}', [ReportWeeklyController::class, 'update'])->name('report-weekly-update');
    Route::get('/report-weekly-share/{id}', [ReportWeeklyController::class, 'share'])->name('report-weekly-share');
    Route::get('/report-weekly-pdf/{id}', [ReportWeeklyController::class, 'pdf'])->name('report-weekly-pdf');
    Route::get('/report-weekly-delete/{id}', [ReportWeeklyController::class, 'viewDelete'])->name('report-weekly-delete');
    Route::post('/report-weekly-deleted/{id}', [ReportWeeklyController::class, 'deleted'])->name('report-weekly-deleted');
  
    Route::get('/report-monthly', [ReportMonthlyController::class, 'index'])->name('report-monthly');
    Route::get('/get-report-monthly', [ReportMonthlyController::class, 'getReport'])->name('get-report-monthly');
    Route::get('/report-monthly-tambah', [ReportMonthlyController::class, 'tambah'])->name('report-monthly-tambah');
    Route::post('/report-monthly-upload-temp', [ReportMonthlyController::class, 'uploadTemp'])->name('report-monthly-upload-temp');
    Route::post('/report-monthly-save-uploads', [ReportMonthlyController::class, 'saveUploads'])->name('report-monthly-save-uploads');
   
    Route::get('/report-monthly-edit/{id}', [ReportMonthlyController::class, 'viewEdit'])->name('report-monthly-edit');
    Route::post('/report-monthly-update/{id}', [ReportMonthlyController::class, 'update'])->name('report-monthly-update');
    Route::get('/report-monthly-share/{id}', [ReportMonthlyController::class, 'share'])->name('report-monthly-share');
    Route::get('/report-monthly-pdf/{id}', [ReportMonthlyController::class, 'pdf'])->name('report-monthly-pdf');
    Route::get('/report-monthly-delete/{id}', [ReportMonthlyController::class, 'viewDelete'])->name('report-monthly-delete');
    Route::post('/report-monthly-deleted/{id}', [ReportMonthlyController::class, 'deleted'])->name('report-monthly-deleted');
  
    Route::get('/mom', [MomController::class, 'index'])->name('mom');
    Route::get('/get-mom', [MomController::class, 'getReport'])->name('get-mom');
    Route::get('/mom-tambah', [MomController::class, 'tambah'])->name('mom-tambah');
    Route::post('/mom-upload-temp', [MomController::class, 'uploadTemp'])->name('mom-upload-temp');
    Route::post('/mom-save-uploads', [MomController::class, 'saveUploads'])->name('mom-save-uploads');
   
    Route::get('/mom-edit/{id}', [MomController::class, 'viewEdit'])->name('mom-edit');
    Route::post('/mom-update/{id}', [MomController::class, 'update'])->name('mom-update');
    Route::get('/mom-share/{id}', [MomController::class, 'share'])->name('mom-share');
    Route::get('/mom-pdf/{id}', [MomController::class, 'pdf'])->name('mom-pdf');
    Route::get('/mom-delete/{id}', [MomController::class, 'viewDelete'])->name('mom-delete');
    Route::post('/mom-deleted/{id}', [MomController::class, 'deleted'])->name('mom-deleted');
  
    Route::get('/sop', [SopController::class, 'index'])->name('sop');
    Route::get('/get-sop', [SopController::class, 'getReport'])->name('get-sop');
    Route::get('/sop-tambah', [SopController::class, 'tambah'])->name('sop-tambah');
    Route::post('/sop-upload-temp', [SopController::class, 'uploadTemp'])->name('sop-upload-temp');
    Route::post('/sop-save-uploads', [SopController::class, 'saveUploads'])->name('sop-save-uploads');
   
    Route::get('/sop-edit/{id}', [SopController::class, 'viewEdit'])->name('sop-edit');
    Route::post('/sop-update/{id}', [SopController::class, 'update'])->name('sop-update');
    Route::get('/sop-share/{id}', [SopController::class, 'share'])->name('sop-share');
    Route::get('/sop-pdf/{id}', [SopController::class, 'pdf'])->name('sop-pdf');
    Route::get('/sop-delete/{id}', [SopController::class, 'viewDelete'])->name('sop-delete');
    Route::post('/sop-deleted/{id}', [SopController::class, 'deleted'])->name('sop-deleted');

    
    Route::get('/surat-masuk', [CorSuratMasukController::class, 'index'])->name('surat-masuk');
    Route::get('/get-surat-masuk', [CorSuratMasukController::class, 'getSuratMasuk'])->name('get-surat-masuk');
    Route::get('/surat-masuk-tambah', [CorSuratMasukController::class, 'tambah'])->name('surat-masuk-tambah');
    Route::post('/surat-masuk-upload-temp', [CorSuratMasukController::class, 'uploadTemp'])->name('surat-masuk-upload-temp');
    Route::post('/surat-masuk-save-uploads', [CorSuratMasukController::class, 'saveUploads'])->name('surat-masuk-save-uploads');
   
    Route::get('/surat-masuk-edit/{id}', [CorSuratMasukController::class, 'viewEdit'])->name('surat-masuk-edit');
    Route::post('/surat-masuk-update/{id}', [CorSuratMasukController::class, 'update'])->name('surat-masuk-update');
    Route::get('/surat-masuk-share/{id}', [CorSuratMasukController::class, 'share'])->name('surat-masuk-share');
    Route::get('/surat-masuk-pdf/{id}', [CorSuratMasukController::class, 'pdf'])->name('surat-masuk-pdf');
    Route::get('/surat-masuk-delete/{id}', [CorSuratMasukController::class, 'viewDelete'])->name('surat-masuk-delete');
    Route::post('/surat-masuk-deleted/{id}', [CorSuratMasukController::class, 'deleted'])->name('surat-masuk-deleted');
    
    Route::get('/surat-keluar', [CorSuratKeluarController::class, 'index'])->name('surat-keluar');
    Route::get('/get-surat-keluar', [CorSuratKeluarController::class, 'getSuratKeluar'])->name('get-surat-keluar');
    Route::get('/surat-keluar-tambah', [CorSuratKeluarController::class, 'tambah'])->name('surat-keluar-tambah');
    Route::post('/surat-keluar-upload-temp', [CorSuratKeluarController::class, 'uploadTemp'])->name('surat-keluar-upload-temp');
    Route::post('/surat-keluar-save-uploads', [CorSuratKeluarController::class, 'saveUploads'])->name('surat-keluar-save-uploads');
   
    Route::get('/surat-keluar-edit/{id}', [CorSuratKeluarController::class, 'viewEdit'])->name('surat-keluar-edit');
    Route::post('/surat-keluar-update/{id}', [CorSuratKeluarController::class, 'update'])->name('surat-keluar-update');
    Route::get('/surat-keluar-share/{id}', [CorSuratKeluarController::class, 'share'])->name('surat-keluar-share');
    Route::get('/surat-keluar-pdf/{id}', [CorSuratKeluarController::class, 'pdf'])->name('surat-keluar-pdf');
    Route::get('/surat-keluar-delete/{id}', [CorSuratKeluarController::class, 'viewDelete'])->name('surat-keluar-delete');
    Route::post('/surat-keluar-deleted/{id}', [CorSuratKeluarController::class, 'deleted'])->name('surat-keluar-deleted');


    Route::get('/construction-document', [ConstructionDocumentController::class, 'cdr'])->name('construction-document');
    Route::get('/get-construction-document/{field}/{status}', [ConstructionDocumentController::class, 'getConstructionDocument'])->name('get-construction-document');
    Route::get('/construction-document-tambah', [ConstructionDocumentController::class, 'viewTambah'])->name('construction-document-tambah');
    Route::post('/construction-document-upload-temp', [ConstructionDocumentController::class, 'uploadTemp'])->name('construction-document-upload-temp');
    Route::post('/construction-document-save-uploads', [ConstructionDocumentController::class, 'saveUploads'])->name('construction-document-save-uploads');
    Route::get('/construction-document-edit/{id}', [ConstructionDocumentController::class, 'viewEdit'])->name('construction-document-edit');
    Route::post('/construction-document-update-uploads/{id}', [ConstructionDocumentController::class, 'updateUploads'])->name('construction-document-update-uploads');

    Route::get('/construction-document-history/{id}', [ConstructionDocumentController::class, 'history'])->name('construction-document-history');
    Route::get('/construction-document-share/{id}', [ConstructionDocumentController::class, 'share'])->name('construction-document-share');
    Route::get('/construction-document-pdf/{id}', [ConstructionDocumentController::class, 'pdf'])->name('construction-document-pdf');
    Route::get('/construction-document-delete/{id}', [ConstructionDocumentController::class, 'viewDelete'])->name('construction-document-delete');
    Route::post('/construction-document-deleted/{id}', [ConstructionDocumentController::class, 'deleted'])->name('construction-document-deleted');
  
    Route::get('/construction-document-check', [ConstructionDocumentController::class, 'check'])->name('construction-document-check');
    Route::get('/construction-document-check-modal/{id}', [ConstructionDocumentController::class, 'viewCheckModal'])->name('construction-document-check-modal');
    Route::post('/construction-document-check-update/{id}', [ConstructionDocumentController::class, 'updateCheck'])->name('construction-document-check-update');
 
    Route::get('/construction-document-review', [ConstructionDocumentController::class, 'review'])->name('construction-document-review');
    Route::get('/construction-document-review-modal/{id}', [ConstructionDocumentController::class, 'viewReviewModal'])->name('construction-document-review-modal');
    Route::post('/construction-document-review-update/{id}', [ConstructionDocumentController::class, 'updateReview'])->name('construction-document-review-update');

    Route::get('/construction-document-approve', [ConstructionDocumentController::class, 'approve'])->name('construction-document-approve');
    Route::get('/construction-document-approve-modal/{id}', [ConstructionDocumentController::class, 'viewApproveModal'])->name('construction-document-approve-modal');
    Route::post('/construction-document-approve-update/{id}', [ConstructionDocumentController::class, 'updateApprove'])->name('construction-document-approve-update');

    
    Route::get('/field-instruction', [FieldInstructionController::class, 'cdr'])->name('field-instruction');
    Route::get('/get-field-instruction/{field}/{status}', [FieldInstructionController::class, 'getFieldInstruction'])->name('get-field-instruction');
    Route::get('/field-instruction-tambah', [FieldInstructionController::class, 'viewTambah'])->name('field-instruction-tambah');
    Route::post('/field-instruction-upload-temp', [FieldInstructionController::class, 'uploadTemp'])->name('field-instruction-upload-temp');
    Route::post('/field-instruction-save-uploads', [FieldInstructionController::class, 'saveUploads'])->name('field-instruction-save-uploads');
    Route::get('/field-instruction-edit/{id}', [FieldInstructionController::class, 'viewEdit'])->name('field-instruction-edit');
    Route::post('/field-instruction-update-uploads/{id}', [FieldInstructionController::class, 'updateUploads'])->name('field-instruction-update-uploads');

    Route::get('/field-instruction-history/{id}', [FieldInstructionController::class, 'history'])->name('field-instruction-history');
    Route::get('/field-instruction-share/{id}', [FieldInstructionController::class, 'share'])->name('field-instruction-share');
    Route::get('/field-instruction-pdf/{id}', [FieldInstructionController::class, 'pdf'])->name('field-instruction-pdf');
    Route::get('/field-instruction-delete/{id}', [FieldInstructionController::class, 'viewDelete'])->name('field-instruction-delete');
    Route::post('/field-instruction-deleted/{id}', [FieldInstructionController::class, 'deleted'])->name('field-instruction-deleted');
  
    Route::get('/field-instruction-check', [FieldInstructionController::class, 'check'])->name('field-instruction-check');
    Route::get('/field-instruction-check-modal/{id}', [FieldInstructionController::class, 'viewCheckModal'])->name('field-instruction-check-modal');
    Route::post('/field-instruction-check-update/{id}', [FieldInstructionController::class, 'updateCheck'])->name('field-instruction-check-update');
 
    Route::get('/field-instruction-review', [FieldInstructionController::class, 'review'])->name('field-instruction-review');
    Route::get('/field-instruction-review-modal/{id}', [FieldInstructionController::class, 'viewReviewModal'])->name('field-instruction-review-modal');
    Route::post('/field-instruction-review-update/{id}', [FieldInstructionController::class, 'updateReview'])->name('field-instruction-review-update');

    Route::get('/field-instruction-approve', [FieldInstructionController::class, 'approve'])->name('field-instruction-approve');
    Route::get('/field-instruction-approve-modal/{id}', [FieldInstructionController::class, 'viewApproveModal'])->name('field-instruction-approve-modal');
    Route::post('/field-instruction-approve-update/{id}', [FieldInstructionController::class, 'updateApprove'])->name('field-instruction-approve-update');

    
    
    
    
    
  
});