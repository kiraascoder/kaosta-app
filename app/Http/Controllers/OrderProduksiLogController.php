<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\OrderProduksiLog;
use App\Models\Order;
use App\Models\Pekerja;
use Illuminate\Support\Facades\Storage;

class OrderProduksiLogController extends Controller
{
    public function create($orderId)
    {
        $order = Order::with('produk')->findOrFail($orderId);
        $pekerjaList = Pekerja::all(); // nanti disesuaikan dengan login pekerja

        return view('produksi_log_create', compact('order', 'pekerjaList'));
    }

    public function store(Request $request, $orderId)
    {
        $request->validate([
            'tahapan' => 'required|in:desain,gunting,jahit,bordir,sablon,qc',
            'pekerja_id' => 'required|exists:pekerja,id',
            'status' => 'required|string|max:20',
            'catatan' => 'nullable|string',
            'bukti_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'waktu_mulai' => 'required|date',
            'waktu_selesai' => 'nullable|date|after_or_equal:waktu_mulai',
        ]);

        $log = new OrderProduksiLog();
        $log->order_id = $orderId;
        $log->tahapan = $request->tahapan;
        $log->pekerja_id = $request->pekerja_id;
        $log->status = $request->status;
        $log->catatan = $request->catatan;
        $log->waktu_mulai = $request->waktu_mulai;
        $log->waktu_selesai = $request->waktu_selesai;

        if ($request->hasFile('bukti_file')) {
            $log->bukti_file = $request->file('bukti_file')->store('produksi', 'public');
        }

        $log->save();

        return redirect()->route('order_show', $orderId)->with('success', 'Log produksi ditambahkan.');
    }
}
