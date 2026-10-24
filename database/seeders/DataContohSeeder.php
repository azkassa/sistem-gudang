<?php

namespace Database\Seeders;

use App\Models\Absensi;
use App\Models\Barang;
use App\Models\Karyawan;
use App\Models\Supply;
use Illuminate\Database\Seeder;

class DataContohSeeder extends Seeder
{
    public function run(): void
    {
        if (Barang::exists()) {
            return;
        }

        $barang = collect([
            ['BRG-001', 'Kopi Arabika Gayo', 'kg'],
            ['BRG-002', 'Karet Alam SIR 20', 'ton'],
            ['BRG-003', 'Rotan Mentah', 'bundel'],
            ['BRG-004', 'Minyak Kelapa Sawit', 'liter'],
        ])->map(fn ($b) => Barang::create(['kode' => $b[0], 'nama' => $b[1], 'satuan' => $b[2]]));

        foreach ([
            ['KRY-001', 'Budi Santoso', 'Kepala Gudang'],
            ['KRY-002', 'Siti Rahmawati', 'Admin Stok'],
            ['KRY-003', 'Andi Pratama', 'Operator Forklift'],
            ['KRY-004', 'Dewi Lestari', 'Staf Pengiriman'],
        ] as $k) {
            Karyawan::create(['nip' => $k[0], 'nama' => $k[1], 'jabatan' => $k[2]]);
        }

        $hari = fn (int $mundur) => now()->subDays($mundur)->toDateString();

        foreach ([
            [$hari(3), $barang[0]->id, 'masuk', 500, 'PT Tani Makmur', 'Panen batch 12'],
            [$hari(3), $barang[1]->id, 'masuk', 80, 'CV Karet Jaya', null],
            [$hari(2), $barang[0]->id, 'ekspor', 200, 'Hamburg, Jerman', 'Kontainer 20ft'],
            [$hari(1), $barang[2]->id, 'masuk', 300, 'UD Rotan Mandiri', null],
            [$hari(0), $barang[1]->id, 'ekspor', 40, 'Shanghai, Tiongkok', 'Kapal MV Nusantara'],
        ] as $s) {
            Supply::create([
                'tanggal' => $s[0], 'barang_id' => $s[1], 'jenis' => $s[2],
                'jumlah' => $s[3], 'tujuan' => $s[4], 'keterangan' => $s[5],
            ]);
        }

        foreach ([
            ['Budi Santoso', '07:55', 'Hadir'],
            ['Siti Rahmawati', '08:05', 'Hadir'],
            ['Andi Pratama', null, 'Sakit'],
            ['Dewi Lestari', '08:30', 'Hadir'],
        ] as $a) {
            Absensi::create(['tanggal' => $hari(0), 'nama_karyawan' => $a[0], 'jam_masuk' => $a[1], 'status' => $a[2]]);
        }
    }
}
