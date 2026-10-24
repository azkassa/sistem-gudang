<?php

namespace App\Http\Controllers;

use App\Models\Supply;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SupplyController extends CrudController
{
    protected function modul(): string
    {
        return 'supply';
    }

    protected function modelClass(): string
    {
        return Supply::class;
    }

    protected function baseQuery(): Builder
    {
        return DB::table('supply as s')
            ->join('barang as b', 'b.id', '=', 's.barang_id')
            ->select('s.id', 's.tanggal', 'b.nama as barang', 's.jenis', 's.jumlah', 'b.satuan', 's.tujuan', 's.keterangan');
    }

    protected function config(): array
    {
        return [
            'title' => config('gudang.menu.supply'),
            'add' => 'Catat supply',
            'date' => 's.tanggal',
            'order' => 's.tanggal DESC, s.id DESC',
            'search' => ['b.nama', 's.tujuan'],
            'cols' => [
                'tanggal' => 'Tanggal', 'barang' => 'Barang', 'jenis' => 'Jenis', 'jumlah' => 'Jumlah',
                'satuan' => 'Satuan', 'tujuan' => 'Asal / Tujuan', 'keterangan' => 'Catatan',
            ],
            'fields' => [
                'tanggal' => ['label' => 'Tanggal', 'type' => 'date'],
                'barang_id' => ['label' => 'Barang', 'type' => 'ref', 'options' => ['barang', 'nama']],
                'jenis' => ['label' => 'Jenis', 'type' => 'enum', 'options' => ['masuk', 'ekspor']],
                'jumlah' => ['label' => 'Jumlah', 'type' => 'number'],
                'tujuan' => ['label' => 'Asal / Tujuan', 'type' => 'text', 'required' => false, 'max' => 120],
                'keterangan' => ['label' => 'Catatan', 'type' => 'text', 'required' => false],
            ],
        ];
    }

    // Ekspor tidak boleh melebihi stok yang tersedia.
    protected function validasiTambahan(array $data, ?int $id): void
    {
        if (($data['jenis'] ?? '') !== 'ekspor') {
            return;
        }

        $tersedia = Supply::stok((int) $data['barang_id'], (int) $id);

        if ((int) $data['jumlah'] > $tersedia) {
            throw ValidationException::withMessages([
                'jumlah' => "Stok tidak cukup untuk diekspor. Stok tersedia: {$tersedia}.",
            ]);
        }
    }
}
