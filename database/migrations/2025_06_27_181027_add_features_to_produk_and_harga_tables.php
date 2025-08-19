<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Tambahan kolom di tabel produk
        Schema::table('produk', function (Blueprint $table) {
            $table->boolean('mendukung_lengan_panjang')->default(false);
            $table->boolean('mendukung_sablon')->default(false);
            $table->boolean('mendukung_bordir')->default(false);
            $table->integer('minimal_pemesanan')->default(1);
            $table->integer('up_sablon_per_pcs')->default(0);
        });

        // Tambahan kolom di tabel harga (untuk lengan panjang)
        Schema::table('harga', function (Blueprint $table) {
            $table->integer('harga_lengan_panjang')->nullable();
        });
    }

    public function down(): void
    {
        // Rollback
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn([
                'mendukung_lengan_panjang',
                'mendukung_sablon',
                'mendukung_bordir',
                'minimal_pemesanan',
                'up_sablon_per_pcs',
            ]);
        });

        Schema::table('harga', function (Blueprint $table) {
            $table->dropColumn('harga_lengan_panjang');
        });
    }
};

