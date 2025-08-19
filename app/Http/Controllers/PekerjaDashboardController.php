<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderProduksiLog;
use App\Models\Order;
use App\Models\Pekerja;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Helpers\FCMHelperV1; // ✅ Tambahkan ini

class PekerjaDashboardController extends Controller
{
    private array $tahapanProduksi = [
        'desain',
        'potong',
        'jahit',
        'sablon',
        'qc',
        'distribusi',
    ];

    public function index()
    {
        $user = Auth::user();
        $pekerja = Pekerja::where('user_id', $user->id)->first();

        if (!$pekerja) {
            abort(404, 'Data profil pekerja Anda tidak ditemukan. Silakan hubungi administrator.');
        }

        $tahapanPekerja = $this->mapRoleToTahapan($user->role);
        if (!$tahapanPekerja) {
            return redirect()->route('dashboard')->with('error', 'Role Anda tidak memiliki tahapan produksi yang valid.');
        }

        $tugasSaya = OrderProduksiLog::with(['order', 'order.produk', 'order.jenisKain', 'order.orderDetails.ukuran'])
            ->where('pekerja_id', $pekerja->id)
            ->where('status', 'dikerjakan')
            ->orderBy('created_at', 'asc')
            ->get();

        $tugasSelesai = OrderProduksiLog::with(['order', 'order.produk', 'order.jenisKain', 'order.orderDetails.ukuran'])
            ->where('pekerja_id', $pekerja->id)
            ->where('status', 'selesai')
            ->orderBy('created_at', 'desc')
            ->get();

        $tugasTersedia = OrderProduksiLog::with(['order.orderDetails.ukuran', 'order.produk', 'order.jenisKain'])
            ->whereNull('pekerja_id')
            ->where('tahapan', $tahapanPekerja)
            ->where('status', 'menunggu')
            ->orderBy('created_at', 'asc')
            ->get();

        $tugasYangBenarTersedia = collect();
        foreach ($tugasTersedia as $log) {
            if ($this->apakahTahapanSebelumnyaSudahSelesai($log->order_id, $tahapanPekerja)) {
                $tugasYangBenarTersedia->push($log);
            }
        }
        $tugasTersedia = $tugasYangBenarTersedia;

        return view('dashboard_pekerja', compact('tugasSaya', 'tugasTersedia', 'tugasSelesai', 'pekerja'));
    }

    public function terima(Request $request, $id)
    {
        $user = auth()->user();
        $pekerja = $user->pekerja;

        if (!$pekerja) {
            return back()->with('error', 'Data pekerja Anda tidak ditemukan.');
        }

        $log = OrderProduksiLog::findOrFail($id);
        $tahapanPekerja = $this->mapRoleToTahapan($user->role);

        if ($log->pekerja_id !== null) {
            return back()->with('error', 'Tugas sudah diambil pekerja lain.');
        }

        if ($log->tahapan !== $tahapanPekerja) {
            abort(403, 'Tugas tidak sesuai dengan tahapan kerja Anda.');
        }

        if (!$this->apakahTahapanSebelumnyaSudahSelesai($log->order_id, $tahapanPekerja)) {
            return back()->with('error', 'Tahapan sebelumnya belum diselesaikan.');
        }

        $log->update([
            'pekerja_id' => $pekerja->id,
            'status' => 'dikerjakan',
            'mulai' => now(),
        ]);

        return redirect()->route('dashboard_pekerja')->with('success', 'Tugas berhasil diambil dan sedang dikerjakan.');
    }

    public function selesai(Request $request, $id)
    {
        $validated = $request->validate([
            'catatan' => 'nullable|string',
            'file_bukti' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $user = auth()->user();
        $pekerja = $user->pekerja;

        if (!$pekerja) {
            return back()->with('error', 'Data pekerja Anda tidak ditemukan.');
        }

        $log = OrderProduksiLog::findOrFail($id);

        if ($log->pekerja_id !== $pekerja->id) {
            abort(403, 'Tugas ini bukan milik Anda.');
        }

        $filePath = $log->file_bukti;
        if ($request->hasFile('file_bukti')) {
            if ($log->file_bukti) {
                Storage::disk('public')->delete($log->file_bukti);
            }
            $filePath = $request->file('file_bukti')->store('hasil-produksi', 'public');
        }

        $log->update([
            'status' => 'selesai',
            'selesai' => now(),
            'catatan' => $validated['catatan'] ?? null,
            'file_bukti' => $filePath,
        ]);

        $tahapanSaatIniIndex = array_search($log->tahapan, $this->tahapanProduksi);
        $tahapanSaatIniIndex = array_search($log->tahapan, $this->tahapanProduksi);
        $tahapanSelanjutnyaIndex = $tahapanSaatIniIndex + 1;

        if (isset($this->tahapanProduksi[$tahapanSelanjutnyaIndex])) {
            $tahapanSelanjutnya = $this->tahapanProduksi[$tahapanSelanjutnyaIndex];

            $logSelanjutnya = OrderProduksiLog::where('order_id', $log->order_id)
                ->where('tahapan', $tahapanSelanjutnya)
                ->first();

            if ($logSelanjutnya) {
                $logSelanjutnya->update(['status' => 'menunggu']);

                $roleSelanjutnya = match ($tahapanSelanjutnya) {
                    'potong' => 'Pemotong Kain',
                    'jahit' => 'Penjahit',
                    'sablon' => 'Sablon',
                    'qc' => 'Quality Control',
                    'distribusi' => 'Sales',
                    default => null
                };

                // Perbaikan ada di sini
                if ($roleSelanjutnya) {
                    // ✅ Gunakan whereRaw untuk query case-insensitive
                    $users = User::whereRaw('LOWER(role) = ?', [strtolower(trim($roleSelanjutnya))])
                        ->whereNotNull('fcm_token')
                        ->get();

                    if ($users->isNotEmpty()) {
                        foreach ($users as $u) {
                            FCMHelperV1::send(
                                $u->fcm_token,
                                'Pekerjaan Baru',
                                "Order {$log->order->kode_order} siap dikerjakan!",
                                route('dashboard_pekerja')
                            );
                        }
                    }
                }
            }
        }

        return redirect()->route('dashboard_pekerja')->with('success', 'Tugas berhasil diselesaikan.');
    }

    private function apakahTahapanSebelumnyaSudahSelesai(int $orderId, string $tahapanPekerja): bool
    {
        $currentIndex = array_search($tahapanPekerja, $this->tahapanProduksi);

        if ($currentIndex === 0) {
            $order = Order::findOrFail($orderId);
            return $order->status_pembayaran === 'Lunas';
        }

        $tahapanSebelumnya = $this->tahapanProduksi[$currentIndex - 1];

        $logSebelumnya = OrderProduksiLog::where('order_id', $orderId)
            ->where('tahapan', $tahapanSebelumnya)
            ->first();

        return $logSebelumnya && $logSebelumnya->status === 'selesai';
    }

    private function mapRoleToTahapan(string $role): ?string
    {
        return match ($role) {
            'Desain Grafis' => 'desain',
            'Pemotong Kain' => 'potong',
            'Penjahit' => 'jahit',
            'Sablon' => 'sablon',
            'Quality Control' => 'qc',
            'Sales' => 'sales',
            default => null,
        };
    }
}
