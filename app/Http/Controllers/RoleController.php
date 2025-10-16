<?php

namespace App\Http\Controllers;

use App\Models\MasterCategory;
use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\ScheduleManagement;
use App\Models\SCurve;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use Illuminate\Support\FacadesDB;
use Monolog\Handler\RollbarHandler;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        try {

            return view('pages.role.role');
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getRole(Request $request)
    {

        if ($request->ajax()) {
            $data = Role::select([
                'id',
                'name',
                'guard_name'
            ]);

            return DataTables::of($data)
                ->addColumn('action', function ($row) {

                    if (!in_array($row->name, ['superadmin', 'reviewer', 'checker', 'approver'])) {
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
                    <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewEdit(' . $row->id . ')" class="dropdown-item cursor-pointer">Edit</a>
                    <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewDelete(' . $row->id . ')" class="dropdown-item cursor-pointer">Delete</a>
                                            
                    </div>
                </div>';
                        return $btn;
                    } else {
                        $btn = "";
                    }
                })
                ->addColumn('permission', function ($row) {
                    $btn = '
                <a
                    class="btn btn-icon btn-clean me-0"
                   
                    
                    data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewPermission(' . $row->id . ')"
                >
                    <i class="fas fa-cog"></i>
                </a>
           ';
                    return $btn;
                })
                ->rawColumns(['action', 'permission']) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }

    public function viewAdd(Request $request)
    {

        try {
            return view('pages.role.role-tambah');
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function save(Request $request)
    {
        Role::updateOrCreate(
            ['name' => $request->input('role')], // Kriteria pencarian
            ['guard_name' => 'web'] // Data yang diperbarui jika role sudah ada
        );

        return response()->json([
            'status' => 'ok',
        ]);
    }

    public function viewEdit(Request $request, $id)
    {

        try {
            $document = Role::find($id);

            return view('pages.role.role-edit', [
                "document" => $document
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function update(Request $request, $id)
    {
        $doc = Role::find($id);
        $doc->name = $request->role;
        $doc->save();

        return response()->json([
            'status' => 'ok',
        ]);
    }


    public function viewDelete(Request $request, $id)
    {

        try {
            $document = Role::find($id);
            return view('pages.role.role-delete', [
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



    public function deleted($id)
    {

        //role
        $checkPermission = RolePermission::where('role_id', $id)->first();

        if ($checkPermission) {
            return response()->json([
                "status" => "permission",
                "message" => "Failed, Already in Permission"
            ]);
        }

        $task = Role::find($id);
        $task->delete();

        return response()->json([
            "status" => "ok",
            "message" => "Data successfully Deleted"
        ]);
    }


    public function viewPermission(Request $request, $id)
    {

        $data_permission = Permission::get();
        $data_permission_role = RolePermission::where('role_id', $id)->get();

        foreach ($data_permission as $item_permission) {
            if ($data_permission_role->contains('permission_id', $item_permission->id)) {
                $item_permission->check = 1;
            } else {
                $item_permission->check = 0;
            }
        }
        try {
            $role = Role::find($id);
            return view('pages.role.role-permission', [
                "data_role" => $role,
                "data_permission" => $data_permission,
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePermission(Request $request)
    {

        $check_role_permission = RolePermission::where('role_id', $request->role_id)
            ->where('permission_id', $request->permission_id)->first();
        if ($check_role_permission) {
            RolePermission::where('role_id', $request->role_id)
                ->where('permission_id', $request->permission_id)->delete();
        } else {
            $rolePermission = new RolePermission();
            $rolePermission->permission_id = $request->permission_id;
            $rolePermission->role_id = $request->role_id;
            $rolePermission->save();
        }
        app()['cache']->forget('spatie.permission.cache');
    }
}
