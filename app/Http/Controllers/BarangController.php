<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class BarangController extends CrudController
{
    protected function modul(): string
    {
        return 'barang';
    }

    protected function modelClass(): string
    {
        return Barang::class;
    }

    protected function baseQuery(): Builder
    {
        return DB::table('barang')->select('id', 'kode', 'nama', 'satuan');
    }

    protected function config(): array
    {
        return [
            'title' => config('gudang.menu.barang'),
            'add' => 'Tambah barang',
            'order' => 'nama',
            'search' => ['kode', 'nama'],
            'cols' => ['kode' => 'Kode', 'nama' => 'Nama barang', 'satuan' => 'Satuan'],
            'fields' => [
                'kode' => ['label' => 'Kode barang', 'type' => 'text', 'max' => 20],
                'nama' => ['label' => 'Nama barang', 'type' => 'text', 'max' => 100],
                'satuan' => ['label' => 'Satuan (kg, pcs, ton...)', 'type' => 'text', 'max' => 20],
            ],
        ];
    }

    protected function aturanTambahan(?int $id): array
    {
        return ['kode' => [Rule::unique('barang', 'kode')->ignore($id)]];
    }
}
