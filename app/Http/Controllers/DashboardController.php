<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->get('month', date('m'));
        $currentMonth = (int) $selectedMonth;
        $year = date('Y');
        $today = now()->toDateString();

        // Get total active students
        $totalSiswa = Siswa::count();

        // Get today's attendance in a single query
        $todayStats = DB::connection('absensi_v2')
            ->table('absen')
            ->select(
                DB::raw('COUNT(DISTINCT CASE WHEN keterangan = "hadir" THEN id_siswa END) as total_hadir'),
                DB::raw('COUNT(DISTINCT CASE WHEN keterangan = "terlambat" THEN id_siswa END) as total_terlambat')
            )
            ->whereDate('tanggal', $today)
            ->first();

        $hadirHariIni = $todayStats ? ($todayStats->total_hadir ?? 0) : 0;
        $terlambatHariIni = $todayStats ? ($todayStats->total_terlambat ?? 0) : 0;
        $tidakHadirHariIni = $totalSiswa - $hadirHariIni;

        // Get student list from siswa_connectis database
        $siswaList = DB::connection('siswa_connectis')
            ->table('view_siswa')
            ->select('id', 'name as nama_siswa')
            ->get()
            ->keyBy('id');

        // Get attendance data from absensi_v2 database
        $absensiData = DB::connection('absensi_v2')
            ->table('absen')
            ->select(
                'id_siswa',
                DB::raw('COUNT(CASE WHEN keterangan = "hadir" THEN 1 END) as total_hadir')
            )
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $year)
            ->where('keterangan', 'hadir')
            ->groupBy('id_siswa')
            ->orderBy('total_hadir', 'desc')
            ->get();

        // Get student names from siswa_connectis database
        $siswaIds = $absensiData->pluck('id_siswa')->toArray();
        $siswaList = [];

        if (! empty($siswaIds)) {
            $siswaList = DB::connection('siswa_connectis')
                ->table('view_siswa')
                ->whereIn('id', $siswaIds)
                ->pluck('name', 'id')
                ->toArray();
        }

        // Combine the data
        $absensiBulanIni = $absensiData->map(function ($item) use ($siswaList) {
            return (object) [
                'id_siswa' => $item->id_siswa,
                'nama_siswa' => $siswaList[$item->id_siswa] ?? 'Siswa #'.$item->id_siswa,
                'total_hadir' => $item->total_hadir,
                'total_terlambat' => 0, // Will be updated below
            ];
        });

        // Get late arrivals count for each student
        $terlambatData = DB::connection('absensi_v2')
            ->table('absen')
            ->select(
                'id_siswa',
                DB::raw('COUNT(*) as total_terlambat')
            )
            ->where('keterangan', 'terlambat')
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $year)
            ->whereIn('id_siswa', $siswaIds)
            ->groupBy('id_siswa')
            ->get()
            ->keyBy('id_siswa');

        // Update late counts
        $absensiBulanIni = $absensiBulanIni->map(function ($item) use ($terlambatData) {
            if (isset($terlambatData[$item->id_siswa])) {
                $item->total_terlambat = $terlambatData[$item->id_siswa]->total_terlambat;
            }

            return $item;
        });

        // Get late arrivals - today only when no month filter is applied, otherwise filter by selected month
        $terlambatQuery = Absensi::with(['siswa'])
            ->where('keterangan', 'terlambat');

        if (! $request->has('month') || $request->month == date('m')) {
            // If no month filter or current month is selected, show only today's late arrivals
            $terlambatQuery->whereDate('tanggal', $today);
        } else {
            // If a specific month is selected, filter by that month
            $terlambatQuery
                ->whereMonth('tanggal', $currentMonth)
                ->whereYear('tanggal', $year);
        }

        $terlambat = $terlambatQuery
            ->orderBy('tanggal', 'desc')
            ->take(6)
            ->get();

        // Get late arrivals per student for the selected month in a single query
        $terlambatPerSiswa = Absensi::with('siswa')
            ->select('id_siswa', DB::raw('COUNT(*) as total'))
            ->whereMonth('tanggal', $currentMonth)
            ->whereYear('tanggal', $year)
            ->where('keterangan', 'terlambat')
            ->groupBy('id_siswa')
            ->orderBy('total', 'desc')
            ->get();

        // Calculate total late arrivals for the month
        $totalTerlambat = $terlambatPerSiswa->sum('total');
        $totalTerlambatBulanIni = $totalTerlambat;

        // Get monthly statistics in a single query
        $monthlyStats = Absensi::select(
            DB::raw('MONTH(tanggal) as month'),
            DB::raw('SUM(CASE WHEN keterangan = "hadir" THEN 1 ELSE 0 END) as hadir'),
            DB::raw('SUM(CASE WHEN keterangan = "terlambat" THEN 1 ELSE 0 END) as terlambat')
        )
            ->whereYear('tanggal', $year)
            ->groupBy(DB::raw('MONTH(tanggal)'))
            ->get()
            ->keyBy('month');

        // Prepare monthly statistics for the chart
        $statistikBulanan = [];
        $monthNames = [
            1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
            'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des',
        ];

        for ($i = 1; $i <= 12; $i++) {
            $monthData = $monthlyStats->get($i, (object) ['hadir' => 0, 'terlambat' => 0]);
            $statistikBulanan[] = [
                'bulan' => $monthNames[$i],
                'hadir' => (int) $monthData->hadir,
                'terlambat' => (int) $monthData->terlambat,
            ];
        }

        // Full month names for dropdown
        $fullMonthNames = [
            1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember',
        ];

        // The $absensiBulanIni is already prepared above with student names and attendance counts
        // No need to overwrite it here

        return view('dashboard', compact(
            'totalSiswa',
            'hadirHariIni',
            'terlambatHariIni',
            'tidakHadirHariIni',
            'absensiBulanIni',
            'terlambat',
            'terlambatPerSiswa',
            'fullMonthNames',
            'selectedMonth',
            'statistikBulanan',
            'year',
            'totalTerlambat',
            'totalTerlambatBulanIni'
        ));
    }
}
