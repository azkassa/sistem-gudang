<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Transaksi barang: masuk (dari pemasok) atau ekspor (keluar ke tujuan)
        Schema::create('supply', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->index();
            $table->foreignId('barang_id')->constrained('barang')->restrictOnDelete();
            $table->enum('jenis', ['masuk', 'ekspor']);
            $table->unsignedInteger('jumlah');
            $table->string('tujuan', 120)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->timestamp('dibuat_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply');
    }
};
