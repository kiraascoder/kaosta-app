@extends('layouts.app')

@section('content')
{{-- Latar belakang utama halaman (body) diharapkan diatur di layouts.app.blade.php --}}
{{-- Contoh: <body class="h-full font-inter antialiased text-gray-900 dark:text-gray-100 bg-blue-100" ...> --}}

<div class="min-h-screen py-8 md:py-12">
    <!-- Main Content Wrapper -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8" x-data="orderForm({{ json_encode($produk) }}, {{ json_encode($hargaList) }})" x-init="init()">

        <form action="{{ route('order_store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <!-- Customer Information Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b-4 border-red-800">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        Informasi Customer
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="customer_id" class="block text-sm font-semibold text-gray-700">Nama Customer</label>
                            <select id="customer_id" name="customer_id" required
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50">
                                <option value="">-- Pilih Customer --</option>
                                @foreach ($customers as $cust)
                                    <option value="{{ $cust->id }}">{{ $cust->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Selection Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b-4 border-red-800">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                        Pilih Produk & Kain
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="produk_id" class="block text-sm font-semibold text-gray-700">Produk</label>
                            <select id="produk_id" name="produk_id" x-model="selectedProdukId"
                                @change="loadJenisKain(); updateUkuran();"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50">
                                <option value="">-- Pilih Produk --</option>
                                <template x-for="produk in produkList" :key="produk.id">
                                    <option :value="produk.id" x-text="produk.nama_produk"></option>
                                </template>
                            </select>
                            <div x-show="produkTerpilih && totalQty < produkTerpilih.minimal_pemesanan"
                                class="bg-red-50 border-l-4 border-red-400 p-3 rounded-r-lg mt-3">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-red-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <p class="text-sm text-red-700">
                                        Jumlah pesanan masih kurang dari minimal (<span
                                            x-text="produkTerpilih.minimal_pemesanan"></span> pcs)
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="jeniskain_id" class="block text-sm font-semibold text-gray-700">Jenis Kain</label>
                            <select id="jeniskain_id" name="jeniskain_id" x-model="selectedKainId" @change="updateUkuran()"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50">
                                <option value="">-- Pilih Jenis Kain --</option>
                                <template x-for="kain in jenisKainList" :key="kain.id">
                                    <option :value="kain.id" x-text="kain.nama_kain"></option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b-4 border-red-800">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                        Detail Pesanan
                    </h2>
                </div>
                <div class="p-6">
                    <!-- Desktop Headers (Dihapus karena tampilan akan menjadi kartu di semua ukuran) -->
                    {{-- <div class="hidden md:grid grid-cols-[1.2fr_1.8fr_2.5fr_1.2fr_1fr] gap-4 mb-4">
                        <div class="text-sm font-semibold text-gray-600 bg-gray-50 p-3 rounded-lg">Jumlah</div>
                        <div class="text-sm font-semibold text-gray-600 bg-gray-50 p-3 rounded-lg">Ukuran</div>
                        <div class="text-sm font-semibold text-gray-600 bg-gray-50 p-3 rounded-lg">Harga per Pcs</div>
                        <div class="text-sm font-semibold text-gray-600 bg-gray-50 p-3 rounded-lg">Lengan Panjang?</div>
                        <div class="text-sm font-semibold text-gray-600 bg-gray-50 p-3 rounded-lg">Aksi</div>
                    </div> --}}

                    <div class="space-y-4">
                        <template x-for="(row, index) in detailPesanan" :key="index">
                            {{-- Setiap baris detail pesanan sekarang akan selalu menjadi kartu, tanpa perbedaan mobile/desktop --}}
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-200 space-y-4">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4"> {{-- Menggunakan grid responsif untuk mobile/tablet --}}
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Jumlah</label>
                                        <input type="number"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50"
                                            placeholder="Jumlah" x-model.number="row.jumlah" @input="updateHarga(index)"
                                            :name="`ukuran_detail[${index}][jumlah]`" min="1">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-600 mb-1">Harga per Pcs</label>
                                        <input type="text"
                                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 focus:outline-none cursor-not-allowed"
                                            :value="formatRupiah(row.harga)" readonly>
                                        <input type="hidden" :name="`ukuran_detail[${index}][harga]`" :value="row.harga">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Ukuran</label>
                                    <select
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50"
                                        x-model="row.ukuran_id" :name="`ukuran_detail[${index}][ukuran_id]`"
                                        @change="updateHarga(index)">
                                        <option value="">Pilih Ukuran</option>
                                        <template x-for="ukuran in ukuranList" :key="ukuran.id">
                                            <option :value="ukuran.id" x-text="ukuran.nama_ukuran"></option>
                                        </template>
                                    </select>
                                </div>
                                <div x-show="punyaLenganPanjang">
                                    <label class="block text-xs font-medium text-gray-600 mb-1">Lengan Panjang</label>
                                    <select x-model.number="row.lengan_panjang"
                                        :name="`ukuran_detail[${index}][lengan_panjang]`"
                                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50"
                                        @change="updateHarga(index)">
                                        <option value="0">Tidak</option>
                                        <option value="1">Lengan Panjang</option>
                                    </select>
                                </div>
                                <div class="flex justify-end mt-4">
                                    <button type="button" @click="hapusPesanan(index)"
                                        class="px-4 py-2 text-sm bg-red-500 text-white rounded-lg hover:bg-red-600 transition transform hover:scale-105">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="produkTerpilih" class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-3 rounded-r-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <p class="text-sm text-blue-700">
                                Minimal pemesanan: <span x-text="produkTerpilih.minimal_pemesanan"></span> pcs
                            </p>
                        </div>
                    </div>

                    <button type="button" @click="tambahPesanan()"
                        class="mt-6 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all duration-200 transform hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambah Pesanan
                    </button>
                </div>
            </div>





            <!-- Additional Services Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b-4 border-red-800">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                            </path>
                        </svg>
                        Jasa Tambahan
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="jasa_tambahan" class="block text-sm font-semibold text-gray-700">Jenis Jasa</label>
                            <select id="jasa_tambahan" name="jasa_tambahan" x-model="jasaTambahan" @change="updateTotal()"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50">
                                <option value="tidak">Tidak Ada</option>
                                <option x-show="bisaSablon" value="sablon">Sablon</option>
                                <option x-show="bisaBordir" value="bordir">Bordir</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label for="fee_tambahan" class="block text-sm font-semibold text-gray-700">Fee Tambahan</label>
                            <input type="text" id="fee_tambahan" name="fee_tambahan_display" :value="formatRupiah(feeTambahan)"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 focus:outline-none cursor-not-allowed" readonly>
                            <input type="hidden" name="fee_tambahan" :value="feeTambahan">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Estimation Card -->
            <div
                class="bg-gradient-to-r from-green-600 to-green-700 rounded-2xl shadow-xl border border-green-200 overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between">
                        <div class="text-center md:text-left mb-4 md:mb-0">
                            <h3 class="text-xl font-bold text-white mb-2">Total Estimasi</h3>
                            <p class="text-3xl md:text-4xl font-black text-white" x-text="formatRupiah(totalHarga)">
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 backdrop-blur-sm rounded-xl p-4">
                            <!-- SVG for Rupiah Icon -->
                            <svg class="w-16 h-16 text-white mx-auto" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                <text x="12" y="15" font-family="Arial" font-size="8" text-anchor="middle" fill="white">Rp</text>
                            </svg>
                        </div>
                    </div>
                    <input type="hidden" name="total_harga" :value="totalHarga">
                </div>
            </div>

            <!-- Design Section Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b-4 border-red-800">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z">
                            </path>
                        </svg>
                        Desain & Catatan
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2">
                            <label for="desain_status" class="block text-sm font-semibold text-gray-700">Status Desain</label>
                            <select id="desain_status" name="desain_status" x-model="desainStatus"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50">
                                <option value="belum">Belum</option>
                                <option value="sudah">Sudah</option>
                            </select>
                        </div>
                        <div x-show="desainStatus === 'sudah'" class="space-y-2">
                            <label for="desain_file" class="block text-sm font-semibold text-gray-700">Upload Desain</label>
                            <input type="file" id="desain_file" name="desain_file"
                                class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="catatan_desain" class="block text-sm font-semibold text-gray-700">Catatan Desain</label>
                        <textarea id="catatan_desain" name="catatan_desain" rows="4"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50 resize-y"
                            placeholder="Masukkan catatan atau instruksi khusus untuk desain..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Payment Section Card -->
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4 border-b-4 border-red-800">
                    <h2 class="text-xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                            </path>
                        </svg>
                        Pembayaran DP
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="dp_nominal" class="block text-sm font-semibold text-gray-700">Nominal DP (50%)</label>
                            <input type="text" id="dp_nominal" name="dp_nominal_display" :value="formatRupiah(Math.ceil(totalHarga / 2))"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl bg-gray-100 focus:outline-none cursor-not-allowed" readonly>
                            <input type="hidden" name="dp_nominal" :value="Math.ceil(totalHarga / 2)">
                        </div>
                        <div class="space-y-2">
                            <label for="bukti_pembayaran" class="block text-sm font-semibold text-gray-700">Upload Bukti Pembayaran</label>
                            <input type="file" id="bukti_pembayaran" name="bukti_pembayaran"
                                class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl focus:border-red-500 focus:ring-2 focus:ring-red-200 transition-all duration-200 bg-gray-50 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="text-center pt-6">
                <button type="submit"
                    class="w-full md:w-auto px-12 py-4 bg-gradient-to-r from-red-600 to-red-700 text-white text-lg font-bold rounded-2xl hover:from-red-700 hover:to-red-800 transition-all duration-200 transform hover:scale-105 shadow-2xl">
                    <svg class="w-6 h-6 inline mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                    Kirim Pesanan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function orderForm(produkList, hargaList) {
        return {
            produkList: produkList,
            hargaList: hargaList,
            selectedProdukId: '',
            jenisKainList: [],
            selectedKainId: '',
            ukuranList: [],
            detailPesanan: [],
            jasaTambahan: 'tidak',
            feeTambahan: 0,
            totalHarga: 0,
            desainStatus: 'belum',
            punyaLenganPanjang: false,
            bisaSablon: false,
            bisaBordir: false,
            upHargaLengan: 0,
            upHargaSablon: 0,
            upHargaBordir: 0,

            // Getter untuk produk terpilih
            get produkTerpilih() {
                return this.produkList.find(p => p.id == this.selectedProdukId);
            },

            // Getter untuk total kuantitas semua pesanan
            get totalQty() {
                return this.detailPesanan.reduce((sum, d) => sum + parseInt(d.jumlah || 0), 0);
            },

            init() {
                // Saat inisialisasi, tambahkan satu baris pesanan dan hitung total.
                this.tambahPesanan();
                this.updateTotal();
            },

            loadJenisKain() {
                const produk = this.produkTerpilih;
                this.jenisKainList = produk?.jenis_kain || [];
                this.selectedKainId = ''; // Reset jenis kain saat produk berubah
                this.ukuranList = []; // Reset ukuran saat produk berubah
                this.detailPesanan = []; // Kosongkan detail pesanan
                this.tambahPesanan(); // Tambahkan baris pesanan baru setelah reset

                // Update kemampuan produk dan harga tambahan
                this.punyaLenganPanjang = produk?.mendukung_lengan_panjang == 1;
                this.bisaSablon = produk?.mendukung_sablon == 1;
                this.bisaBordir = produk?.mendukung_bordir == 1;
                this.upHargaLengan = parseInt(produk?.up_lengan_panjang || 0);
                this.upHargaSablon = parseInt(produk?.up_sablon_per_pcs || 0);
                this.upHargaBordir = parseInt(produk?.up_bordir_per_pcs || 0);

                // Reset jasa tambahan jika tidak didukung produk baru
                if (!this.bisaSablon && this.jasaTambahan === 'sablon') {
                    this.jasaTambahan = 'tidak';
                }
                if (!this.bisaBordir && this.jasaTambahan === 'bordir') {
                    this.jasaTambahan = 'tidak';
                }

                this.updateTotal(); // Perbarui total setelah perubahan produk
            },

            updateUkuran() {
                if (!this.selectedProdukId || !this.selectedKainId) {
                    this.ukuranList = [];
                    this.detailPesanan.forEach((_, i) => this.updateHarga(i));
                    return;
                }

                // Filter ukuran yang tersedia berdasarkan produk dan jenis kain yang dipilih
                this.ukuranList = this.hargaList
                    .filter(h => h.produk_id == this.selectedProdukId && h.jenis_kain_id == this.selectedKainId)
                    .map(h => ({
                        id: h.ukuran.id,
                        nama_ukuran: h.ukuran.nama_ukuran,
                        harga: h.harga,
                        harga_satuan: h.harga_satuan || h.harga, // Gunakan harga satuan jika ada, jika tidak pakai harga normal
                    }));

                // Perbarui harga untuk setiap baris detail pesanan
                this.detailPesanan.forEach((_, i) => this.updateHarga(i));
            },

            tambahPesanan() {
                this.detailPesanan.push({
                    jumlah: 1,
                    ukuran_id: '',
                    harga: 0,
                    lengan_panjang: 0
                });
                this.updateTotal();
            },

            hapusPesanan(index) {
                this.detailPesanan.splice(index, 1);
                if (this.detailPesanan.length === 0) {
                    this.tambahPesanan();
                }
                this.updateTotal();
            },

            // *** FUNGSI INI ADALAH FUNGSI INTI PERUBAHAN ***
            updateHarga(index) {
                const detail = this.detailPesanan[index];
                const ukuran = this.ukuranList.find(u => u.id == detail.ukuran_id);
                const totalQty = this.totalQty; // Ambil total kuantitas terbaru

                if (ukuran) {
                    let basePrice;
                    // Logika penentuan harga:
                    // Jika total kuantitas kurang dari minimal pemesanan
                    // DAN produk memiliki harga satuan (harga_satuan > 0),
                    // gunakan harga satuan. Jika tidak, gunakan harga grosir.
                    if (this.produkTerpilih && totalQty < this.produkTerpilih.minimal_pemesanan && ukuran.harga_satuan > 0) {
                        basePrice = parseInt(ukuran.harga_satuan || 0);
                    } else {
                        // Jika total kuantitas >= minimal pemesanan, pakai harga grosir
                        basePrice = parseInt(ukuran.harga || 0);
                    }
                    
                    // Tambahkan biaya lengan panjang jika dipilih
                    if (parseInt(detail.lengan_panjang) === 1 && this.punyaLenganPanjang) {
                        basePrice += this.upHargaLengan;
                    }
                    
                    detail.harga = basePrice;
                } else {
                    detail.harga = 0; // Reset harga jika ukuran tidak valid
                }
                this.updateTotal();
            },

            // *** FUNGSI INI JUGA PENTING UNTUK DIPERBARUI ***
            updateTotal() {
                // Hitung subtotal dari semua detail pesanan
                const subtotal = this.detailPesanan.reduce((sum, d) => {
                    return sum + (parseInt(d.harga || 0) * parseInt(d.jumlah || 0));
                }, 0);

                // Hitung fee tambahan berdasarkan jenis jasa dan total kuantitas
                const totalQty = this.totalQty;
                let fee = 0;
                if (this.jasaTambahan === 'sablon' && this.bisaSablon) {
                    fee = this.upHargaSablon * totalQty;
                } else if (this.jasaTambahan === 'bordir' && this.bisaBordir) {
                    fee = this.upHargaBordir * totalQty;
                }
                this.feeTambahan = fee;

                this.totalHarga = subtotal + this.feeTambahan;
            },

            formatRupiah(val) {
                if (isNaN(val) || val === null) {
                    return 'Rp0';
                }
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                }).format(val);
            }
        }
    }
</script>
@endsection
