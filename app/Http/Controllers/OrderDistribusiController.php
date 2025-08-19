<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDistribusi;
use Illuminate\Http\Request;

class OrderDistribusiController extends Controller
{
    // Daftar semua distribusi order
    public function index()
    {
        $distribusiOrders = OrderDistribusi::with('order.customer')->latest()->get();
        return view('order_distribusi', compact('distribusiOrders'));
    }

    // Form input distribusi (manual dari order tertentu)
    public function create(Order $order)
    {
        return view('order_distribusi_form', compact('order'));
    }

    // Simpan distribusi dari form input manual
    public function store(Request $request, Order $order)
    {
        $request->validate([
            'metode_pengiriman' => 'required|in:antar,ambil',
            'biaya_ongkir' => 'nullable|numeric|min:0',
            'metode_pelunasan' => 'nullable|string',
            'bukti_pelunasan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'nomor_resi' => 'nullable|string',
            'bukti_pengambilan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'keterangan' => 'nullable|string',
        ]);

        $biaya_ongkir = $request->biaya_ongkir ?? 0;

        $pelunasan = $order->total_harga - $order->dp_nominal + $biaya_ongkir;

        $buktiPelunasanPath = $request->file('bukti_pelunasan')?->store('pelunasan', 'public');
        $buktiPengambilanPath = $request->file('bukti_pengambilan')?->store('pengambilan', 'public');

        OrderDistribusi::create([
            'order_id' => $order->id,
            'metode_pengiriman' => $request->metode_pengiriman,
            'biaya_ongkir' => $biaya_ongkir,
            'pelunasan_nominal' => $pelunasan,
            'metode_pelunasan' => $request->metode_pelunasan,
            'bukti_pelunasan' => $buktiPelunasanPath,
            'nomor_resi' => $request->nomor_resi,
            'bukti_pengambilan' => $buktiPengambilanPath,
            'keterangan' => $request->keterangan,
            'status' => 'selesai', // atau sesuai logikamu
        ]);

        $order->update(['status' => 'selesai']);

        return redirect()->route('order_index')->with('success', 'Distribusi & pelunasan berhasil dicatat.');
    }

    // Kirim distribusi otomatis dari sistem (setelah tahapan produksi selesai)
    public function kirimDistribusi($id)
    {
        $order = Order::with('orderProduksiLogs')->findOrFail($id);

        // Validasi tahapan selesai
        $semuaTahapanSelesai = $order->orderProduksiLogs->every(fn($log) => $log->status === 'selesai');

        if (!$semuaTahapanSelesai) {
            return back()->with('error', 'Masih ada tahapan produksi yang belum selesai.');
        }

        // Cegah duplikasi distribusi
        if ($order->distribusi) {
            return back()->with('error', 'Order ini sudah dikirim ke distribusi.');
        }

        // Kirim distribusi awal tanpa pelunasan
        OrderDistribusi::create([
            'order_id' => $order->id,
            'status' => 'menunggu_pelunasan',
            'pelunasan_nominal' => 0,
        ]);

        $order->update(['status' => 'menunggu_pelunasan']);

        return redirect()->route('orderdistribusi_index')->with('success', 'Order berhasil dikirim ke distribusi.');
    }

    // Edit distribusi setelah dikirim
    public function edit($id)
    {
        $distribusi = OrderDistribusi::with('order.customer')->findOrFail($id);
        return view('order_distribusi_edit', compact('distribusi'));
    }

    // Update distribusi (lengkapi)
    public function update(Request $request, $id)
    {
        $distribusi = OrderDistribusi::findOrFail($id);

        $request->validate([
            'metode_pengiriman' => 'required|in:antar,ambil',
            'biaya_ongkir' => 'nullable|numeric|min:0',
            'pelunasan_nominal' => 'required|numeric|min:0',
            'bukti_pelunasan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'nomor_resi' => 'nullable|string',
            'keterangan' => 'nullable|string',
        ]);

        if ($request->hasFile('bukti_pengambilan')) {
            $distribusi->bukti_pengambilan = $request->file('bukti_pengambilan')->store('pengambilan', 'public');
        }

        $distribusi->update([
            'metode_pengiriman' => $request->metode_pengiriman,
            'biaya_ongkir' => $request->biaya_ongkir ?? 0,
            'pelunasan_nominal' => $request->pelunasan_nominal,
            'nomor_resi' => $request->nomor_resi,
            'keterangan' => $request->keterangan,
            'status' => 'selesai',
        ]);

        $distribusi->order->update(['status' => 'selesai']);

        return redirect()->route('orderdistribusi_index')->with('success', 'Data distribusi berhasil diperbarui.');
    }


    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pelunasan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'bukti_pengambilan' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $distribusi = OrderDistribusi::findOrFail($id);

        if ($request->hasFile('bukti_pelunasan')) {
            $distribusi->bukti_pelunasan = $request->file('bukti_pelunasan')->store('pelunasan', 'public');
        }

        if ($request->hasFile('bukti_pengambilan')) {
            $distribusi->bukti_pengambilan = $request->file('bukti_pengambilan')->store('pengambilan', 'public');
        }

        $distribusi->status = 'selesai';
        $distribusi->save();

        $distribusi->order->update(['status' => 'selesai']);

        return back()->with('success', 'Bukti berhasil diupload.');
    }
}
