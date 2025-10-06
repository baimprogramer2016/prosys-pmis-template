<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
use App\Models\PresensiBreak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;
use Yajra\DataTables\Facades\DataTables;

class PresensiController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $absen_today = Presensi::whereDate('created_at', now())->where('user_id', $user->id)->first();

            return view('pages.presensi.presensi', [
                'absen_today' => $absen_today
            ]);
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan menarik data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function presensiBreak()
    {
        try {
            $user = Auth::user();
            $absen_today = Presensi::whereDate('created_at', now())->where('user_id', $user->id)->first();
            $presensi_break = DB::table('presensi_break')
                ->where('presensi_id', $absen_today->id)
                ->where('status', true)
                ->first();
            if ($presensi_break) {
                return response()->json([
                    'status' => 'onbreak',
                    'data' => $presensi_break
                ]);
            } else {
                return response()->json([
                    'status' => 'onwork',
                    'data' => null
                ]);
            }
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan menarik data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updatePresensiBreak(Request $request)
    {
        try {
            if ($request->status == "insert") {
                PresensiBreak::create([
                    'status' => true,
                    'presensi_id' => $request->id,
                    'break_time' => $request->break_time,
                    'work_time' => null
                ]);
            }


            if ($request->status == "update") {
                $presensi_break = PresensiBreak::where('presensi_id', $request->id)
                    ->where('status', true)
                    ->whereDate('break_time', now())
                    ->first();
                if ($presensi_break) {
                    $presensi_break->update([
                        'status' => false,
                        'work_time' => $request->break_time
                    ]);
                }
                return response()->json([
                    'status' => 'success',
                    'message' => 'Data presensi break berhasil diperbarui',
                    'data' => $presensi_break
                ], 200);
            }
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan menarik data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function getPresensi(Request $request)
    {
        $user = Auth::user();
        $data = Presensi::select([
            'id',
            'nik',
            'user_id',
            'name',
            'latitude_in',
            'longitude_in',
            'latitude_out',
            'longitude_out',
            'check_in',
            'check_in_address',
            'check_out',
            'check_out_address',
            'status',
            'approval_by',
            'position',
            'departement',
            'work_description',
            'created_at',
        ])->where('user_id', $user->id)->orderBy('id', 'desc');

        return DataTables::of($data)
            ->editColumn('latlong_in', function ($row) {
                return $row->latitude_in . ', ' . $row->longitude_in;
            })
            ->editColumn('latlong_out', function ($row) {
                return $row->latitude_out . ', ' . $row->longitude_out;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('detail', function ($row) {
                $hasBreak = !empty($row->r_presensi_break); // true kalau relasi ada (hasOne atau hasMany)
                $hasPhoto = !empty($row->photo_in) || !empty($row->photo_out);

                if ($hasBreak || $hasPhoto) {
                    return '<a href="" data-bs-toggle="modal" data-bs-target="#modal-pdf" onClick="return viewPdf(' . $row->id . ')" class="dropdown-item cursor-pointer text-primary">View</a>';
                }

                return '';
            })

            ->rawColumns(['detail']) // Agar HTML di kolom 'action' dirender
            ->make(true);
    }
    public function absen(Request $request)
    {
        try {
            Log::info(json_encode(Auth::user()));
            $user = Auth::user();
            $type = $request->type;
            $work_description = $request->work_description;
            $status = "OPEN";
            $now = $request->check_time;
            $position = $user->position;
            $departement = $user->departement;
            $user_id = $user->id;
            $name = $user->name;
            $nik = $user->nik;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $address = $request->address;

            if ($request->has('image')) {
                $image = $request->input('image');
                $image = str_replace('data:image/png;base64,', '', $image);
                $image = str_replace(' ', '+', $image);

                $imageName = 'attendance_' . time() . '.png';

                // simpan ke storage/app/public/attendance
                \Storage::disk('public')->put('attendance/' . $imageName, base64_decode($image));

                // path yang bisa dipanggil via url (kalau sudah buat storage:link)
                $photo = 'attendance/' . $imageName;
            }


            $absen_today = Presensi::whereDate('created_at', now())->where('user_id', $user->id)->first();
            if (!$absen_today) {
                //insert
                if ($type == 'IN') {
                    Presensi::create([
                        'nik' => $nik,
                        'user_id' => $user_id,
                        'name' => $name,
                        'latitude_in' => $latitude,
                        'longitude_in' => $longitude,
                        'check_in' => $now,
                        'check_in_address' => $address,
                        'position' => $position,
                        'departement' => $departement,
                        'work_description' => $work_description,
                        'status' => $status,
                        'photo_in' => $photo ?? null
                    ]);
                }
                if ($type == 'OUT') {
                    Presensi::create([
                        'user_id' => $user_id,
                        'name' => $name,
                        'nik' => $nik,
                        'latitude_out' => $latitude,
                        'longitude_out' => $longitude,
                        'check_out' => $now,
                        'check_out_address' => $address,
                        'position' => $position,
                        'departement' => $departement,
                        'work_description' => $work_description,
                        'status' => $status,
                        'photo_out' => $photo ?? null
                    ]);
                }
            } else {
                //update
                if ($type == 'IN') {
                    $absen_today->update([
                        'user_id' => $user_id,
                        'name' => $name,
                        'nik' => $nik,
                        'latitude_in' => $latitude,
                        'longitude_in' => $longitude,
                        // 'check_in' => $now,
                        'check_in_address' => $address,
                        'position' => $position,
                        'departement' => $departement,
                        'work_description' => $work_description,
                        'status' => $status,
                        // 'photo_in' => $photo
                    ]);
                }
                if ($type == 'OUT') {
                    $absen_today->update([
                        'user_id' => $user_id,
                        'name' => $name,
                        'nik' => $nik,
                        'latitude_out' => $latitude,
                        'longitude_out' => $longitude,
                        'check_out' => $now,
                        'check_out_address' => $address,
                        'position' => $position,
                        'departement' => $departement,
                        'work_description' => $work_description,
                        'status' => $status,
                        'photo_out' => $photo ?? null
                    ]);
                }
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Presensi berhasil disimpan',
                'data' => $absen_today
            ], 200);
        } catch (Throwable $e) {
            // Tangani error
            report($e);
            return response()->json([
                'status' => 'failed',
                'message' => 'Terjadi kesalahan menarik data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function detailPresensi(Request $request, $id)
    {
        try {
            $document = Presensi::with(['r_presensi_break'])->find($id);
            return view('pages.presensi.presensi-detail', [
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
    public function timeSheet()
    {
        try {
            return view('pages.time-sheet.time-sheet');
        } catch (Throwable $e) {
            // Tangani error
            return response()->json([
                'message' => 'Terjadi kesalahan menarik data.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getTimeSheet(Request $request)
    {
        $data = Presensi::select([
            'id',
            'nik',
            'user_id',
            'name',
            'latitude_in',
            'longitude_in',
            'latitude_out',
            'longitude_out',
            'check_in',
            'check_in_address',
            'check_out',
            'check_out_address',
            'status',
            'approval_by',
            'position',
            'departement',
            'work_description',
            'created_at',
        ])->orderBy('id', 'desc');

        return DataTables::of($data)
            ->editColumn('latlong_in', function ($row) {
                return $row->latitude_in . ', ' . $row->longitude_in;
            })
            ->editColumn('latlong_out', function ($row) {
                return $row->latitude_out . ', ' . $row->longitude_out;
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('Y-m-d');
            })
            ->addColumn('day', function ($row) {
                // Ambil nama hari dari created_at
                return $row->created_at->format('l'); // Contoh: Monday, Tuesday
            })
            ->addColumn('detail', function ($row) {
                if (optional($row->r_presensi_break)->count() > 0 || $row->photo_in || $row->photo_out) {
                    $btn = '<a href="" data-bs-toggle="modal" data-bs-target="#modal" onClick="return viewPdf(' . $row->id . ')" class="dropdown-item cursor-pointer text-primary">View</a>';
                } else {
                    $btn = "";
                }

                return $btn;
            })
            ->rawColumns(['detail']) // Agar HTML di kolom 'action' dirender
            ->make(true);
    }
}
