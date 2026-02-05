<?php

namespace App\Console\Commands;

use App\Models\Absensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MarkAbsentStudents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absensi:mark-absent {date?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark students as Alpha (absent) if they did not tap in/out for the day';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $date = $this->argument('date') ? Carbon::parse($this->argument('date')) : Carbon::today();

        $this->info("Memeriksa absensi untuk tanggal: {$date->format('Y-m-d')}");

        // Get all active students
        $allStudents = Siswa::all();
        $totalStudents = $allStudents->count();

        $this->info("Total siswa: {$totalStudents}");

        // Get students who already have attendance record for the date
        $attendedStudentIds = Absensi::whereDate('tanggal', $date)
            ->pluck('id_siswa')
            ->unique()
            ->toArray();

        $this->info('Siswa yang sudah absen: '.count($attendedStudentIds));

        // Find students who didn't attend
        $absentStudents = $allStudents->whereNotIn('id', $attendedStudentIds);
        $absentCount = $absentStudents->count();

        if ($absentCount === 0) {
            $this->info('✓ Semua siswa sudah melakukan absensi!');

            return Command::SUCCESS;
        }

        $this->info("Siswa yang tidak hadir (Alpha): {$absentCount}");

        // Create Alpha records for absent students
        $bar = $this->output->createProgressBar($absentCount);
        $bar->start();

        foreach ($absentStudents as $student) {
            // Check if Alpha record already exists (to avoid duplicate)
            $existingAlpha = Absensi::where('id_siswa', $student->id)
                ->whereDate('tanggal', $date)
                ->where('keterangan', 'Alpha')
                ->first();

            if (! $existingAlpha) {
                Absensi::create([
                    'id_siswa' => $student->id,
                    'tanggal' => $date,
                    'waktu_masuk' => null,
                    'waktu_keluar' => null,
                    'keterangan' => 'Alpha',
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("✓ Selesai! {$absentCount} siswa ditandai sebagai Alpha.");

        return Command::SUCCESS;
    }
}
