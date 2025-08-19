<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    
        Schema::create('pekerja', function (Blueprint $table) {
            $table->id();
            $table->string('pekerja_id')->nullable()->unique(); // ID kustom
            $table->string('nama');
            $table->enum('role', allowed: [
                'Sales',
                'Desain Grafis',
                'Pemotong Kain',
                'Penjahit',
                'Sablon',
                'Quality Control'
            ]);
            $table->string('nohp')->nullable();
            $table->string('email')->unique()->nullable();
            $table->text('alamat')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->timestamps();
        });
    
    }
    public function down(): void
    {
        Schema::dropIfExists('pekerja');
    }
};
