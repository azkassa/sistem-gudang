<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

// Akun awal (sebelumnya dibuat otomatis oleh index.php saat pertama dibuka).
class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            ['nama' => 'Pengelola', 'role' => 'admin', 'password_hash' => Hash::make('admin123')]
        );
        User::updateOrCreate(
            ['username' => 'staf'],
            ['nama' => 'Staf Gudang', 'role' => 'user', 'password_hash' => Hash::make('staf123')]
        );
    }
}
