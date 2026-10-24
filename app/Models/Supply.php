<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Supply extends Model
{
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = null;

    protected $table = 'supply';

    protected $fillable = ['tanggal', 'barang_id', 'jenis', 'jumlah', 'tujuan', 'keterangan'];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    /** Stok barang = total masuk - total ekspor (opsional: abaikan satu transaksi saat ubah data). */
    public static function stok(int $barangId, int $kecuali = 0): int
    {
        return (int) static::where('barang_id', $barangId)
            ->where('id', '<>', $kecuali)
            ->selectRaw("COALESCE(SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE -jumlah END), 0) AS total")
            ->value('total');
    }
}
