<?php

namespace Tests\Feature;

use App\Models\Barang;
use App\Models\Supply;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class SupplyTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::create([
            'username' => 'admin', 'nama' => 'Pengelola', 'role' => 'admin',
            'password_hash' => Hash::make('admin123'),
        ]);
    }

    public function test_ekspor_melebihi_stok_ditolak(): void
    {
        $barang = Barang::create(['kode' => 'B-1', 'nama' => 'Kopi', 'satuan' => 'kg']);
        Supply::create(['tanggal' => '2026-01-01', 'barang_id' => $barang->id, 'jenis' => 'masuk', 'jumlah' => 10]);

        $this->actingAs($this->admin())
            ->post('/supply', [
                'tanggal' => '2026-01-02', 'barang_id' => $barang->id,
                'jenis' => 'ekspor', 'jumlah' => 20,
            ])
            ->assertSessionHasErrors('jumlah');

        $this->assertDatabaseCount('supply', 1);
    }

    public function test_ekspor_sesuai_stok_tersimpan(): void
    {
        $barang = Barang::create(['kode' => 'B-1', 'nama' => 'Kopi', 'satuan' => 'kg']);
        Supply::create(['tanggal' => '2026-01-01', 'barang_id' => $barang->id, 'jenis' => 'masuk', 'jumlah' => 10]);

        $this->actingAs($this->admin())
            ->post('/supply', [
                'tanggal' => '2026-01-02', 'barang_id' => $barang->id,
                'jenis' => 'ekspor', 'jumlah' => 4,
            ])
            ->assertRedirect(route('supply.index'));

        $this->assertSame(6, Supply::stok($barang->id));
    }

    public function test_user_biasa_tidak_boleh_menambah_supply(): void
    {
        $user = User::create([
            'username' => 'staf', 'nama' => 'Staf', 'role' => 'user',
            'password_hash' => Hash::make('staf123'),
        ]);

        $this->actingAs($user)->get('/supply/create')->assertForbidden();
        $this->actingAs($user)->get('/supply')->assertOk();
    }
}
