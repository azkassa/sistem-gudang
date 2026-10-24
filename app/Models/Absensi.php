<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = null;

    protected $table = 'absensi';

    protected $fillable = ['tanggal', 'nama_karyawan', 'jam_masuk', 'jam_pulang', 'status'];
}
