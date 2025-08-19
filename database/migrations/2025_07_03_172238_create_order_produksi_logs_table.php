<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('order_produksi_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('pekerja_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('tahapan', ['desain', 'gunting', 'jahit', 'bordir', 'sablon', 'qc']);
            $table->enum('status', ['menunggu', 'dikerjakan', 'menunggu_qc', 'selesai'])->default('menunggu');
            $table->text('catatan')->nullable();
            $table->string('bukti_file')->nullable(); // <- diperbaiki nama kolom
            $table->datetime('waktu_mulai')->nullable(); // <- lebih deskriptif
            $table->datetime('waktu_selesai')->nullable(); // <- lebih deskriptif
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_produksi_logs');
    }
};
