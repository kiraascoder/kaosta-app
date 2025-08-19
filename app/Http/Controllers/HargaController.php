<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Harga;
use App\Models\Produk;
use App\Models\JenisKain;
use App\Models\Ukuran;

class HargaController extends Controller
{
    public function index(Request $request)
    {
        $harga = Harga::with(['produk', 'jenisKain', 'ukuran'])->orderBy('created_at', 'desc')->get();
        $produk = Produk::all();
        $jenis_kain = JenisKain::all();
        $ukuran = Ukuran::all();

        return view('harga_index', compact('harga', 'produk', 'jenis_kain', 'ukuran'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jenis_kain_id' => 'required|exists:jenis_kain,id',
            'harga_lusinan' => 'required|array',
            'harga_lusinan.*' => 'required|numeric|min:0',
            'harga_satuan' => 'nullable|array',
            'harga_satuan.*' => 'nullable|numeric|min:0',
        ]);

        foreach ($request->harga_lusinan as $ukuran_id => $harga_lusinan) {
            $harga_satuan = $request->harga_satuan[$ukuran_id] ?? null;

            Harga::updateOrCreate(
                [
                    'produk_id' => $request->produk_id,
                    'jenis_kain_id' => $request->jenis_kain_id,
                    'ukuran_id' => $ukuran_id,
                ],
                [
                    'harga' => $harga_lusinan,
                    'harga_satuan' => $harga_satuan,
                ]
            );
        }

        return redirect()->route('harga_index')->with('success', 'Harga berhasil ditambahkan atau diperbarui untuk semua ukuran.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0', // Ini untuk harga lusinan
            'harga_satuan' => 'nullable|numeric|min:0', // Ini untuk harga satuan
        ]);

        $harga = Harga::findOrFail($id);
        $harga->harga = $request->harga;
        $harga->harga_satuan = $request->harga_satuan; // Perbarui juga harga satuan
        $harga->save();

        return redirect()->route('harga_index')->with('success', 'Harga berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $harga = Harga::findOrFail($id);
        $harga->delete();

        return redirect()->route('harga_index')->with('success', 'Harga berhasil dihapus.');
    }
}
