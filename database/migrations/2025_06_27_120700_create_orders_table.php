<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');


            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('jenis_kain_id')->constrained('jenis_kain')->onDelete('cascade');
            $table->foreignId('lengan_id')->nullable()->constrained('lengan')->onDelete('set null');

            $table->enum('jasa_tambahan', ['tidak', 'sablon', 'bordir'])->default('tidak');
            $table->integer('fee_tambahan')->default(0);

            $table->string('status')->default('menunggu_dp');

            $table->integer('total_harga');

            $table->enum('desain_status', ['sudah', 'belum'])->default('belum');
            $table->string('desain_file')->nullable();
            $table->text('catatan_desain')->nullable();

            $table->integer('dp_nominal')->nullable();
            $table->integer('pelunasan_nominal')->nullable();
            $table->string('metode_pembayaran')->nullable();
            $table->string('metode_pelunasan')->nullable();

            $table->string('bukti_pembayaran')->nullable();
            $table->string('bukti_pelunasan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
