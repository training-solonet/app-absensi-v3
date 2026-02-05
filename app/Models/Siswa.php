<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 */
class Siswa extends Model
{
    protected $connection = 'siswa_connectis';

    protected $table = 'view_siswa';

    public $timestamps = false;
}
