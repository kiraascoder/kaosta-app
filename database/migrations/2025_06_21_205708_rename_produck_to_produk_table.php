<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameProduckToProdukTable extends Migration
{
    public function up()
    {
        Schema::rename('produck', 'produk');
    }

    public function down()
    {
        Schema::rename('produk', 'produck');
    }
}

