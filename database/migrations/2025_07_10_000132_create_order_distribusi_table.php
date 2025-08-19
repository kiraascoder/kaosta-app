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
        Schema::create('order_distribusi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->enum('metode_pengiriman', ['ambil', 'antar']);
            $table->text('keterangan')->nullable(); // untuk catatan atau lokasi ambil
            $table->string('bukti_pengambilan')->nullable(); // file upload
            $table->string('nomor_resi')->nullable(); // hanya jika 'antar'
            $table->decimal('biaya_ongkir', 10, 2)->default(0);
            $table->decimal('pelunasan_nominal', 10, 2);
            $table->string('metode_pelunasan')->nullable(); // e.g. transfer, tunai
            $table->string('bukti_pelunasan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_distribusi');
    }
};
