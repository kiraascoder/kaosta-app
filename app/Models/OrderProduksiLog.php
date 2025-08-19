<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderProduksiLog extends Model
{
use HasFactory;

protected $table = 'order_produksi_logs'; // Pastikan nama tabel benar

protected $fillable = [
'order_id',
'pekerja_id',
'tahapan',
'catatan',
'file_bukti',
'status',
'mulai',
'selesai',
];

protected $casts = [
'mulai' => 'datetime',
'selesai' => 'datetime',
];

// Relasi ke Order
public function order()
{
return $this->belongsTo(Order::class, 'order_id', 'id');
}

// Relasi ke Pekerja (yang mengerjakan log ini)
public function pekerja()
{
return $this->belongsTo(Pekerja::class, 'pekerja_id', 'id');
}
}