<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    public function test_admin_bisa_masuk(): void
    {
        User::create([
            'username' => 'admin', 'nama' => 'Pengelola', 'role' => 'admin',
            'password_hash' => Hash::make('admin123'),
        ]);

        $this->post('/login', ['username' => 'admin', 'password' => 'admin123'])
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
    }

    public function test_password_salah_ditolak(): void
    {
        User::create([
            'username' => 'admin', 'nama' => 'Pengelola', 'role' => 'admin',
            'password_hash' => Hash::make('admin123'),
        ]);

        $this->post('/login', ['username' => 'admin', 'password' => 'salah'])
            ->assertSessionHas('err');

        $this->assertGuest();
    }
}
