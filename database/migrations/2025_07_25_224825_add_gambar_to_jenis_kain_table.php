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
        Schema::table('jenis_kain', function (Blueprint $table) {
            // Menambahkan kolom 'gambar' setelah 'nama_kain', bisa null
            $table->string('gambar')->nullable()->after('nama_kain');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jenis_kain', function (Blueprint $table) {
            // Menghapus kolom 'gambar' jika migrasi di-rollback
            $table->dropColumn('gambar');
        });
    }
};
