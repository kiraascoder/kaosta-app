<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
use HasFactory;

protected $table = 'order_details'; // Pastikan nama tabel benar

protected $fillable = [
'order_id',
'ukuran_id',
'jumlah',
'harga_satuan',
'subtotal',
'lengan_panjang',
];

// Relasi ke Ukuran
public function ukuran()
{
return $this->belongsTo(Ukuran::class, 'ukuran_id', 'id');
}

// Relasi kembali ke Order (jika diperlukan)
public function order()
{
return $this->belongsTo(Order::class, 'order_id', 'id');
}
}