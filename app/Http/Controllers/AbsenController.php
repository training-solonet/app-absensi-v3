<?php

namespace App\Http\Controllers;

use App\Models\Uid;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AbsenController extends Controller
{
    public function index()
    {
        return view('absen');
    }

    public function processFace(Request $request)
    {
        try {
            // 1. Validasi request (HANYA FOTO)
            $request->validate([
                'image' => 'required|image|max:5120',
            ]);

            // 2. Simpan file sementara
            $imagePath = $request->file('image')->store('temp', 'public');
            $fullPath  = storage_path('app/public/' . $imagePath);

            // 3. Kirim ke Python Face Recognition API
            $pythonApiUrl = config('app.python_api_url');

            $faceResponse = Http::timeout(30)
                ->attach('image', file_get_contents($fullPath), 'face.jpg')
                ->post($pythonApiUrl);

            // Hapus file sementara
            unlink($fullPath);

            if (!$faceResponse->successful()) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Gagal terhubung ke Face Recognition API'
                ], 500);
            }

            $faceData = $faceResponse->json();

            if ($faceData['status'] !== 'success') {
                return response()->json([
                    'status'  => 'error',
                    'message' => $faceData['message'] ?? 'Wajah tidak dikenali'
                ], 400);
            }

            // 4. Ambil nama hasil deteksi Python
            $detectedFaces = $faceData['detected_faces'] ?? [];
            $detectedName  = count($detectedFaces) > 0 ? $detectedFaces[0] : 'Unknown';

            if ($detectedName === 'Unknown') {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Wajah tidak dikenali'
                ], 400);
            }

            // 5. Cari siswa (DB SISWA)
            $siswa = Siswa::on('siswa_connectis')
                ->where('name', $detectedName)
                ->first();

            if (!$siswa) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'Data siswa tidak ditemukan'
                ], 404);
            }

            // 6. Cari UID (DB ABSENSI)
            $uidRecord = Uid::on('absensi_v2')
                ->where('id_siswa', $siswa->id)
                ->first();

            if (!$uidRecord) {
                return response()->json([
                    'status'  => 'error',
                    'message' => 'UID tidak ditemukan untuk siswa ini'
                ], 404);
            }

            // 7. Kirim ke API absensi
            $absensiApiUrl = config('app.absensi_api_url');

            $absensiResponse = Http::timeout(10)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept'       => 'application/json',
                ])
                ->post($absensiApiUrl, [
                    'uid'       => $uidRecord->uid,
                    'nama'      => $siswa->name,
                    'timestamp' => now()->toISOString(),
                ]);

            if (!$absensiResponse->successful()) {
                return response()->json([
                    'status'  => 'partial_success',
                    'message' => 'Wajah dikenali, tapi gagal kirim absensi',
                    'uid'     => $uidRecord->uid,
                    'nama'    => $siswa->name,
                ], 207);
            }

            // 8. Sukses penuh
            return response()->json([
                'status'        => 'success',
                'detected_name' => $detectedName,
                'uid'           => $uidRecord->uid,
                'nama'          => $siswa->name,
                'absensi_msg'   => $absensiResponse->json()['message'] ?? 'Absensi berhasil',
                'timestamp'     => now()->toISOString(),
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
