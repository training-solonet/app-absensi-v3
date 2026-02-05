<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $tanggalAwal = request('tanggal_awal', date('Y-m-d'));
        $tanggalAkhir = request('tanggal_akhir', date('Y-m-d'));
        $namaSiswa = request('nama_siswa');

        $query = Absensi::with('siswa')
            ->whereBetween('tanggal', [$tanggalAwal, $tanggalAkhir])
            ->orderBy('tanggal', 'desc');

        // Filter by student name if provided
        if ($namaSiswa) {
            // Dapatkan dulu ID siswa berdasarkan nama
            $siswaIds = \App\Models\Siswa::where('name', 'like', '%'.$namaSiswa.'%')
                ->pluck('id')
                ->toArray();

            if (! empty($siswaIds)) {
                $query->whereIn('id_siswa', $siswaIds);
            } else {
                // Jika tidak ada siswa yang cocok, pastikan query tidak mengembalikan hasil
                $query->where('id', 0);
            }
        }

        $absen = $query->get();

        // Get unique student list for filter dropdown
        $siswas = \App\Models\Siswa::orderBy('name')->get();

        // Hitung jumlah hadir dan terlambat
        $hadirKemarin = Absensi::whereDate('tanggal', now()->subDay())
            ->where('keterangan', 'hadir')
            ->count();

        $terlambatKemarin = Absensi::whereDate('tanggal', now()->subDay())
            ->where('keterangan', 'terlambat')
            ->count();

        return view('absensi', compact(
            'absen',
            'siswas',
            'tanggalAwal',
            'tanggalAkhir',
            'hadirKemarin',
            'terlambatKemarin'
        ));
    }

    public function terlambat()
    {
        $selectedDate = request('tanggal', date('Y-m-d'));

        $absen = Absensi::with('siswa')
            ->whereDate('tanggal', $selectedDate)
            ->where('keterangan', 'terlambat')
            ->orderBy('tanggal', 'desc')
            ->get();

        /** @var view-string $viewName */
        $viewName = 'absensi-terlambat';

        return view($viewName, compact('absen', 'selectedDate'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'keterangan' => 'required|in:Hadir,Terlambat,Izin,Sakit,Alpha',
            'catatan' => 'nullable|string|max:255',
        ]);

        /** @var Absensi $absensi */
        $absensi = Absensi::findOrFail($id);

        $updates = [
            'keterangan' => $validated['keterangan'],
            'updated_at' => now(),
        ];

        if (isset($validated['catatan'])) {
            $updates['catatan'] = $validated['catatan'];
        }

        if (($validated['keterangan'] === 'Hadir' || $validated['keterangan'] === 'Terlambat') && empty($absensi->getAttribute('waktu_masuk'))) {
            $updates['waktu_masuk'] = now()->format('Y-m-d H:i:s');
        }

        $absensi->update($updates);

        return back()->with('success', 'Status absensi berhasil diperbarui');
    }
}
