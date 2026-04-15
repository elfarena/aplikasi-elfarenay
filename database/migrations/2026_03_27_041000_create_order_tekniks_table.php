<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_tekniks', function (Blueprint $table) {
            $table->id();
            $table->string('tanggal', 10);
            $table->string('kelurahan');
            $table->string('kecamatan')->nullable();
            $table->string('zona', 20)->nullable();
            $table->string('kode_order', 20)->nullable();
            $table->string('realisasi_order');
            $table->string('perbaikan')->nullable();
            $table->string('diameter', 20)->nullable();
            $table->string('keterangan')->nullable();
            $table->string('stan_meter', 30)->nullable();
            $table->string('tanggal_realisasi', 10)->nullable();
            $table->string('pelaksana', 50)->nullable();
            $table->enum('status_realisasi', ['masuk', 'proses', 'selesai'])->default('masuk');
            $table->string('sumber', 50)->nullable();
            $table->string('no_pelanggan', 30)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_tekniks');
    }
};

