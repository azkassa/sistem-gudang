<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = null;

    protected $table = 'barang';

    protected $fillable = ['kode', 'nama', 'satuan'];

    public function supply(): HasMany
    {
        return $this->hasMany(Supply::class, 'barang_id');
    }
}
