<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        $query = Produk::query();

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('nama_produk', 'like', "%{$search}%");
        }

        $produk = $query->orderBy('nama_produk')->paginate(10);

        return view('produk_index', compact('produk'));
    }

    public function create()
    {
        return view('produk_create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'gambar' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
            'minimal_pemesanan' => 'required|integer|min:1','name',
            'mendukung_lengan_panjang' => 'nullable|boolean',
            'up_lengan_panjang' => 'nullable|integer|min:0',
            'mendukung_sablon' => 'nullable|boolean',
            'mendukung_bordir' => 'nullable|boolean',
            'up_sablon_per_pcs' => 'nullable|integer|min:0',
            'up_bordir_per_pcs' => 'nullable|integer|min:0',
        ]);

        $gambar = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar')->store('produk', 'public');
        }

        $produk = Produk::create([
            'nama_produk' => $request->nama_produk,
            'gambar' => $gambar,
            'deskripsi' => $request->deskripsi,
            'minimal_pemesanan' => $request->minimal_pemesanan,
            'mendukung_lengan_panjang' => $request->boolean('mendukung_lengan_panjang'),
            'up_lengan_panjang' => $request->up_lengan_panjang ?? 0,
            'mendukung_sablon' => $request->boolean('mendukung_sablon'),
            'up_sablon_per_pcs' => $request->up_sablon_per_pcs ?? 0,
            'mendukung_bordir' => $request->boolean('mendukung_bordir'),
            'up_bordir_per_pcs' => $request->up_bordir_per_pcs ?? 0,
        ]);

        $produk->produk_id = 'PRD-' . now()->format('Ymd') . '-' . str_pad($produk->id, 3, '0', STR_PAD_LEFT);
        $produk->save();

        return redirect()->route('produk_index')->with('success', 'Produk berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $produk = Produk::findOrFail($id);
        return view('produk_edit', compact('produk'));
    }

    public function update(Request $request, $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama_produk' => 'required|string|max:255',
            'gambar' => 'nullable|image|max:2048',
            'deskripsi' => 'nullable|string',
            'minimal_pemesanan' => 'required|integer|min:1',
            'mendukung_lengan_panjang' => 'nullable|boolean',
            'up_lengan_panjang' => 'nullable|integer|min:0',
            'mendukung_sablon' => 'nullable|boolean',
            'up_sablon_per_pcs' => 'nullable|integer|min:0',
            'mendukung_bordir' => 'nullable|boolean',
            'up_bordir_per_pcs' => 'nullable|integer|min:0',
        ]);

        if ($request->hasFile('gambar')) {
            if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $produk->gambar = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update([
            'nama_produk' => $request->nama_produk,
            'deskripsi' => $request->deskripsi,
            'minimal_pemesanan' => $request->minimal_pemesanan,
            'mendukung_lengan_panjang' => $request->boolean('mendukung_lengan_panjang'),
            'up_lengan_panjang' => $request->up_lengan_panjang ?? 0,
            'mendukung_sablon' => $request->boolean('mendukung_sablon'),
            'up_sablon_per_pcs' => $request->up_sablon_per_pcs ?? 0,
            'mendukung_bordir' => $request->boolean('mendukung_bordir'),
            'up_bordir_per_pcs' => $request->up_bordir_per_pcs ?? 0,
        ]);

        return redirect()->route('produk_index')->with('success', 'Produk berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $produk = Produk::findOrFail($id);

        if ($produk->gambar && Storage::disk('public')->exists($produk->gambar)) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk_index')->with('success', 'Produk berhasil dihapus!');
    }
}
