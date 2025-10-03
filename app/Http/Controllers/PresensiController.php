<?php

namespace App\Http\Controllers;

use App\Models\Presensi;
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
            ->make(true);
    }
}
