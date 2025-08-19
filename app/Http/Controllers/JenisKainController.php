<?php

namespace App\Http\Controllers;

use App\Models\JenisKain;
use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Storage; // Tambahkan ini

class JenisKainController extends Controller
{
    public function index()
    {
        // Menggunakan paginate untuk daftar jenis kain agar lebih efisien
        $jenis_kain = JenisKain::with('produk')->paginate(10); // Ambil 10 item per halaman dan eager load relasi produk
        $produk = Produk::all(); // ambil produk juga untuk form modal

        return view('jenis_kain_index', compact('jenis_kain', 'produk'));
    }

    public function create()
    {
        $produk = Produk::all();
        return view('jenis_kain_create', compact('produk'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'nama_kain' => 'required|array',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')->store('jenis_kain_images', 'public');
        }

        foreach ($request->nama_kain as $namaKain) {
            JenisKain::create([
                'produk_id' => $validatedData['produk_id'],
                'nama_kain' => $namaKain,
                'gambar' => $gambarPath,
            ]);
        }

        return redirect()->route('jenis_kain_index')->with('success', 'Beberapa jenis kain berhasil ditambahkan.');
    }



    public function update(Request $request, $id)
    {
        $jenisKain = JenisKain::findOrFail($id); // Mengubah $kain menjadi $jenisKain agar konsisten

        $validatedData = $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'nama_kain' => 'required|string|max:255',
            // 'harga' => 'nullable|integer', // Jika kolom harga ada di tabel jenis_kain
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240', // Diubah menjadi 10MB (10240 KB)
        ]);

        // Tangani upload gambar
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($jenisKain->gambar) {
                Storage::disk('public')->delete($jenisKain->gambar);
            }
            // Simpan gambar baru
            $path = $request->file('gambar')->store('jenis_kain_images', 'public');
            $validatedData['gambar'] = $path;
        } else {
            // Jika tidak ada gambar baru diupload, pertahankan gambar lama
            $validatedData['gambar'] = $jenisKain->gambar; // Pertahankan gambar yang sudah ada
        }

        $jenisKain->update($validatedData);

        return redirect()->route('jenis_kain_index')->with('success', 'Jenis kain berhasil diubah.');
    }

    public function destroy($id)
    {
        $jenisKain = JenisKain::findOrFail($id); // Mengubah $kain menjadi $jenisKain agar konsisten

        // Hapus file gambar dari storage jika ada
        if ($jenisKain->gambar) {
            Storage::disk('public')->delete($jenisKain->gambar);
        }

        $jenisKain->delete();

        return redirect()->route('jenis_kain_index')->with('success', 'Jenis kain berhasil dihapus.');
    }
}
