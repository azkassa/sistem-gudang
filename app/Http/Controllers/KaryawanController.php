<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class KaryawanController extends CrudController
{
    protected function modul(): string
    {
        return 'karyawan';
    }

    protected function modelClass(): string
    {
        return Karyawan::class;
    }

    protected function baseQuery(): Builder
    {
        return DB::table('karyawan')->select('id', 'nip', 'nama', 'jabatan');
    }

    protected function config(): array
    {
        return [
            'title' => config('gudang.menu.karyawan'),
            'add' => 'Tambah karyawan',
            'order' => 'nama',
            'search' => ['nip', 'nama', 'jabatan'],
            'cols' => ['nip' => 'NIP', 'nama' => 'Nama', 'jabatan' => 'Jabatan'],
            'fields' => [
                'nip' => ['label' => 'NIP', 'type' => 'text', 'max' => 20],
                'nama' => ['label' => 'Nama lengkap', 'type' => 'text', 'max' => 100],
                'jabatan' => ['label' => 'Jabatan', 'type' => 'text', 'max' => 60],
            ],
        ];
    }

    protected function aturanTambahan(?int $id): array
    {
        return ['nip' => [Rule::unique('karyawan', 'nip')->ignore($id)]];
    }
}
