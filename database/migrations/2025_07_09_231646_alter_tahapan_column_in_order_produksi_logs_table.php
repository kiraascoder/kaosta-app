<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('order_produksi_logs', function (Blueprint $table) {
            $table->string('tahapan', 50)->change(); // ubah dari misal varchar(10) jadi varchar(50)
        });
    }

    public function down()
    {
        Schema::table('order_produksi_logs', function (Blueprint $table) {
            $table->string('tahapan', 10)->change(); // balik ke sebelumnya
        });
    }
};
