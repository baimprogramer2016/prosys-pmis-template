<?php

namespace App\Http\Controllers;

use App\Models\Cv;
use App\Models\CvGroup;
use App\Models\Mom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class CvController extends Controller
{
    public function index(Request $request)
    {
        try {
            return view('pages.cv.cv');
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getReport(Request $request)
    {

        if ($request->ajax()) {
            $data = Cv::select([
                'id',
                'name',
                'academy',
                'degree',
                'major',
                'position',
                'photo',
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"),
                'path',
                'ext',
            ]);

            return DataTables::of($data)
                ->editColumn('photo', function ($row) {
                    if ($row->photo) {
                        $fileUrl = asset('storage/' . $row->photo);
                        return '<img src="' . $fileUrl . '" alt="photo" height="100" class="rounded">';
                    }
                    return '-';
                })
                ->addColumn('action', function ($row) {
                    $fileUrl = asset('storage/' . $row->path);

                    $addDropdown = "";
                    if (in_array($row->ext, ['pdf', 'jpg', 'png', 'jpeg', 'docx', 'doc', 'xls', 'xlsx', 'ppt', 'pptx'])) {
                        $addDropdown = ' <a href="" data-bs-toggle="modal" data-bs-target="#modal-pdf" onClick="return viewPdf(' . $row->id . ')" class="dropdown-item cursor-pointer">View</a>';
                    }
                    $editBtn = '';
                    if (Gate::allows('edit_cv')) {
                        $editBtn = '<a class="dropdown-item" href="' . route('cv-edit', $row->id) . '">Edit</a>';
                    }

                    // Tombol Delete (Hanya tampil jika user memiliki izin 'delete_schedule')
                    $deleteBtn = '';
                    if (Gate::allows('delete_cv')) {
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
                        <a class="dropdown-item" href="' . $fileUrl . '" download>Download</a>
                         ' . $editBtn . '
                        ' . $deleteBtn . '
                        <a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewShare(' . $row->id . ')" class="dropdown-item cursor-pointer">Share</a>
                        ' . $addDropdown . '                        
                    </div>
                </div>';
                    return $btn;
                })

                ->rawColumns(['photo', 'action']) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }

    public function tambah(Request $request)
    {
        try {
            return view('pages.cv.cv-tambah');
        } catch (Throwable $e) {
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
            'path' => $path, //lokasi dan nama file disimpan ditemp
            'name' => $file->getClientOriginalName(),
        ]);
    }

    public function saveUploads(Request $request)
    {
        $uploadedFiles = json_decode($request->input('uploaded_files'), true);
        $name = $request->input('name');
        $academy = $request->input('academy');
        $major = $request->input('major');
        $degree = $request->input('degree');
        $position = $request->input('position');
        $imageInput = $request->file('imageInput'); // <- ambil file gambar

        if ($imageInput) {
            $path = $imageInput->store('public/cv_images'); // simpan di storage/app/public/cv_images
            $imagePath = str_replace('public/', '', $path);
        }

        $savedFiles = [];
        foreach ($uploadedFiles as $file) {
            $fileName = $file['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

            $newPath = str_replace('temp', 'public/cv', $file['path']);
            Storage::move($file['path'], $newPath);

            $doc = new Cv();
            $doc->name = trim($name);
            $doc->academy = trim($academy);
            $doc->major = trim($major);
            $doc->degree = trim($degree);
            $doc->position = trim($position);
            $doc->path = str_replace('public/', '', $newPath);
            $doc->ext = $file_ext;
            $doc->author = Auth::user()->name;
            $doc->photo = $imagePath ?? null; // simpan gambar opsional
            $doc->save();

            $savedFiles[] = $doc;
        }

        return response()->json([
            'status' => 'ok',
            'data' => $savedFiles
        ]);
    }


    public function viewEdit(Request $request, $id)
    {

        try {
            $document = Cv::find($id);

            return view('pages.cv.cv-edit', [
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
        // Decode uploaded_files karena dikirim via JSON
        $uploadedFiles = json_decode($request->input('uploaded_files'), true);

        $name = $request->input('name');
        $academy = $request->input('academy');
        $major = $request->input('major');
        $degree = $request->input('degree');
        $position = $request->input('position');
        $imageInput = $request->file('imageInput'); // ambil file image kalau ada

        $doc = Cv::findOrFail($id);

        // default: pakai data lama
        $path = $doc->path;
        $imagePath = $doc->photo;
        $file_ext = $doc->ext;

        // === kalau ada file baru dari Dropzone ===
        if (!empty($uploadedFiles) && is_array($uploadedFiles)) {
            $fileName = $uploadedFiles[0]['fileName'];
            $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

            $newPath = str_replace('temp', 'public/cv', $uploadedFiles[0]['path']);
            Storage::move($uploadedFiles[0]['path'], $newPath);
            $path = str_replace('public/', '', $newPath);
        }

        // === kalau ada file baru dari imageInput ===
        if ($imageInput) {
            $pathPhoto = $imageInput->store('public/cv_images');
            $imagePath = str_replace('public/', '', $pathPhoto);
        }

        // === update ke database ===
        $doc->name = trim($name);
        $doc->academy = trim($academy);
        $doc->major = trim($major);
        $doc->degree = trim($degree);
        $doc->position = trim($position);
        $doc->author = Auth::user()->name;
        $doc->path = $path;
        $doc->ext = $file_ext;
        $doc->photo = $imagePath; // tetap pakai lama kalau tidak ada baru
        $doc->save();

        return response()->json([
            'status' => 'ok',
        ]);
    }


    public function viewDelete(Request $request, $id)
    {

        try {
            $document = Cv::find($id);
            return view('pages.cv.cv-delete', [
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

    public function share(Request $request, $id)
    {

        try {
            $document = Cv::find($id);
            return view('pages.cv.cv-share', [
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

    public function pdf(Request $request, $id)
    {
        try {
            $document = Cv::find($id);
            return view('pages.cv.cv-pdf', [
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
        $task = Cv::find($id);
        $task->delete();

        return response()->json([
            "action" => "deleted"
        ]);
    }

    public function getName(Request $request)
    {

        try {
            $data = Cv::where('name', 'LIKE', '%' . $request->search . '%')
                ->select('id', 'name')
                ->limit(5)
                ->get();

            Log::info(json_encode($data));
            return response()->json([
                'status' => 'ok',
                'data'  => $data
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getNameUser(Request $request)
    {

        try {
            $data = User::select('id', 'name')
                ->limit(5)
                ->get();

            Log::info(json_encode($data));
            return response()->json([
                'status' => 'ok',
                'data'  => $data
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function pengajuan(Request $request)
    {
        try {
            return view('pages.cv.cv-pengajuan');
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function cvPengajuanSave(Request $request)
    {
        try {
            DB::beginTransaction();
            $resultCvGroup = CvGroup::create([
                'description' => $request->description,
                'created_by' => Auth::User()->name,
                'created_by_id' => Auth::User()->id,
                'reviewer_id' => $request->reviewer_id,
                'reviewer_name' => User::find($request->reviewer_id)->name,
                'status' => 'SEND',
            ]);

            $arraySelectedIds = explode(',', $request->selected_ids);

            foreach ($arraySelectedIds as $cvId) {

                DB::table('cv_group_detail')->insert([
                    'cv_id' => (int)$cvId,
                    'status' => '0',
                    'cv_group_id' => $resultCvGroup->id,
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => 'ok',
                'message' => 'Pengajuan CV berhasil dikirim.'
            ]);
        } catch (Throwable $e) {
            // Tangani error
            DB::rollBack();
            return response()->json([
                'message' => 'Terjadi kesalahan saat menyimpan data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function getCvPengajuan(Request $request)
    {

        if ($request->ajax()) {
            $data = CvGroup::select([
                'id',
                'status',
                'reviewer_name',
                'description',
                DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"),
                'created_by',

            ])->orderBy('created_at', 'desc');

            return DataTables::of($data)
                ->addColumn('action', function ($row) {

                    $deleteBtn = '';
                    if (Gate::allows('delete_cv_group')) {
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
                        ' . $deleteBtn . '
                    </div>
                </div>';
                    return $btn;
                })

                ->rawColumns(['action']) // Agar HTML di kolom 'action' dirender
                ->make(true);
        }
    }
    public function viewDeletePengajuan(Request $request, $id)
    {

        try {
            $document = CvGroup::find($id);
            return view('pages.cv.cv-pengajuan-delete', [
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


    public function pengajuanDeleted($id)
    {
        $task = CvGroup::find($id);
        $task->delete();

        return response()->json([
            "action" => "deleted"
        ]);
    }
}
