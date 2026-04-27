<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migrasi konversi kolom tanggal dari VARCHAR(dd/mm/yyyy) ke tipe DATE.
 *
 * PERUBAHAN DATA:
 * - Kolom tanggal, tanggal_masuk, tanggal_realisasi:
 *   SEBELUM : VARCHAR(10) — nilai "27/03/2026"
 *   SESUDAH : DATE       — nilai 2026-03-27 (tanggal sama, format beda)
 *
 * Data yang tidak valid / kosong akan menjadi NULL (aman).
 */
return new class extends Migration
{
    public function up(): void
    {
        // Langkah 1: tambah kolom DATE sementara
        Schema::table('order_tekniks', function (Blueprint $table) {
            $table->date('tanggal_dt')->nullable()->after('tanggal');
            $table->date('tanggal_masuk_dt')->nullable()->after('tanggal_masuk');
            $table->date('tanggal_realisasi_dt')->nullable()->after('tanggal_realisasi');
        });

        // Langkah 2: konversi data string dd/mm/yyyy → DATE
        DB::statement("
            UPDATE order_tekniks
            SET tanggal_dt = STR_TO_DATE(tanggal, '%d/%m/%Y')
            WHERE tanggal REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
        ");
        DB::statement("
            UPDATE order_tekniks
            SET tanggal_masuk_dt = STR_TO_DATE(tanggal_masuk, '%d/%m/%Y')
            WHERE tanggal_masuk REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
        ");
        DB::statement("
            UPDATE order_tekniks
            SET tanggal_realisasi_dt = STR_TO_DATE(tanggal_realisasi, '%d/%m/%Y')
            WHERE tanggal_realisasi REGEXP '^[0-9]{2}/[0-9]{2}/[0-9]{4}$'
        ");

        // Langkah 3: hapus kolom string lama
        Schema::table('order_tekniks', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'tanggal_masuk', 'tanggal_realisasi']);
        });

        // Langkah 4: rename kolom baru ke nama asli
        Schema::table('order_tekniks', function (Blueprint $table) {
            $table->renameColumn('tanggal_dt', 'tanggal');
            $table->renameColumn('tanggal_masuk_dt', 'tanggal_masuk');
            $table->renameColumn('tanggal_realisasi_dt', 'tanggal_realisasi');
        });
    }

    public function down(): void
    {
        // Rollback: konversi DATE kembali ke VARCHAR dd/mm/yyyy
        Schema::table('order_tekniks', function (Blueprint $table) {
            $table->string('tanggal_str', 10)->nullable()->after('tanggal');
            $table->string('tanggal_masuk_str', 10)->nullable()->after('tanggal_masuk');
            $table->string('tanggal_realisasi_str', 10)->nullable()->after('tanggal_realisasi');
        });

        DB::statement("UPDATE order_tekniks SET tanggal_str = DATE_FORMAT(tanggal, '%d/%m/%Y')");
        DB::statement("UPDATE order_tekniks SET tanggal_masuk_str = DATE_FORMAT(tanggal_masuk, '%d/%m/%Y')");
        DB::statement("UPDATE order_tekniks SET tanggal_realisasi_str = DATE_FORMAT(tanggal_realisasi, '%d/%m/%Y')");

        Schema::table('order_tekniks', function (Blueprint $table) {
            $table->dropColumn(['tanggal', 'tanggal_masuk', 'tanggal_realisasi']);
        });

        Schema::table('order_tekniks', function (Blueprint $table) {
            $table->renameColumn('tanggal_str', 'tanggal');
            $table->renameColumn('tanggal_masuk_str', 'tanggal_masuk');
            $table->renameColumn('tanggal_realisasi_str', 'tanggal_realisasi');
        });
    }
};
