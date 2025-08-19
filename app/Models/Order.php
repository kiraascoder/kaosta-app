<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderDistribusi; // <<< PERBAIKAN PENTING: Ganti dari 'Distribusi' menjadi 'OrderDistribusi'

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders'; // Pastikan nama tabel benar

    protected $fillable = [
        // ... semua fillable columns Anda ...
        'customer_id',
        'produk_id',
        'jenis_kain_id',
        'kode_order',
        'jasa_tambahan',
        'fee_tambahan',
        'status',
        'total_harga',
        'desain_status',
        'desain_file',
        'catatan_desain',
        'dp_nominal',
        'pelunasan_nominal',
        'metode_pembayaran',
        'metode_pelunasan',
        'bukti_pembayaran',
        'bukti_pelunasan',
        'status_pembayaran',
    ];

    // Relasi ke Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    // Relasi ke Produk
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id', 'id');
    }

    // Relasi ke Jenis Kain
    // Di Model Order
    public function jenisKain()
    {
        return $this->belongsTo(JenisKain::class, 'jenis_kain_id', 'id');
    }

    // Relasi ke Order Details (ini yang penting untuk ukuran)
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'id');
    }

    // Relasi ke OrderProduksiLog (jika Anda mengaksesnya dari Order)
    public function orderProduksiLogs()
    {
        return $this->hasMany(OrderProduksiLog::class, 'order_id', 'id');
    }

    // Relasi ke Distribusi (sekarang OrderDistribusi)
    public function distribusi()
    {
        // PERBAIKAN PENTING: Ganti dari 'Distribusi::class' menjadi 'OrderDistribusi::class'
        return $this->hasOne(OrderDistribusi::class, 'order_id', 'id');
    }
}
