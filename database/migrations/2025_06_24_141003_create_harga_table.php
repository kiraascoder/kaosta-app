<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('harga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('jenis_kain_id')->constrained('jenis_kain')->onDelete('cascade');
            $table->foreignId('ukuran_id')->constrained('ukuran')->onDelete('cascade');
            $table->unsignedInteger('harga');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga');
    }
};
