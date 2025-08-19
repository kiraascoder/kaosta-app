<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpBordirPerPcsToProdukTable extends Migration
{
    public function up()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->decimal('up_bordir_per_pcs', 10, 2)->nullable()->after('up_sablon_per_pcs');
        });
    }

    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            $table->dropColumn('up_bordir_per_pcs');
        });
    }
}
