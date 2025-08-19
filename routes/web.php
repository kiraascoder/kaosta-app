<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PekerjaController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\JenisKainController;
use App\Http\Controllers\UkuranController;
use App\Http\Controllers\HargaController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderProduksiLogController;
use App\Http\Controllers\PekerjaDashboardController;
use App\Http\Controllers\OrderDistribusiController;
use Illuminate\Http\Request;






// Redirect root URL to login page
Route::redirect('/', '/login');


// Mengimpor rute otentikasi Laravel (login, register, logout, dll.)
require __DIR__ . '/auth.php';

Route::post('/save-fcm-token', function (Illuminate\Http\Request $request) {
    $request->validate(['token' => 'required']);
    if (auth()->check()) {
        auth()->user()->update(['fcm_token' => $request->token]);
        return response()->json(['success' => true]);
    }
    return response()->json(['error' => 'User not logged in'], 401);
});

// Rute untuk Halaman Statis atau Umum
Route::get('/kaosta', fn() => view('beranda'));
Route::get('/pemesanan', fn() => view('pemesanan'))->name('pemesanan');

// Dashboard utama yang akan memeriksa peran pengguna
Route::get('/dashboard', function () {
    // Memeriksa peran pengguna yang sedang login
    if (auth()->user()->role === 'Pekerja') {
        // Jika peran adalah 'Pekerja', alihkan ke dashboard pekerja
        return redirect()->route('dashboard_pekerja');
    }

    // Jika peran bukan 'Pekerja', anggap saja itu adalah Admin atau Sales
    // dan tampilkan tampilan dashboard admin/sales
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// =====================================================================================
// Rute Khusus Admin & Sales
// =====================================================================================
// Gunakan alias middleware 'isadmin' untuk melindungi rute ini
Route::middleware(['auth', 'isadmin'])->group(function () {
    // Pekerja
    Route::get('/pekerja', [PekerjaController::class, 'index'])->name('pekerja_index');
    Route::post('/pekerja', [PekerjaController::class, 'store'])->name('pekerja_store');
    Route::put('/pekerja/{id}', [PekerjaController::class, 'update'])->name('pekerja_update');
    Route::delete('/pekerja/{id}', [PekerjaController::class, 'destroy'])->name('pekerja_destroy');

    // Produk
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk_index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk_create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk_store');
    Route::get('/produk/{id}/edit', [ProdukController::class, 'edit'])->name('produk_edit');
    Route::put('/produk/{id}', [ProdukController::class, 'update'])->name('produk_update');
    Route::delete('/produk/{id}', [ProdukController::class, 'destroy'])->name('produk_destroy');

    // Jenis Kain
    Route::get('/produk/jeniskain', [JenisKainController::class, 'index'])->name('jenis_kain_index');
    Route::post('/jeniskain/store', [JenisKainController::class, 'store'])->name('jenis_kain_store');
    Route::put('/jeniskain/{id}', [JenisKainController::class, 'update'])->name('jenis_kain_update');
    Route::delete('/jeniskain/{id}', [JenisKainController::class, 'destroy'])->name('jenis_kain_destroy');

    // Ukuran
    Route::get('/ukuran', [UkuranController::class, 'index'])->name('ukuran_index');
    Route::post('/ukuran/store', [UkuranController::class, 'store'])->name('ukuran_store');
    Route::put('/ukuran/{id}', [UkuranController::class, 'update'])->name('ukuran_update');
    Route::delete('/ukuran/{id}', [UkuranController::class, 'destroy'])->name('ukuran_destroy');

    // Harga
    Route::get('/harga', [HargaController::class, 'index'])->name('harga_index');
    Route::post('/harga', [HargaController::class, 'store'])->name('harga_store');
    Route::put('/harga/{id}', [HargaController::class, 'update'])->name('harga_update');
    Route::delete('/harga/{id}', [HargaController::class, 'destroy'])->name('harga_destroy');

    // Order
    Route::get('/order', [OrderController::class, 'index'])->name('order_index');
    Route::get('/order/create', [OrderController::class, 'create'])->name('order_create');
    Route::post('/order/store', [OrderController::class, 'store'])->name('order_store');
    Route::get('/order/{order}', [OrderController::class, 'show'])->name('order_show');
    Route::delete('/order/{id}', [OrderController::class, 'destroy'])->name('order_destroy');
    Route::post('/order/{order}/upload-dp', [OrderController::class, 'uploadDP'])->name('orderupload_dp');
    Route::post('/order/{id}/assign-pekerja', [OrderController::class, 'assignPekerja'])->name('order_assignPekerja');

    // Distribusi
    Route::get('/distribusi', [OrderDistribusiController::class, 'index'])->name('orderdistribusi_index');
    Route::get('/order/{order}/distribusi', [OrderDistribusiController::class, 'create'])->name('orderdistribusi_create');
    Route::post('/order/{order}/distribusi', [OrderDistribusiController::class, 'store'])->name('orderdistribusi_store');
    Route::post('/order/{id}/kirim-distribusi', [OrderDistribusiController::class, 'kirimDistribusi'])->name('orderdistribusi_kirim');
    Route::get('/distribusi/{id}/edit', [OrderDistribusiController::class, 'edit'])->name('orderdistribusi_edit');
    Route::put('/distribusi/{id}', [OrderDistribusiController::class, 'update'])->name('orderdistribusi_update');
    Route::post('/order-distribusi/{id}/upload-bukti', [OrderDistribusiController::class, 'uploadBukti'])->name('orderdistribusi_upload_bukti');

    // Produksi Log
    Route::get('/order/{order}/produksi-log/create', [OrderProduksiLogController::class, 'create'])->name('produksi_log_create');
    Route::post('/order/{order}/produksi-log/store', [OrderProduksiLogController::class, 'store'])->name('produksi_log_store');
    Route::post('/order/produksi/assign', [OrderProduksiLogController::class, 'assign'])->name('order_produksi_assign');

    // Customer
    Route::get('/customer', [CustomerController::class, 'index'])->name('customer_index');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customer_store');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customer_update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customer_destroy');
});


// =====================================================================================
// Rute Khusus Pekerja
// =====================================================================================
// Gunakan alias middleware 'ispekerja' untuk melindungi rute ini
Route::middleware(['auth', 'ispekerja'])->group(function () {
    // Dashboard Pekerja
    Route::get('/pekerja/dashboard', [PekerjaDashboardController::class, 'index'])->name('dashboard_pekerja');

    // Rute untuk menerima tugas. Ubah method-nya menjadi POST dengan ID tugas.
    Route::post('/pekerja/terima/{id}', [PekerjaDashboardController::class, 'terima'])->name('pekerja_terima');

    // Rute untuk menyelesaikan tugas. Ubah method-nya menjadi POST dengan ID tugas.
    Route::post('/pekerja/selesai/{id}', [PekerjaDashboardController::class, 'selesai'])->name('pekerja_selesai');

    // Rute untuk keterangan, metode GET sudah benar.
    Route::get('/pekerja/keterangan', [PekerjaDashboardController::class, 'keterangan'])->name('pekerja_keterangan');
});

// =====================================================================================
// Rute Profile
// =====================================================================================
// Rute ini dapat diakses oleh semua pengguna yang sudah login
Route::middleware('auth')->group(function () {
    Route::post('/simpan-notifikasi-pwa', [App\Http\Controllers\PwaSubscriptionController::class, 'store'])->name('pwa.store');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
