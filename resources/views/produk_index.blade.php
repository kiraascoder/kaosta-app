@extends('layouts.app')

@section('title', 'Manajemen Produk')
@section('header', 'Manajemen Produk')

@section('content')
{{-- Container utama yang mengelola padding horizontal dan latar belakang --}}
<div x-data="{
    tambahModal: false,
    editModal: false,
    produkEdit: {
        id: null,
        nama_produk: '',
        deskripsi: '',
        minimal_pemesanan: 1,
        mendukung_lengan_panjang: 0,
        up_lengan_panjang: 0,
        mendukung_sablon: 0,
        up_sablon_per_pcs: 0,
        mendukung_bordir: 0,
        up_bordir_per_pcs: 0,
        gambar: '' // Pastikan properti gambar ada di sini
    },
    showPreview: false,
    previewGambar: '',
    openDetails: {}, // Objek untuk melacak detail yang terbuka { produk_id: boolean }

    // Fungsi untuk membuka/menutup detail baris
    toggleDetails(productId) {
        this.openDetails[productId] = !this.openDetails[productId];
    },

    // Fungsi untuk mengisi data edit modal
    showEditModal(produk) {
        this.produkEdit = JSON.parse(JSON.stringify(produk)); // Deep copy untuk menghindari referensi langsung
        this.editModal = true;
    }
}" class="min-h-screen pb-10"> {{-- Hapus background gradient di sini karena sudah di layout utama --}}

    <!-- Modal Pratinjau Gambar -->
    <div x-show="showPreview" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div @click.outside="showPreview = false" class="relative max-w-4xl w-full mx-auto">
            <img :src="previewGambar" class="w-full h-auto max-h-[80vh] object-contain rounded-2xl shadow-2xl"
                x-transition:enter="transition ease-out duration-300 transform"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <button @click="showPreview = false"
                class="absolute -top-4 -right-4 bg-white text-gray-700 rounded-full p-3 shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-red-500">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border-l-4 border-green-400 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-lg mb-6 mx-auto max-w-7xl shadow-lg transition-all duration-300 ease-in-out transform scale-95 opacity-0 animate-fade-in"
             x-init="setTimeout(() => $el.classList.remove('scale-95', 'opacity-0'), 100)"
             x-cloak>
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    <!-- Bagian Header -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:p-8 mb-8 mx-auto max-w-7xl">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-black bg-clip-text text-transparent dark:from-white dark:to-gray-200">
                        Manajemen Produk
                    </h1>
                </div>
            </div>

            <!-- Pencarian dan Tombol Tambah -->
            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                <!-- Form Pencarian -->
                <form method="GET" action="{{ route('produk_index') }}" class="flex gap-2 w-full sm:w-auto">
                    <div class="relative flex-1 min-w-40 sm:min-w-64">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Cari nama produk..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:bg-gray-50 dark:focus:bg-gray-600" />
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-400"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-gray-900 to-black text-white rounded-xl hover:from-gray-800 hover:to-gray-900 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex-shrink-0 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Cari
                    </button>
                </form>

                <!-- Tombol Tambah -->
                <button @click="tambahModal = true"
                    class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-500 hover:to-red-600 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Produk
                </button>
            </div>
        </div>
    </div>

    <!-- Bagian Tabel -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mx-auto max-w-7xl">
        <!-- Kartu Mobile (terlihat di layar kecil) -->
        <div class="block lg:hidden">
            <div class="p-4 space-y-4">
                @forelse ($produk as $prd)
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700 shadow-md">
                        <div class="flex items-start gap-4 mb-4">
                            <div class="flex-shrink-0">
                                @if ($prd->gambar)
                                    <img src="{{ asset('storage/' . $prd->gambar) }}" alt="{{ $prd->nama_produk }}"
                                        class="w-16 h-16 object-cover rounded-xl cursor-pointer hover:opacity-80 transition shadow-md"
                                        @click="previewGambar = '{{ asset('storage/' . $prd->gambar) }}'; showPreview = true">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">ID: {{ $prd->id }}</p> {{-- Menggunakan $prd->id --}}
                                        <h3 class="font-semibold text-gray-900 dark:text-white text-lg leading-tight">{{ $prd->nama_produk }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div> {{-- End flex items-start gap-4 --}}

                        <div class="space-y-2 text-gray-700 dark:text-gray-300">
                            <div class="flex items-start text-sm">
                                <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <p class="line-clamp-3">{{ $prd->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            </div>
                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6h18M3 12h18M3 18h18"></path>
                                </svg>
                                Minimal Pemesanan: <span class="ml-1 font-medium">{{ $prd->minimal_pemesanan }}</span>
                            </div>

                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a3 3 0 100-6 3 3 0 000 6zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Lengan Panjang: <span class="ml-1 font-medium">{{ $prd->mendukung_lengan_panjang ? 'Ya' : 'Tidak' }}</span>
                                @if ($prd->mendukung_lengan_panjang)
                                    <span class="ml-2 text-red-500 font-medium">(+Rp{{ number_format($prd->up_lengan_panjang, 0, ',', '.') }})</span>
                                @endif
                            </div>

                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 01-4 4zm0 0V9m6 0h4"></path>
                                </svg>
                                Sablon: <span class="ml-1 font-medium">{{ $prd->mendukung_sablon ? 'Ya' : 'Tidak' }}</span>
                                @if ($prd->mendukung_sablon)
                                    <span class="ml-2 text-red-500 font-medium">(+Rp{{ number_format($prd->up_sablon_per_pcs, 0, ',', '.') }})</span>
                                @endif
                            </div>

                            <div class="flex items-center text-sm">
                                <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Bordir: <span class="ml-1 font-medium">{{ $prd->mendukung_bordir ? 'Ya' : 'Tidak' }}</span>
                                @if ($prd->mendukung_bordir)
                                    <span class="ml-2 text-red-500 font-medium">(+Rp{{ number_format($prd->up_bordir_per_pcs, 0, ',', '.') }})</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex gap-2 mt-4 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <button @click="showEditModal({{ $prd->toJson() }})"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 text-amber-700 dark:text-amber-400 text-sm font-semibold rounded-xl hover:bg-amber-100 dark:hover:bg-amber-800/20 transition-colors duration-150 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit
                            </button>
                            <form action="{{ route('produk_destroy', $prd->id) }}" method="POST"
                                class="flex-1">
                                @csrf @method('DELETE')
                                <button onclick="return confirm('Yakin hapus produk ini?')"
                                    class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 text-sm font-semibold rounded-xl hover:bg-red-100 dark:hover:bg-red-800/20 transition-colors duration-150 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
                        <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-lg">Belum ada produk tersedia</p>
                        <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Klik tombol "Tambah Produk" untuk memulai</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Tabel Desktop (tersembunyi di layar kecil) -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full min-w-max divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-900 border-b border-gray-300 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-16">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 min-w-40">Nama Produk</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 min-w-60">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-32">Minimal Pemesanan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 w-24">Gambar</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 w-32">Aksi</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700 dark:text-gray-200 w-24">Detail</th> {{-- Kolom baru untuk tombol detail --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($produk as $prd)
                        <tr class="hover:bg-gradient-to-r hover:from-gray-50 hover:to-gray-100 dark:hover:from-gray-700/50 dark:hover:to-gray-800/50 transition-all duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                <span class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded-lg text-xs text-gray-700 dark:text-gray-200">{{ $prd->id }}</span> {{-- Menggunakan $prd->id --}}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $prd->nama_produk }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300 max-w-xs">
                                <p class="line-clamp-2">{{ $prd->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                {{ $prd->minimal_pemesanan }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($prd->gambar)
                                    <img src="{{ asset('storage/' . $prd->gambar) }}" alt="{{ $prd->nama_produk }}"
                                        class="w-16 h-16 object-cover rounded-xl cursor-pointer hover:opacity-80 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                        @click="previewGambar = '{{ asset('storage/' . $prd->gambar) }}'; showPreview = true">
                                @else
                                    <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400 dark:text-gray-500">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <button @click="showEditModal({{ $prd->toJson() }})"
                                        class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg text-sm font-medium hover:from-red-400 hover:to-red-500 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-red-500">
                                        Edit
                                    </button>
                                    <form action="{{ route('produk_destroy', $prd->id) }}" method="POST"
                                        class="inline">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Yakin hapus produk ini?')" {{-- Pertimbangkan menggunakan modal konfirmasi kustom --}}
                                            class="px-4 py-2 bg-gradient-to-r from-gray-800 to-black text-white text-sm rounded-lg font-medium hover:from-gray-700 hover:to-gray-800 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button @click="toggleDetails({{ $prd->id }})"
                                    class="p-2 rounded-full bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-800/20 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg x-show="!openDetails[{{ $prd->id }}]" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <svg x-show="openDetails[{{ $prd->id }}]" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <tr x-show="openDetails[{{ $prd->id }}]" x-collapse>
                            <td colspan="7" class="p-0"> {{-- colspan harus sesuai dengan jumlah kolom header utama (7) --}}
                                <div class="bg-gray-50 dark:bg-gray-700/50 p-4 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-700 dark:text-gray-200 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a3 3 0 100-6 3 3 0 000 6zM21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Lengan Panjang: <span class="ml-1 font-medium">{{ $prd->mendukung_lengan_panjang ? 'Ya' : 'Tidak' }}</span>
                                        @if ($prd->mendukung_lengan_panjang)
                                            <span class="ml-2 text-red-500 font-medium">(+Rp{{ number_format($prd->up_lengan_panjang, 0, ',', '.') }})</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 01-4 4zm0 0V9m6 0h4"></path>
                                        </svg>
                                        Sablon: <span class="ml-1 font-medium">{{ $prd->mendukung_sablon ? 'Ya' : 'Tidak' }}</span>
                                        @if ($prd->mendukung_sablon)
                                            <span class="ml-2 text-red-500 font-medium">(+Rp{{ number_format($prd->up_sablon_per_pcs, 0, ',', '.') }})</span>
                                        @endif
                                    </div>

                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Bordir: <span class="ml-1 font-medium">{{ $prd->mendukung_bordir ? 'Ya' : 'Tidak' }}</span>
                                        @if ($prd->mendukung_bordir)
                                            <span class="ml-2 text-red-500 font-medium">(+Rp{{ number_format($prd->up_bordir_per_pcs, 0, ',', '.') }})</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-16 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                <svg class="w-16 h-16 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg">Belum ada produk tersedia</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Klik tombol "Tambah Produk" untuk memulai</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-8 mx-auto max-w-7xl">
        {{ $produk->appends(request()->query())->links('vendor.pagination.tailwind') }}
    </div>

    <!-- Modal Tambah -->
    <div x-show="tambahModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-2xl shadow-2xl relative overflow-hidden max-h-[90vh] overflow-y-auto custom-scrollbar"
            @click.outside="tambahModal = false" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <!-- Header Modal -->
            <div class="bg-gradient-to-r from-red-600 to-red-700 px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Produk Baru
                </h3>
            </div>

            <!-- Body Modal -->
            <form action="{{ route('produk_store') }}" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-6" x-data="{ mendukung_lengan_panjang: 0, up_lengan_panjang: 0, mendukung_sablon: 0, up_sablon_per_pcs: 0, mendukung_bordir: 0, up_bordir_per_pcs: 0 }">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label for="nama_produk_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Produk</label>
                        <input name="nama_produk" id="nama_produk_add" required placeholder="Masukkan nama produk..."
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:bg-gray-50 dark:focus:bg-gray-600">
                    </div>

                    <div>
                        <label for="gambar_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gambar Produk</label>
                        <div class="relative">
                            <input type="file" name="gambar" id="gambar_add" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:bg-gray-50 dark:focus:bg-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 dark:file:bg-red-900/20 dark:file:text-red-300 hover:file:bg-red-100 dark:hover:file:bg-red-800/20 cursor-pointer">
                        </div>
                    </div>

                    <div>
                        <label for="deskripsi_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi_add" rows="4" placeholder="Masukkan deskripsi produk..."
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:bg-gray-50 dark:focus:bg-gray-600 resize-none"></textarea>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Minimal Pemesanan -->
                    <div>
                        <label for="minimal_pemesanan_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Minimal Pemesanan</label>
                        <input type="number" name="minimal_pemesanan" id="minimal_pemesanan_add" min="1" value="1"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <!-- Lengan Panjang -->
                    <div>
                        <label for="mendukung_lengan_panjang_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Apakah Ada Lengan Panjang?</label>
                        <select name="mendukung_lengan_panjang" id="mendukung_lengan_panjang_add" x-model="mendukung_lengan_panjang"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div>
                        <label for="up_lengan_panjang_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Up Harga Lengan Panjang (Rp)</label>
                        <input type="number" name="up_lengan_panjang" id="up_lengan_panjang_add" value="0"
                            :disabled="mendukung_lengan_panjang != 1"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-70">
                    </div>

                    <!-- Sablon -->
                    <div>
                        <label for="mendukung_sablon_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Boleh Sablon?</label>
                        <select name="mendukung_sablon" id="mendukung_sablon_add" x-model="mendukung_sablon"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div>
                        <label for="up_sablon_per_pcs_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Up Harga Sablon per Pcs (Rp)</label>
                        <input type="number" name="up_sablon_per_pcs" id="up_sablon_per_pcs_add" value="0"
                            :disabled="mendukung_sablon != 1"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-70">
                    </div>

                    <!-- Bordir -->
                    <div>
                        <label for="mendukung_bordir_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Boleh Bordir?</label>
                        <select name="mendukung_bordir" id="mendukung_bordir_add" x-model="mendukung_bordir"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div>
                        <label for="up_bordir_per_pcs_add" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Up Harga Bordir per Pcs (Rp)</label>
                        <input type="number" name="up_bordir_per_pcs" id="up_bordir_per_pcs_add" value="0"
                            :disabled="mendukung_bordir != 1"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-70">
                    </div>
                </div>

                <!-- Footer Modal -->
                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button @click="tambahModal = false" type="button"
                        class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-500 hover:to-red-600 transition-all duration-200 font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-red-500">
                        Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
    <div x-show="editModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 w-full max-w-2xl rounded-2xl shadow-2xl relative max-h-[90vh] overflow-y-auto custom-scrollbar"
            @click.outside="editModal = false" x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4">

            <div class="bg-gradient-to-r from-gray-800 to-black px-6 py-4">
                <h3 class="text-xl font-bold text-white flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Produk
                </h3>
            </div>

            <form :action="'/produk/' + produkEdit.id" method="POST" enctype="multipart/form-data"
                class="p-6 space-y-6">
                @csrf @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label for="nama_produk_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Produk</label>
                        <input name="nama_produk" id="nama_produk_edit" x-model="produkEdit.nama_produk" required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:bg-gray-50 dark:focus:bg-gray-600">
                    </div>

                    <div>
                        <label for="gambar_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gambar Produk</label>
                        <div class="relative">
                            <input type="file" name="gambar" id="gambar_edit" accept="image/*"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:bg-gray-50 dark:focus:bg-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-900/20 dark:file:text-gray-300 hover:file:bg-gray-100 dark:hover:file:bg-gray-800/20 cursor-pointer">
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah gambar</p>
                        <template x-if="produkEdit.gambar">
                            <div class="mt-2">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Gambar saat ini:</p>
                                <img :src="'{{ asset('storage') }}/' + produkEdit.gambar" :alt="produkEdit.nama_produk"
                                    class="w-24 h-24 object-cover rounded-lg shadow-sm cursor-pointer border border-gray-200 dark:border-gray-600"
                                    @click="previewGambar = '{{ asset('storage') }}/' + produkEdit.gambar; showPreview = true">
                            </div>
                        </template>
                    </div>

                    <div>
                        <label for="deskripsi_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi_edit" x-model="produkEdit.deskripsi" rows="4"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:bg-gray-50 dark:focus:bg-gray-600 resize-none"></textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="minimal_pemesanan_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Minimal Pemesanan</label>
                        <input type="number" name="minimal_pemesanan" id="minimal_pemesanan_edit" x-model="produkEdit.minimal_pemesanan"
                            min="1"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                    </div>

                    <div>
                        <label for="mendukung_lengan_panjang_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Apakah Ada Lengan Panjang?</label>
                        <select name="mendukung_lengan_panjang" id="mendukung_lengan_panjang_edit" x-model="produkEdit.mendukung_lengan_panjang"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div>
                        <label for="up_lengan_panjang_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Up Harga Lengan Panjang (Rp)</label>
                        <input type="number" name="up_lengan_panjang" id="up_lengan_panjang_edit" x-model="produkEdit.up_lengan_panjang"
                            :disabled="produkEdit.mendukung_lengan_panjang != 1"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-70">
                    </div>

                    <div>
                        <label for="mendukung_sablon_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Boleh Sablon?</label>
                        <select name="mendukung_sablon" id="mendukung_sablon_edit" x-model="produkEdit.mendukung_sablon"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div>
                        <label for="up_sablon_per_pcs_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Up Harga Sablon per Pcs (Rp)</label>
                        <input type="number" name="up_sablon_per_pcs" id="up_sablon_per_pcs_edit" x-model="produkEdit.up_sablon_per_pcs"
                            :disabled="produkEdit.mendukung_sablon != 1"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-70">
                    </div>

                    <div>
                        <label for="mendukung_bordir_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Boleh Bordir?</label>
                        <select name="mendukung_bordir" id="mendukung_bordir_edit" x-model="produkEdit.mendukung_bordir"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="0">Tidak</option>
                            <option value="1">Ya</option>
                        </select>
                    </div>
                    <div>
                        <label for="up_bordir_per_pcs_edit" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Up Harga Bordir per Pcs (Rp)</label>
                        <input type="number" name="up_bordir_per_pcs" id="up_bordir_per_pcs_edit" x-model="produkEdit.up_bordir_per_pcs"
                            :disabled="produkEdit.mendukung_bordir != 1"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white disabled:bg-gray-100 dark:disabled:bg-gray-700 disabled:cursor-not-allowed disabled:opacity-70">
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <button @click="editModal = false" type="button"
                        class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 font-medium focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-gradient-to-r from-gray-800 to-black text-white rounded-xl hover:from-gray-700 hover:to-gray-800 transition-all duration-200 font-medium shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-gray-500">
                        Update Produk
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>

@push('styles')
<style>
    /* Custom scrollbar for modals */
    .custom-scrollbar::-webkit-scrollbar {
        width: 8px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1; /* Light track */
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #888; /* Darker thumb */
        border-radius: 10px;
        border: 2px solid #f1f1f1; /* Padding effect */
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #555;
    }

    /* Dark mode scrollbar */
    html.dark .custom-scrollbar::-webkit-scrollbar-track {
        background: #1f2937; /* Dark track */
    }
    html.dark .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #4b5563; /* Darker thumb */
        border: 2px solid #1f2937; /* Padding effect */
    }
    html.dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background-color: #6b7280;
    }
</style>
@endpush

@endsection
