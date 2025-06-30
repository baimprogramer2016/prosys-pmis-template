<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
use App\Models\MasterCustom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CustomController extends Controller
{
    public function index(Request $request)
    {
        try {
            return view('pages.custom.master-custom', [
                "data_type" => MasterCategory::where('category', '=', 'custom_menu')->get(),
                "data_parent" => MasterCustom::where('type', '=', 'parent')->get(),
                "data_template" => MasterCategory::where('category', '=', 'template')->get(),
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getMasterCustom(Request $request)
    {

        if ($request->ajax()) {
            $data = MasterCustom::select([
                'id',
                'name',
                'type',
                'icon',
                'tab',
                'tab_history',
                'parent',
                'template',
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"),
            ]);

            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    $rowJson = htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8');  // Konversi $row ke JSON string

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
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>               
                    </div>
                </div>';
                    return $btn;
                })
                ->addColumn('parent_desc', function ($row) {
                    return optional($row->r_parent)->name;
                })
                ->addColumn('icon_show', function ($row) {
                    if ($row->icon != '') {
                        $icon_show = '<i class="' . $row->icon . ' fa-2x text-secondary"></i>';
                    } else {
                        $icon_show = '';
                    }

                    return $icon_show;
                })
                ->addColumn('parent_type', function ($row) {
                    if ($row->type == 'parent') {
                        $badge = "<span class='badge badge-success'>" . $row->type . "</span>";
                    } else {
                        $badge = "<span class='badge badge-warning'>" . $row->type . "</span>";
                    }

                    return $badge;
                })
                ->rawColumns(['action', 'parent_desc', 'icon_show', 'parent_type']) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }


    public function save(Request $request)
    {
        // Simpan ke database atau proses lainnya

        MasterCustom::create(
            $request->all()
        );
        if ($request->tab) {
            $tableName = preg_replace('/[^a-zA-Z0-9_]/', '', "custom_" . $request->tab);
            $tableNameHistory = preg_replace('/[^a-zA-Z0-9_]/', '', "custom_" . $request->tab_history);

            if ($request->template == 'personnel_hr') {
                // Buat tabel baru secara dinamis
                DB::statement("
                        CREATE TABLE `$tableName` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `discipline` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `tanggal` DATETIME NULL DEFAULT NULL,
                            `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `created_at` DATETIME NULL DEFAULT NULL,
                            `updated_at` DATETIME NULL DEFAULT NULL,
                            `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            PRIMARY KEY (`id`)
                        )
                        ");
                DB::statement("
                        CREATE TABLE `$tableNameHistory` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `discipline` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `tanggal` DATETIME NULL DEFAULT NULL,
                            `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `created_at` DATETIME NULL DEFAULT NULL,
                            `updated_at` DATETIME NULL DEFAULT NULL,
                            `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            PRIMARY KEY (`id`)
                        )
                        ");
            } elseif ($request->template == 'drawings') {
                DB::statement("
                        CREATE TABLE `$tableName` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `created_at` DATETIME NULL DEFAULT NULL,
                            `updated_at` DATETIME NULL DEFAULT NULL,
                            `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            PRIMARY KEY (`id`)
                        )
                        ");
                DB::statement("
                        CREATE TABLE `$tableNameHistory` (
                            `id` INT(11) NOT NULL AUTO_INCREMENT,
                            `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            `created_at` DATETIME NULL DEFAULT NULL,
                            `updated_at` DATETIME NULL DEFAULT NULL,
                            `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                            PRIMARY KEY (`id`)
                        )
                        ");
            } elseif ($request->template == 'photographic') {
                DB::statement("
                      CREATE TABLE `$tableName` (
                          `id` INT(11) NOT NULL AUTO_INCREMENT,
                          `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          `tanggal` DATETIME NULL DEFAULT NULL,
                          `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          `created_at` DATETIME NULL DEFAULT NULL,
                          `updated_at` DATETIME NULL DEFAULT NULL,
                          `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          PRIMARY KEY (`id`)
                      )
                      ");
                DB::statement("
                      CREATE TABLE `$tableNameHistory` (
                          `id` INT(11) NOT NULL AUTO_INCREMENT,
                          `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          `tanggal` DATETIME NULL DEFAULT NULL,
                          `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          `created_at` DATETIME NULL DEFAULT NULL,
                          `updated_at` DATETIME NULL DEFAULT NULL,
                          `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                          PRIMARY KEY (`id`)
                      )
                      ");
            } elseif ($request->template == 'procurement_logistic') {
                // Buat tabel baru secara dinamis
                DB::statement("
                CREATE TABLE `$tableName` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `discipline` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `tanggal` DATETIME NULL DEFAULT NULL,
                    `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `created_at` DATETIME NULL DEFAULT NULL,
                    `updated_at` DATETIME NULL DEFAULT NULL,
                    `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    PRIMARY KEY (`id`)
                )
                ");
                DB::statement("
                CREATE TABLE `$tableNameHistory` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `discipline` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `tanggal` DATETIME NULL DEFAULT NULL,
                    `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `created_at` DATETIME NULL DEFAULT NULL,
                    `updated_at` DATETIME NULL DEFAULT NULL,
                    `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    PRIMARY KEY (`id`)
                )
                ");
            } elseif ($request->template == 'report') {
                // Buat tabel baru secara dinamis
                DB::statement("
                CREATE TABLE `$tableName` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `typeofreport` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `created_at` DATETIME NULL DEFAULT NULL,
                    `updated_at` DATETIME NULL DEFAULT NULL,
                    `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    PRIMARY KEY (`id`)
                )
                ");
                DB::statement("
                CREATE TABLE `$tableNameHistory` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `typeofreport` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `created_at` DATETIME NULL DEFAULT NULL,
                    `updated_at` DATETIME NULL DEFAULT NULL,
                    `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    PRIMARY KEY (`id`)
                )
                ");
            } elseif ($request->template == 'invoice_record') {
                // Buat tabel baru secara dinamis
                DB::statement("
                    CREATE TABLE `$tableName` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `no_invoice` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        `status` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        `invoice_date` DATETIME NULL DEFAULT NULL,
                        `created_at` DATETIME NULL DEFAULT NULL,
                        `updated_at` DATETIME NULL DEFAULT NULL,
                        `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        PRIMARY KEY (`id`)
                    )
                    ");
                DB::statement("
                    CREATE TABLE `$tableNameHistory` (
                        `id` INT(11) NOT NULL AUTO_INCREMENT,
                        `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        `no_invoice` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        `status` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        `invoice_date` DATETIME NULL DEFAULT NULL,
                        `created_at` DATETIME NULL DEFAULT NULL,
                        `updated_at` DATETIME NULL DEFAULT NULL,
                        `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                        PRIMARY KEY (`id`)
                    )
                    ");
            } elseif ($request->template == 'contract_management') {
                // Buat tabel baru secara dinamis
                DB::statement("
                CREATE TABLE `$tableName` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `no_contract` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `title` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `created_at` DATETIME NULL DEFAULT NULL,
                    `updated_at` DATETIME NULL DEFAULT NULL,
                      `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    PRIMARY KEY (`id`)
                )
                ");
                DB::statement("
                CREATE TABLE `$tableNameHistory` (
                    `id` INT(11) NOT NULL AUTO_INCREMENT,
                    `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `no_contract` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `title` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `created_at` DATETIME NULL DEFAULT NULL,
                    `updated_at` DATETIME NULL DEFAULT NULL,
                    `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                    PRIMARY KEY (`id`)
                )
                ");
            } elseif ($request->template == 'document_management') {
                DB::statement("
                  CREATE TABLE `$tableName` (
                      `id` INT(11) NOT NULL AUTO_INCREMENT,
                      `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `created_at` DATETIME NULL DEFAULT NULL,
                      `updated_at` DATETIME NULL DEFAULT NULL,
                      `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      PRIMARY KEY (`id`)
                  )
                  ");
                DB::statement("
                  CREATE TABLE `$tableNameHistory` (
                      `id` INT(11) NOT NULL AUTO_INCREMENT,
                      `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `created_at` DATETIME NULL DEFAULT NULL,
                      `updated_at` DATETIME NULL DEFAULT NULL,
                      `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      PRIMARY KEY (`id`)
                  )
                  ");
            } elseif ($request->template == 'quality_management') {
                DB::statement("
              CREATE TABLE `$tableName` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `created_at` DATETIME NULL DEFAULT NULL,
                  `updated_at` DATETIME NULL DEFAULT NULL,
                  `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  PRIMARY KEY (`id`)
              )
              ");
                DB::statement("
              CREATE TABLE `$tableNameHistory` (
                  `id` INT(11) NOT NULL AUTO_INCREMENT,
                  `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  `created_at` DATETIME NULL DEFAULT NULL,
                  `updated_at` DATETIME NULL DEFAULT NULL,
                  `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                  PRIMARY KEY (`id`)
              )
              ");
            } elseif ($request->template == 'piling') {
                DB::statement("
                  CREATE TABLE `$tableName` (
                      `id` INT(11) NOT NULL AUTO_INCREMENT,
                      `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `created_at` DATETIME NULL DEFAULT NULL,
                      `updated_at` DATETIME NULL DEFAULT NULL,
                      `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      PRIMARY KEY (`id`)
                  )
                  ");
                DB::statement("
                  CREATE TABLE `$tableNameHistory` (
                      `id` INT(11) NOT NULL AUTO_INCREMENT,
                      `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `created_at` DATETIME NULL DEFAULT NULL,
                      `updated_at` DATETIME NULL DEFAULT NULL,
                      `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      PRIMARY KEY (`id`)
                  )
                  ");
            } elseif ($request->template == 'rfi_in_quality_management') {
                DB::statement("
                  CREATE TABLE `$tableName` (
                      `id` INT(11) NOT NULL AUTO_INCREMENT,
                      `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `created_at` DATETIME NULL DEFAULT NULL,
                      `updated_at` DATETIME NULL DEFAULT NULL,
                      `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      PRIMARY KEY (`id`)
                  )
                  ");
                DB::statement("
                  CREATE TABLE `$tableNameHistory` (
                      `id` INT(11) NOT NULL AUTO_INCREMENT,
                      `document_number` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `custom_id` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `description` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `version` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `author` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `size` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `path` VARCHAR(100) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      `created_at` DATETIME NULL DEFAULT NULL,
                      `updated_at` DATETIME NULL DEFAULT NULL,
                      `ext` VARCHAR(20) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
                      PRIMARY KEY (`id`)
                  )
                  ");
            }
        }
        return response()->json([
            'status' => 'ok',

        ]);
    }


    public function viewDelete(Request $request, $id)
    {

        try {
            $document = MasterCustom::find($id);
            return view('pages.custom.master-custom-delete', [
                "document" => $document,
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function deleted(Request $request, $id)
    {

        $check = MasterCustom::where('parent', '=', $id)->first();
        if ($check) {
            return response()->json([
                "action" => "failed",
                "message" => "Delete the child first"
            ]);
        }

        $task = MasterCustom::find($id);
        $task->delete();

        if ($request->tab != "") {
            DB::statement("DROP TABLE " . $request->tab);
            DB::statement("DROP TABLE " . $request->tab_history);
        }

        return response()->json([
            "action" => "ok",
            "message" => "Deleted"
        ]);
    }


    public function getParent()
    {
        $data_parent =  MasterCustom::where('type', '=', 'parent')->get();
        return response()->json($data_parent);
    }

    public function getTemplate(Request $request)
    {

        // return $request->parent;
        if ($request->type == 'parent') {
            $data_template = MasterCategory::where('category', 'template')->select('description')->get();
        } else {
            $data_template = MasterCustom::where('id', $request->parent)->select('template as description')->distinct()->get();
        }

        return response()->json($data_template);
    }
    //


}
