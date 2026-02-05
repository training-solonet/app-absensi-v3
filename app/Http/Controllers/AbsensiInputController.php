<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Uid;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AbsensiInputController extends Controller
{
    /**
     * Show the UID input form page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('absensi-uid-input');
    }

    /**
     * Store attendance data based on UID
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate UID input
            $validated = $request->validate([
                'uid' => 'required|string',
            ]);

            $uid = trim($validated['uid']);

            // Find the UID record
            $uidRecord = Uid::where('uid', $uid)->first();

            if (! $uidRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'UID tidak ditemukan. Silakan cek kembali kartu UID Anda.',
                ], 404);
            }

            // Check if UID has associated student
            if (! $uidRecord->id_siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'UID belum terdaftar ke siswa. Silakan hubungi admin.',
                ], 400);
            }

            // Get student data from the UID
            $siswaId = $uidRecord->id_siswa;

            // Get student information
            $siswa = Siswa::find($siswaId);

            if (! $siswa) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data siswa tidak ditemukan.',
                ], 404);
            }

            // Get today's date
            $today = Carbon::today();
            $now = Carbon::now();

            // Determine attendance status based on time
            // If tap after 08:00:00, mark as "Terlambat" (Late)
            $batasWaktu = Carbon::today()->setTime(8, 0, 0); // 08:00:00
            $keterangan = $now->greaterThan($batasWaktu) ? 'Terlambat' : 'Hadir';

            // Check if student already has attendance record for today
            $existingAbsensi = Absensi::where('id_siswa', $siswaId)
                ->whereDate('tanggal', $today)
                ->first();

            if ($existingAbsensi) {
                // Update exit time if record exists
                $waktuKeluar = $now->format('Y-m-d H:i:s');

                $existingAbsensi->waktu_keluar = $waktuKeluar;
                $existingAbsensi->save();

                // Refresh the model to get updated data
                $existingAbsensi->refresh();

                return response()->json([
                    'success' => true,
                    'message' => 'Data absensi keluar berhasil disimpan!',
                    'data' => [
                        'nama_siswa' => $siswa->name,
                        'tanggal' => $today->format('d-m-Y'),
                        'waktu_masuk' => $existingAbsensi->waktu_masuk ? Carbon::parse($existingAbsensi->waktu_masuk)->format('H:i:s') : '-',
                        'waktu_keluar' => Carbon::parse($waktuKeluar)->format('H:i:s'),
                    ],
                ]);
            }

            // Create new attendance record
            $absensi = Absensi::create([
                'id_siswa' => $siswaId,
                'tanggal' => $today,
                'waktu_masuk' => $now->format('Y-m-d H:i:s'),
                'waktu_keluar' => null,
                'keterangan' => $keterangan, // Hadir or Terlambat based on time
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data absensi berhasil disimpan!',
                'data' => [
                    'nama_siswa' => $siswa->name,
                    'tanggal' => $today->format('d-m-Y'),
                    'waktu_masuk' => $now->format('H:i:s'),
                ],
            ]);

        } catch (\Exception $e) {
            \Log::error('Absensi Input Error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: '.$e->getMessage(),
            ], 500);
        }
    }
}
