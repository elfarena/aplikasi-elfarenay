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
        Schema::table('order_tekniks', function (Blueprint $table) {
            $table->string('nama_pelanggan')->nullable()->after('tanggal');
            $table->string('pelapor')->nullable()->after('nama_pelanggan');
            $table->string('alamat')->nullable()->after('pelapor');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_tekniks', function (Blueprint $table) {
            $table->dropColumn(['nama_pelanggan', 'pelapor', 'alamat']);
        });
    }
};

