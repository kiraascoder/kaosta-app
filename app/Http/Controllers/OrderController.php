<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Produk;
use App\Models\Pekerja;
use App\Models\OrderProduksiLog;
use App\Models\User;
use App\Notifications\PekerjaanBaru;
use Illuminate\Support\Facades\Notification;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use App\Events\PekerjaanBaruEvent;
use App\Helpers\FCMHelperV1;

class OrderController extends Controller
{
    public function create()
    {
        $customers = \App\Models\Customer::all();
        $produk = Produk::with('jenisKain')->get();

        $hargaList = \App\Models\Harga::with('ukuran')->get();

        return view('order_create', compact('customers', 'produk', 'hargaList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'produk_id' => 'required|exists:produk,id',
            'jeniskain_id' => 'required|exists:jenis_kain,id',
            'ukuran_detail' => 'required|array|min:1',
            'ukuran_detail.*.ukuran_id' => 'required|exists:ukuran,id',
            'ukuran_detail.*.jumlah' => 'required|integer|min:1',
            'ukuran_detail.*.harga' => 'required|numeric|min:0',
            'desain_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'bukti_pembayaran' => 'nullable|file|mimes:jpg,jpeg,png,pdf',
            'desain_status' => 'required|in:sudah,belum',
            'jasa_tambahan' => 'nullable|string',
            'fee_tambahan' => 'nullable|numeric',
            'total_harga' => 'required|numeric',
            'dp_nominal' => 'required|numeric',
            'metode_pembayaran' => 'nullable|string',
            'catatan_desain' => 'nullable|string',
        ]);

        $desainPath = $request->hasFile('desain_file')
            ? $request->file('desain_file')->store('desain', 'public')
            : null;

        $buktiPath = $request->hasFile('bukti_pembayaran')
            ? $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public')
            : null;

        $statusPembayaran = $buktiPath ? 'Lunas' : 'Belum Lunas';

        return DB::transaction(function () use ($request, $desainPath, $buktiPath, $statusPembayaran) {
            $order = Order::create([
                'kode_order' => 'temp',
                'customer_id' => $request->customer_id,
                'produk_id' => $request->produk_id,
                'jenis_kain_id' => $request->jeniskain_id,
                'jasa_tambahan' => $request->jasa_tambahan,
                'fee_tambahan' => $request->fee_tambahan,
                'total_harga' => $request->total_harga,
                'desain_status' => $request->desain_status,
                'desain_file' => $desainPath,
                'catatan_desain' => $request->catatan_desain,
                'dp_nominal' => $request->dp_nominal,
                'metode_pembayaran' => $request->metode_pembayaran,
                'bukti_pembayaran' => $buktiPath,
                'status_pembayaran' => $statusPembayaran,
                'status' => 'Baru',
            ]);

            $orderId = $order->id;
            $tanggalHariIni = now()->format('Ymd');
            $nomorUrut = str_pad($orderId, 3, '0', STR_PAD_LEFT);
            $kodeOrder = 'ORD-' . $tanggalHariIni . '-' . $nomorUrut;

            $order->update(['kode_order' => $kodeOrder]);

            foreach ($request->ukuran_detail as $detail) {
                OrderDetail::create([
                    'order_id' => $orderId,
                    'ukuran_id' => $detail['ukuran_id'],
                    'jumlah' => $detail['jumlah'],
                    'harga_satuan' => $detail['harga'],
                    'subtotal' => $detail['harga'] * $detail['jumlah'],
                    'lengan_panjang' => $detail['lengan_panjang'] ?? 0,
                ]);
            }

            $tahapanList = ['desain', 'potong', 'jahit', 'sablon', 'qc'];

            foreach ($tahapanList as $tahapan) {
                OrderProduksiLog::create([
                    'order_id' => $orderId,
                    'pekerja_id' => null,
                    'tahapan' => $tahapan,
                    'status' => ($statusPembayaran === 'Lunas' && $tahapan === 'desain') ? 'menunggu' : 'belum_dikerjakan',
                ]);
            }

            return redirect()->route('order_index')->with('success', 'Pesanan berhasil disimpan.');
        });
    }

    public function index()
    {
        $orders = Order::with(['customer', 'produk', 'jenisKain', 'orderProduksiLogs', 'distribusi'])->latest()->get();
        return view('order_index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with([
            'customer',
            'produk',
            'jenisKain',
            'orderDetails.ukuran',
            'orderProduksiLogs.pekerja',
            'distribusi'
        ])->findOrFail($id);

        $pekerjaList = User::whereIn('role', ['Desain Grafis', 'Pemotong Kain', 'Penjahit', 'sablon', 'Quality Control'])->get();

        return view('order_show', compact('order', 'pekerjaList'));
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->orderDetails()->delete();
        $order->orderProduksiLogs()->delete();
        $order->delete();

        return redirect()->route('order_index')->with('success', 'Order berhasil dihapus.');
    }

    public function uploadDP(Request $request, Order $order)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');

        $order->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'Lunas',
        ]);

        $logDesain = OrderProduksiLog::where('order_id', $order->id)
            ->where('tahapan', 'desain')
            ->first();

        if ($logDesain && $logDesain->status === 'belum_dikerjakan') {
            $logDesain->update(['status' => 'menunggu']);

            $pekerjaDesain = User::where('role', 'Desain Grafis')->whereNotNull('fcm_token')->get();
            foreach ($pekerjaDesain as $pekerja) {
                FCMHelperV1::send(
                    $pekerja->fcm_token,
                    'Pekerjaan Desain Baru',
                    "Order {$order->kode_order} siap dikerjakan!",
                    route('dashboard_pekerja')
                );
            }
        }


        return back()->with('success', 'Bukti pembayaran DP berhasil diunggah dan status DP telah diperbarui.');
    }
}
