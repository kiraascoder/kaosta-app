<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTableAddCustomerRelation extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {

            // Hapus kolom lama
            $table->dropColumn(['nama_customer', 'nomor_telepon', 'alamat', 'lengan_id']);

            // Tambahkan relasi ke tabel customers
            $table->foreignId('customer_id')->after('id')->constrained('customers')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'nama_customer')) {
                $table->string('nama_customer')->nullable();
            }

            if (!Schema::hasColumn('orders', 'nomor_telepon')) {
                $table->string('nomor_telepon')->nullable();
            }

            if (!Schema::hasColumn('orders', 'alamat')) {
                $table->text('alamat')->nullable();
            }

            // Hapus foreign key dan kolom customer_id jika ada
            if (Schema::hasColumn('orders', 'customer_id')) {
                $table->dropForeign(['customer_id']);
                $table->dropColumn('customer_id');
            }
        });
    }
}
