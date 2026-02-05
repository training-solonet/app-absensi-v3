<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $id_siswa
 * @property \Carbon\Carbon $tanggal
 * @property \Carbon\Carbon|null $waktu_masuk
 * @property \Carbon\Carbon|null $waktu_keluar
 * @property string $keterangan
 * @property string|null $catatan
 */
class Absensi extends Model
{
    // Tentukan koneksi database yang digunakan
    protected $connection = 'absensi_v2';

    protected $table = 'absen';

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'waktu_masuk' => 'datetime',
        'waktu_keluar' => 'datetime',
        'tanggal' => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_siswa',
        'tanggal',
        'waktu_masuk',
        'waktu_keluar',
        'keterangan',
        'catatan',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }
}
