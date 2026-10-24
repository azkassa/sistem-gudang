<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Akun login (tabel `pengguna`). role: admin | user.
 */
class User extends Authenticatable
{
    public const CREATED_AT = 'dibuat_pada';
    public const UPDATED_AT = null;

    /** Hak akses role "user": c=tambah, r=lihat, u=ubah, d=hapus. Admin bisa semuanya. */
    public const HAK_USER = [
        'supply' => 'r',
        'absensi' => 'c',
    ];

    protected $table = 'pengguna';

    protected $fillable = ['username', 'nama', 'role', 'password_hash'];

    protected $hidden = ['password_hash'];

    public function getAuthPassword(): string
    {
        return $this->password_hash;
    }

    public function getAuthPasswordName(): string
    {
        return 'password_hash';
    }

    // Tabel tidak punya kolom remember_token.
    public function getRememberTokenName(): string
    {
        return '';
    }

    public function izin(string $modul, string $aksi): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        return $this->role === 'user'
            && str_contains(self::HAK_USER[$modul] ?? '', $aksi);
    }
}
