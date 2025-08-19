<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    protected $table = 'harga';

    protected $fillable = [
        'produk_id',
        'jenis_kain_id',
        'ukuran_id',
        'harga',
        'harga_satuan', // <-- Tambahkan baris ini
    ];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function jenisKain()
    {
        return $this->belongsTo(JenisKain::class);
    }

    public function ukuran()
    {
        return $this->belongsTo(Ukuran::class);
    }
}
