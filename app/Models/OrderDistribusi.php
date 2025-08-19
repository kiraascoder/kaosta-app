<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderDistribusi extends Model
{

    protected $table = 'order_distribusi';

    protected $attributes = [
        'status' => 'menunggu_pelunasan',
    ];
    protected $fillable = [
        'order_id',
        'metode_pengiriman',
        'biaya_ongkir',
        'pelunasan_nominal',
        'metode_pelunasan',
        'bukti_pelunasan',
        'nomor_resi',
        'bukti_pengambilan',
        'keterangan',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
