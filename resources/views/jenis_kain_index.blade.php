@extends('layouts.app')

@section('title', 'Manajemen Jenis Kain')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-10" x-data="{
        tambahModal: false,
        editModal: false,
        kainEdit: {},
        confirmDelete: false,
        deleteData: {},
        searchQuery: '',
        showPreview: false,
        previewGambar: '',
    
        // Fungsi untuk filter data
        get filteredKain() {
            if (this.searchQuery === '') {
                return this.allKain;
            }
            return this.allKain.filter(kain =>
                kain.nama_kain.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                (kain.produk && kain.produk.nama_produk && kain.produk.nama_produk.toLowerCase().includes(this.searchQuery.toLowerCase()))
            );
        },
    
        // Data asli dari Laravel (pastikan menyertakan 'gambar')
        allKain: [
            @foreach($jenis_kain as $kain) {
                id: {{ $kain->id }},
                nama_kain: '{{ addslashes($kain->nama_kain) }}',
                produk_id: {{ $kain->produk_id ?? 'null' }},
                gambar: '{{ addslashes($kain->gambar ?? '') }}', // Tambahkan properti gambar
                produk: {
                    id: {{ $kain->produk->id ?? 'null' }},
                    nama_produk: '{{ addslashes($kain->produk->nama_produk ?? '') }}'
                }
            }
            @if(!$loop->last),
            @endif
            @endforeach
        ],
    
        showDelete(id, nama, route) {
            this.confirmDelete = true;
            this.deleteData = { id, nama, route };
        },
        hideDelete() {
            this.confirmDelete = false;
            this.deleteData = {};
        },
        showEdit(kain) { // Pastikan fungsi ini menerima objek kain lengkap
            this.kainEdit = JSON.parse(JSON.stringify(kain)); // Deep copy
            this.editModal = true;
        },
        resetAddModal() {
            // Reset state for add modal if needed (e.g., namaKainList)
            // This assumes namaKainList is within the x-data scope of the form itself
            // If not, you'd need to emit an event or call a function within that scope.
        }
    }">

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
            <div class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-400 text-green-800 px-4 py-3 rounded-lg mb-6 mx-auto max-w-7xl shadow-lg transition-all duration-300 ease-in-out transform scale-95 opacity-0 animate-fade-in"
                x-init="setTimeout(() => $el.classList.remove('scale-95', 'opacity-0'), 100)" x-cloak>
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div x-show="confirmDelete" x-cloak
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-sm transform transition-all duration-300 scale-95 opacity-0 animate-scale-in"
                @click.outside="hideDelete()" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                <div class="p-6 text-center">
                    <div
                        class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 dark:bg-red-700/20 mb-4">
                        <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Konfirmasi Hapus</h3>
                    <p class="text-gray-600 dark:text-gray-300 mb-1 text-sm">Hapus jenis kain:</p>
                    <p x-text="deleteData.nama" class="text-base font-semibold text-red-600 dark:text-red-400 mb-3"></p>
                    <div class="p-3 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-700">
                        <div class="flex items-center text-red-800 dark:text-red-300 text-xs">
                            <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span>Data tidak dapat dikembalikan!</span>
                        </div>
                    </div>
                </div>

                <div class="px-6 pb-6">
                    <div class="flex gap-2">
                        <button type="button" @click="hideDelete()"
                            class="flex-1 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-all duration-200 font-medium text-sm focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Batal
                        </button>
                        <form :action="deleteData.route" method="POST" class="flex-1">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="w-full px-3 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all duration-200 font-medium shadow-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- Header Section --}}
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700 p-4 sm:p-6 lg:p-8 mx-auto max-w-7xl mb-6 sm:mb-8 lg:mb-10">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                <div class="text-center sm:text-left">
                    <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white mb-1 sm:mb-2">Manajemen
                        Jenis Kain</h1>
                </div>

                {{-- Search & Add Button --}}
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 w-full sm:w-auto">
                    <div class="relative flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" x-model="searchQuery"
                            class="block w-full pl-10 pr-3 py-2 sm:py-3 border border-gray-200 dark:border-gray-600 rounded-lg sm:rounded-xl leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm sm:text-base text-gray-900 dark:text-white"
                            placeholder="Cari jenis kain atau produk...">
                    </div>
                    <div class="flex gap-2 sm:gap-3">
                        <button @click="searchQuery = ''"
                            class="flex-1 sm:flex-none px-3 sm:px-4 py-2 sm:py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg sm:rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-150 font-medium text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Reset
                        </button>
                        <button @click="tambahModal = true"
                            class="flex-1 sm:flex-none px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg sm:rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl font-medium flex items-center justify-center gap-2 text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="hidden sm:inline">Tambah Jenis Kain</span>
                            <span class="sm:hidden">Tambah</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Mobile Card Layout --}}
        <div class="block sm:hidden mx-auto max-w-7xl px-4 space-y-4">
            <template x-for="(kain, index) in filteredKain" :key="kain.id">
                <div
                    class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 p-4 transform transition-transform duration-200 hover:scale-[1.01]">
                    <div class="flex justify-between items-start mb-3">
                        <div class="flex-shrink-0 mr-4">
                            <template x-if="kain.gambar">
                                <img :src="'{{ asset('storage') }}/' + kain.gambar" :alt="kain.nama_kain"
                                    class="w-16 h-16 object-cover rounded-xl cursor-pointer hover:opacity-80 transition shadow-md"
                                    @click="previewGambar = '{{ asset('storage') }}/' + kain.gambar; showPreview = true">
                            </template>
                            <template x-if="!kain.gambar">
                                <div
                                    class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400 dark:text-gray-500">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </template>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-medium text-gray-500 dark:text-gray-400">#<span
                                        x-text="index + 1"></span></span>
                                <span
                                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300"
                                    x-text="kain.produk.nama_produk || '-'"></span>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white" x-text="kain.nama_kain"></h3>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button @click="showEdit(kain)"
                            class="flex items-center px-3 py-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-lg hover:bg-amber-100 dark:hover:bg-amber-800/20 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-amber-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                </path>
                            </svg>
                            Edit
                        </button>
                        <button
                            @click="showDelete(kain.id, kain.nama_kain, '{{ route('jenis_kain_destroy', '') }}/' + kain.id)"
                            class="flex items-center px-3 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 text-xs font-semibold rounded-lg hover:bg-red-100 dark:hover:bg-red-800/20 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-red-500">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                </path>
                            </svg>
                            Hapus
                        </button>
                    </div>
                </div>
            </template>

            <div x-show="filteredKain.length === 0"
                class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-100 dark:border-gray-700 p-8 text-center mt-6">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mb-4 mx-auto" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                <h3 class="text-base font-medium text-gray-900 dark:text-white mb-2">
                    <span x-show="searchQuery === ''">Belum ada data jenis kain</span>
                    <span x-show="searchQuery !== ''">Tidak ada data yang sesuai</span>
                </h3>
                <p class="text-gray-500 dark:text-gray-400 text-sm">
                    <span x-show="searchQuery === ''">Mulai dengan menambahkan jenis kain baru.</span>
                    <span x-show="searchQuery !== ''">Coba dengan kata kunci yang berbeda.</span>
                </p>
            </div>
        </div>

        {{-- Desktop Table --}}
        <div
            class="hidden sm:block bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-lg sm:shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden mx-auto max-w-7xl">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gradient-to-r from-gray-900 to-black dark:from-gray-700 dark:to-gray-900">
                        <tr>
                            <th
                                class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                No</th>
                            <th
                                class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Produk</th>
                            <th
                                class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider">
                                Nama Kain</th>
                            <th
                                class="px-4 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-white uppercase tracking-wider w-24">
                                Gambar Kain</th>
                            <th
                                class="px-4 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-white uppercase tracking-wider w-40">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                        <template x-for="(kain, index) in filteredKain" :key="kain.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                                <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white"
                                    x-text="index + 1"></td>
                                <td
                                    class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <span
                                        class="inline-flex items-center px-2 sm:px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300"
                                        x-text="kain.produk.nama_produk || '-'"></span>
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-white"
                                    x-text="kain.nama_kain"></td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap">
                                    <template x-if="kain.gambar">
                                        <img :src="'{{ asset('storage') }}/' + kain.gambar" :alt="kain.nama_kain"
                                            class="w-16 h-16 object-cover rounded-xl cursor-pointer hover:opacity-80 transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105"
                                            @click="previewGambar = '{{ asset('storage') }}/' + kain.gambar; showPreview = true">
                                    </template>
                                    <template x-if="!kain.gambar">
                                        <div
                                            class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded-xl flex items-center justify-center text-gray-400 dark:text-gray-500">
                                            <svg class="w-8 h-8" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </template>
                                </td>
                                <td class="px-4 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-center">
                                    <div class="flex justify-center gap-2">
                                        <button @click="showEdit(kain)"
                                            class="inline-flex items-center px-3 py-2 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 text-amber-700 dark:text-amber-400 text-xs font-semibold rounded-lg hover:bg-amber-100 dark:hover:bg-amber-800/20 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                </path>
                                            </svg>
                                            Edit
                                        </button>
                                        <button
                                            @click="showDelete(kain.id, kain.nama_kain, '{{ route('jenis_kain_destroy', '') }}/' + kain.id)"
                                            class="inline-flex items-center px-3 py-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 text-xs font-semibold rounded-lg hover:bg-red-100 dark:hover:bg-red-800/20 transition-colors duration-150 focus:outline-none focus:ring-2 focus:ring-red-500">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr x-show="filteredKain.length === 0">
                            <td colspan="5"
                                class="text-center px-4 sm:px-6 py-12 sm:py-16 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 sm:w-16 h-12 sm:h-16 text-gray-300 dark:text-gray-600 mb-4"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                        </path>
                                    </svg>
                                    <h3 class="text-base sm:text-lg font-medium text-gray-900 dark:text-white mb-2">
                                        <span x-show="searchQuery === ''">Belum ada data jenis kain.</span>
                                        <span x-show="searchQuery !== ''">Tidak ada data yang sesuai dengan pencarian
                                            Anda.</span>
                                    </h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm sm:text-base">
                                        <span x-show="searchQuery === ''">Klik "Tambah Jenis Kain" untuk mulai menambahkan
                                            data.</span>
                                        <span x-show="searchQuery !== ''">Coba dengan kata kunci yang berbeda atau reset
                                            pencarian.</span>
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

                <!-- Pagination -->
    <div class="mt-8 mx-auto max-w-7xl">
        {{ $jenis_kain->appends(request()->query())->links('vendor.pagination.tailwind') }}
    </div>

        <div x-show="tambahModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
            x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-md sm:max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0 animate-scale-in"
                @click.outside="tambahModal = false; resetAddModal()" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                <div class="p-4 sm:p-8">
                    <div class="flex items-center justify-between mb-4 sm:mb-6">
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white">Tambah Jenis Kain Baru</h3>
                        <button @click="tambahModal = false; resetAddModal()"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form action="{{ route('jenis_kain_store') }}" method="POST" class="space-y-4 sm:space-y-6"
                        enctype="multipart/form-data">
                        @csrf
                        <div>
                            <label for="produk_id_add"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pilih
                                Produk</label>
                            <select name="produk_id" id="produk_id_add" required
                                class="w-full px-3 sm:px-4 py-2 sm:py-3 border border-gray-200 dark:border-gray-600 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produk as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Input untuk gambar kain --}}
                        <div>
                            <label for="gambar_add"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gambar Kain
                                (Opsional)</label>
                            <input type="file" name="gambar" id="gambar_add" accept="image/*"
                                class="w-full text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                            file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 dark:file:bg-red-900/20 dark:file:text-red-300 hover:file:bg-red-100 dark:hover:file:bg-red-800/20 cursor-pointer">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Format: JPEG, PNG, JPG, GIF, SVG. Max
                                2MB.</p>
                        </div>

                        <div x-data="{ namaKainList: [''] }">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama
                                Kain</label>
                            <template x-for="(kain, index) in namaKainList" :key="index">
                                <div class="flex items-center gap-2 mb-2">
                                    <input type="text" :name="'nama_kain[]'" x-model="namaKainList[index]"
                                        class="flex-1 px-3 sm:px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg sm:rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm sm:text-base bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        placeholder="Contoh: Katun, Jersey" required>
                                </div>
                            </template>
                        </div>

                        <div
                            class="flex flex-col sm:flex-row justify-end gap-3 pt-4 sm:pt-6 border-t border-gray-100 dark:border-gray-700">
                            <button type="button" @click="tambahModal = false; resetAddModal()"
                                class="px-4 sm:px-6 py-2 sm:py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg sm:rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-150 font-medium text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-4 sm:px-6 py-2 sm:py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg sm:rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-150 font-medium shadow-lg text-sm sm:text-base focus:outline-none focus:ring-2 focus:ring-red-500">
                                Simpan Jenis Kain
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div x-show="editModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
            x-cloak>
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto transform transition-all duration-300 scale-95 opacity-0 animate-scale-in"
                @click.outside="editModal = false" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95">
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Jenis Kain</h3>
                        <button @click="editModal = false"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form :action="'{{ route('jenis_kain_update', '') }}/' + kainEdit.id" method="POST"
                        class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="produk_id_edit"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pilih
                                Produk</label>
                            <select name="produk_id" id="produk_id_edit" required
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                <option value="">-- Pilih Produk --</option>
                                @foreach ($produk as $item)
                                    <option value="{{ $item->id }}"
                                        :selected="kainEdit.produk_id == {{ $item->id }}">{{ $item->nama_produk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="nama_kain_edit"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Nama Kain</label>
                            <input type="text" name="nama_kain" id="nama_kain_edit" x-model="kainEdit.nama_kain"
                                required
                                class="w-full px-4 py-3 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                placeholder="Contoh: Katun, Jersey">
                        </div>

                        {{-- Input untuk gambar kain di modal edit --}}
                        <div>
                            <label for="gambar_edit"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Gambar Kain
                                (Opsional)</label>
                            <input type="file" name="gambar" id="gambar_edit" accept="image/*"
                                class="w-full text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent
                            file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-gray-50 file:text-gray-700 dark:file:bg-gray-900/20 dark:file:text-gray-300 hover:file:bg-gray-100 dark:hover:file:bg-gray-800/20 cursor-pointer">

                            <template x-if="kainEdit.gambar">
                                <div class="mt-2 flex items-center gap-3">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Gambar saat ini:</p>
                                    <img :src="'{{ asset('storage') }}/' + kainEdit.gambar" :alt="kainEdit.nama_kain"
                                        class="w-24 h-24 object-cover rounded-lg shadow-sm cursor-pointer border border-gray-200 dark:border-gray-600"
                                        @click="previewGambar = '{{ asset('storage') }}/' + kainEdit.gambar; showPreview = true">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Kosongkan jika tidak ingin mengubah
                                        gambar.</p>
                                </div>
                            </template>
                            <template x-if="!kainEdit.gambar">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Tidak ada gambar saat ini. Pilih
                                    file untuk mengunggah.</p>
                            </template>
                        </div>

                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-700">
                            <button type="button" @click="editModal = false"
                                class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-150 font-medium focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Batal
                            </button>
                            <button type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-amber-600 to-amber-700 text-white rounded-xl hover:from-amber-700 hover:to-amber-800 transition-all duration-150 font-medium shadow-lg focus:outline-none focus:ring-2 focus:ring-amber-500">
                                Update Jenis Kain
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>

    @push('styles')
        <style>
            /* Add some custom keyframes for animations */
            @keyframes fade-in {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .animate-fade-in {
                animation: fade-in 0.3s ease-out forwards;
            }

            @keyframes scale-in {
                from {
                    opacity: 0;
                    transform: scale(0.95);
                }

                to {
                    opacity: 1;
                    transform: scale(1);
                }
            }

            .animate-scale-in {
                animation: scale-in 0.3s ease-out forwards;
            }

            /* Alpine.js x-cloak styles */
            [x-cloak] {
                display: none !important;
            }
        </style>
    @endpush

@endsection
