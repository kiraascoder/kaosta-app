<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTable extends Migration
{
    public function up()
    {
        Schema::create('produck', function (Blueprint $table) {
            $table->id();
            $table->string('produk_id')->unique()->nullable(); // Akan diisi otomatis setelah simpan
            $table->string('nama_produk');
            $table->enum('kategori', ['Atasan', 'Bawahan', 'Seragam']);
            $table->string('gambar')->nullable(); // Path gambar
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk');
    }
}
