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
            $table->dropForeign(['pekerja_id']);
            $table->foreign('pekerja_id')->references('id')->on('pekerja')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('order_produksi_logs', function (Blueprint $table) {
            $table->dropForeign(['pekerja_id']);
            $table->foreign('pekerja_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
