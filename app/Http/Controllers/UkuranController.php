<?php

namespace App\Http\Controllers;

use App\Models\Ukuran;
use App\Models\Produk;
use App\Models\JenisKain;
use Illuminate\Http\Request;

class UkuranController extends Controller
{
    public function index(Request $request)
    {
        // Get per_page from request, default to 10
        $perPage = $request->get('per_page', 10);

        // Validate per_page value
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }

        // Get specific search parameters from the request
        $searchProdukId = $request->get('search_produk_id');
        $searchJenisKainId = $request->get('search_jenis_kain_id');
        // searchUkuranQuery tidak lagi digunakan dari view, jadi tidak perlu diambil di sini

        // Build query for ukuran with relationships
        $query = Ukuran::with(['produk', 'jenisKain']);

        // Add search functionality based on produk_id if parameter exists
        if ($searchProdukId) {
            $query->where('produk_id', $searchProdukId);
        }

        // Add search functionality based on jenis_kain_id if parameter exists
        // Pastikan jenis kain hanya difilter jika produk juga dipilih, sesuai logika di view
        if ($searchJenisKainId && $searchProdukId) {
            $query->where('jenis_kain_id', $searchJenisKainId);
        }

        // Get paginated results
        $ukuran = $query->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Preserve query parameters in pagination links
        $ukuran->appends($request->query());

        // Get other required data for forms
        $produk = Produk::orderBy('nama_produk')->get();
        // Eager load produk untuk jenis_kain agar bisa diakses di filteredJenisKain Alpine.js
        $jenis_kain = JenisKain::with('produk')->orderBy('nama_kain')->get();

        // Mengirimkan parameter pencarian kembali ke view agar dropdown terpilih
        return view('ukuran_kain_index', compact('produk', 'jenis_kain', 'ukuran', 'searchProdukId', 'searchJenisKainId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jenis_kain_id' => 'required|exists:jenis_kain,id',
            'nama_ukuran' => 'required|array|min:1',
            'nama_ukuran.*' => 'required|string|max:100',
        ], [
            'produk_id.required' => 'Produk harus dipilih.',
            'produk_id.exists' => 'Produk yang dipilih tidak valid.',
            'jenis_kain_id.required' => 'Jenis kain harus dipilih.',
            'jenis_kain_id.exists' => 'Jenis kain yang dipilih tidak valid.',
            'nama_ukuran.required' => 'Minimal satu ukuran harus diisi.',
            'nama_ukuran.*.required' => 'Nama ukuran tidak boleh kosong.',
            'nama_ukuran.*.max' => 'Nama ukuran maksimal 100 karakter.',
        ]);

        $createdCount = 0;
        $duplicateCount = 0;

        foreach ($request->nama_ukuran as $ukuran) {
            // Check for duplicate
            $exists = Ukuran::where([
                'produk_id' => $request->produk_id,
                'jenis_kain_id' => $request->jenis_kain_id,
                'nama_ukuran' => trim($ukuran),
            ])->exists();

            if (!$exists) {
                Ukuran::create([
                    'produk_id' => $request->produk_id,
                    'jenis_kain_id' => $request->jenis_kain_id,
                    'nama_ukuran' => trim($ukuran),
                ]);
                $createdCount++;
            } else {
                $duplicateCount++;
            }
        }

        $message = '';
        if ($createdCount > 0) {
            $message = "{$createdCount} ukuran berhasil ditambahkan.";
        }
        if ($duplicateCount > 0) {
            $message .= ($message ? ' ' : '') . "{$duplicateCount} ukuran sudah ada sebelumnya.";
        }

        return redirect()->back()->with('success', $message ?: 'Tidak ada data yang ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'produk_id' => 'required|exists:produk,id',
            'jenis_kain_id' => 'required|exists:jenis_kain,id',
            'nama_ukuran' => 'required|string|max:100',
        ], [
            'produk_id.required' => 'Produk harus dipilih.',
            'produk_id.exists' => 'Produk yang dipilih tidak valid.',
            'jenis_kain_id.required' => 'Jenis kain harus dipilih.',
            'jenis_kain_id.exists' => 'Jenis kain yang dipilih tidak valid.',
            'nama_ukuran.required' => 'Nama ukuran harus diisi.',
            'nama_ukuran.max' => 'Nama ukuran maksimal 100 karakter.',
        ]);

        $ukuran = Ukuran::findOrFail($id);

        // Check for duplicate (exclude current record)
        $duplicate = Ukuran::where([
            'produk_id' => $request->produk_id,
            'jenis_kain_id' => $request->jenis_kain_id,
            'nama_ukuran' => trim($request->nama_ukuran),
        ])->where('id', '!=', $id)->exists();

        if ($duplicate) {
            return redirect()->back()->with('error', 'Ukuran dengan kombinasi produk, jenis kain, dan nama yang sama sudah ada.');
        }

        $ukuran->update([
            'produk_id' => $request->produk_id,
            'jenis_kain_id' => $request->jenis_kain_id,
            'nama_ukuran' => trim($request->nama_ukuran),
        ]);

        return redirect()->route('ukuran_index')->with('success', 'Data ukuran berhasil diubah.');
    }

    public function destroy($id)
    {
        try {
            $ukuran = Ukuran::findOrFail($id);
            $ukuranName = $ukuran->nama_ukuran;
            $ukuran->delete();

            return redirect()->back()->with('success', "Ukuran '{$ukuranName}' berhasil dihapus.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data ukuran. Data mungkin sedang digunakan.');
        }
    }
}
