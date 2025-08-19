<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ukuran extends Model
{
use HasFactory;

protected $table = 'ukuran';

protected $fillable = [
'produk_id',
'jenis_kain_id',
'nama_ukuran',
];

// Relasi ke Produk
public function produk()
{
return $this->belongsTo(Produk::class, 'produk_id', 'id');
}

// Relasi ke Jenis Kain
public function jenisKain()
{
return $this->belongsTo(JenisKain::class, 'jenis_kain_id', 'id');
}
}