<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukVarianTable extends Migration
{
    public function up()
    {
        Schema::create('produk_varian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produk_id')->constrained('produck')->onDelete('cascade');
            $table->enum('ukuran', ['S', 'M', 'L', 'XL', 'XXL']);
            $table->integer('harga_satuan');
            $table->integer('harga_borongan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('produk_varian');
    }
}

