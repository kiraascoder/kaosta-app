<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisKain extends Model
{   
    protected $fillable = ['produk_id', 'nama_kain', 'gambar',];
    protected $table = 'jenis_kain';

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

}
