<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('ukuran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produk')->onDelete('cascade');
            $table->foreignId('jenis_kain_id')->constrained('jenis_kain')->onDelete('cascade');
            $table->string('nama_ukuran');
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('ukuran');
    }
};
