<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Kehadiran karyawan per hari (nama diisi bebas)
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nama_karyawan', 100);
            $table->time('jam_masuk')->nullable();
            $table->time('jam_pulang')->nullable();
            $table->enum('status', ['Hadir', 'Izin', 'Sakit', 'Alpa'])->default('Hadir');
            $table->timestamp('dibuat_pada')->useCurrent();
            $table->unique(['tanggal', 'nama_karyawan'], 'satu_hari');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
