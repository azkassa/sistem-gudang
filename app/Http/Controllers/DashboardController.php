<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Barang;
use App\Models\Supply;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $hariIni = today()->toDateString();

        return view('dashboard', [
            'masukHariIni' => (int) Supply::where('jenis', 'masuk')->whereDate('tanggal', $hariIni)->sum('jumlah'),
            'eksporHariIni' => (int) Supply::where('jenis', 'ekspor')->whereDate('tanggal', $hariIni)->sum('jumlah'),
            'hadir' => Absensi::where('status', 'Hadir')->whereDate('tanggal', $hariIni)->count(),
            'stok' => Barang::query()
                ->withSum(['supply as masuk' => fn ($q) => $q->where('jenis', 'masuk')], 'jumlah')
                ->withSum(['supply as ekspor' => fn ($q) => $q->where('jenis', 'ekspor')], 'jumlah')
                ->orderBy('nama')
                ->get(),
        ]);
    }
}
