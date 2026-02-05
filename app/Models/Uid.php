<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $uid
 * @property string|null $name
 * @property int|null $id_siswa
 * @property-read Siswa|null $siswa
 */
class Uid extends Model
{
    protected $connection = 'absensi_v2';

    protected $table = 'uid';

    protected $fillable = ['uid', 'name', 'id_siswa'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }
}
