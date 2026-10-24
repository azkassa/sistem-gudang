<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = null;

    protected $table = 'karyawan';

    protected $fillable = ['nip', 'nama', 'jabatan'];
}
