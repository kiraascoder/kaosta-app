<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    
    protected $fillable = [
        'nama_produk',
        'deskripsi',
        'minimal_pemesanan',
        'mendukung_lengan_panjang',
        'up_lengan_panjang',
        'mendukung_sablon',
        'up_sablon_per_pcs',
        'mendukung_bordir',
        'up_bordir_per_pcs',
        'gambar'
    ];

    protected static function booted()
    {
        static::created(function ($produk) {
            $prefix = 'PRD';
            $tanggal = now()->format('Ymd');

            $kategoriCode = match(strtolower($produk->kategori)) {
                'atasan' => 'ATS',
                'bawahan' => 'BWH',
                'seragam' => 'SET',
                default => 'XXX'
            };

            $count = Produk::whereDate('created_at', now()->toDateString())->count();
            $urut = str_pad($count, 3, '0', STR_PAD_LEFT);
            $kode = "{$prefix}-{$kategoriCode}-{$tanggal}-{$urut}";
            $produk->produk_id = $kode;
            $produk->save();
        });
    }

    public function jenisKain()
    {
        return $this->hasMany(JenisKain::class);
    }



}
