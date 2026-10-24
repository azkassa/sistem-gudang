<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PenggunaController extends CrudController
{
    protected function modul(): string
    {
        return 'pengguna';
    }

    protected function modelClass(): string
    {
        return User::class;
    }

    protected function baseQuery(): Builder
    {
        return DB::table('pengguna')->select('id', 'username', 'nama', 'role');
    }

    protected function config(): array
    {
        return [
            'title' => config('gudang.menu.pengguna'),
            'add' => 'Tambah pengguna',
            'order' => 'nama',
            'search' => ['username', 'nama'],
            'cols' => ['username' => 'Username', 'nama' => 'Nama', 'role' => 'Hak akses'],
            'fields' => [
                'username' => ['label' => 'Username', 'type' => 'text', 'max' => 40],
                'nama' => ['label' => 'Nama lengkap', 'type' => 'text', 'max' => 100],
                'role' => ['label' => 'Hak akses', 'type' => 'enum', 'options' => ['admin', 'user']],
                'password_hash' => ['label' => 'Password (min. 6 karakter)', 'type' => 'password'],
            ],
        ];
    }

    protected function aturanTambahan(?int $id): array
    {
        return ['username' => [Rule::unique('pengguna', 'username')->ignore($id)]];
    }

    protected function tolakHapus(int $id): ?string
    {
        return $id === (int) auth()->id()
            ? 'Akun yang sedang dipakai tidak bisa dihapus.'
            : null;
    }
}
