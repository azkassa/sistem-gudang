<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AbsensiController extends CrudController
{
    protected function modul(): string
    {
        return 'absensi';
    }

    protected function modelClass(): string
    {
        return Absensi::class;
    }

    protected function baseQuery(): Builder
    {
        return DB::table('absensi as a')
            ->select('a.id', 'a.tanggal', 'a.nama_karyawan', 'a.jam_masuk', 'a.jam_pulang', 'a.status');
    }

    protected function config(): array
    {
        return [
            'title' => config('gudang.menu.absensi'),
            'add' => 'Catat kehadiran',
            'date' => 'a.tanggal',
            'order' => 'a.tanggal DESC, a.nama_karyawan',
            'search' => ['a.nama_karyawan'],
            'cols' => [
                'tanggal' => 'Tanggal', 'nama_karyawan' => 'Nama karyawan',
                'jam_masuk' => 'Masuk', 'jam_pulang' => 'Pulang', 'status' => 'Status',
            ],
            'fields' => [
                'tanggal' => ['label' => 'Tanggal', 'type' => 'date'],
                'nama_karyawan' => ['label' => 'Nama karyawan', 'type' => 'text', 'max' => 100],
                'status' => ['label' => 'Status', 'type' => 'enum', 'options' => ['Hadir', 'Izin', 'Sakit', 'Alpa']],
                'jam_masuk' => ['label' => 'Jam masuk', 'type' => 'time', 'required' => false],
                'jam_pulang' => ['label' => 'Jam pulang', 'type' => 'time', 'required' => false],
            ],
        ];
    }

    // Satu nama hanya boleh tercatat sekali per hari.
    protected function aturanTambahan(?int $id): array
    {
        return [
            'nama_karyawan' => [
                Rule::unique('absensi', 'nama_karyawan')
                    ->where('tanggal', request('tanggal'))
                    ->ignore($id),
            ],
        ];
    }
}
