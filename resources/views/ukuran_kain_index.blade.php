@extends('layouts.app')

@section('title', 'Ukuran Produk')
@section('header', 'Ukuran Produk')

@section('content')

{{-- Define global data for Alpine.js directly in a script block --}}
<script>
    // Pastikan data ini selalu menjadi array, meskipun dari PHP kosong atau null
    window.allProdukData = @json($produk ?: []);
    window.allJenisKainData = @json($jenis_kain ?: []);

    // Alpine.js function to filter jenis kain based on selected product
    function ukuranForm(selectedProduk = '', selectedKain = '') {
        return {
            // Mengambil data produk dan jenis kain dari variabel global window
            allProduk: window.allProdukData,
            allJenisKain: window.allJenisKainData,
            selectedProdukId: selectedProduk,
            selectedJenisKainId: selectedKain,
            get filteredJenisKain() {
                // Pastikan selectedProdukId adalah string atau number yang valid
                return this.allJenisKain.filter(k => k.produk_id == this.selectedProdukId);
            }
        }
    }
</script>

{{-- Container utama yang mengelola padding horizontal dan latar belakang --}}
<div x-data="{
    tambahModal: false,
    editModal: false,
    editData: {}, // Data untuk modal edit
    
    // Referensi ke variabel global yang sudah didefinisikan
    allProduk: window.allProdukData, // Initial assignment from global variable
    allJenisKain: window.allJenisKainData, // Initial assignment from global variable

    // State untuk fitur pencarian
    searchProdukId: '{{ request('search_produk_id') }}',
    searchJenisKainId: '{{ request('search_jenis_kain_id') }}',
    // searchUkuranQuery: '{{ request('search_ukuran_query') }}', // Dihapus
    
    // Computed property untuk filter jenis kain di form pencarian
    get filteredSearchJenisKain() {
        return this.allJenisKain.filter(k => k.produk_id == this.searchProdukId);
    },

    // Fungsi untuk mereset pencarian
    resetSearch() {
        this.searchProdukId = '';
        this.searchJenisKainId = '';
        // this.searchUkuranQuery = ''; // Dihapus
        // Redirect to clear URL parameters
        window.location.href = '{{ route('ukuran_index') }}';
    },

    // Fungsi untuk membuka modal edit dan mengisi data
    showEditModal(item) {
        this.editData = JSON.parse(JSON.stringify(item)); // Deep copy
        this.editModal = true;
    }
}" class="min-h-screen pb-10">

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

    {{-- Bagian Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 lg:p-8 mb-8 mx-auto max-w-7xl">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-md">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                    </div>
                    <h1
                        class="text-3xl font-bold bg-gradient-to-r from-gray-900 to-black bg-clip-text text-transparent dark:from-white dark:to-gray-200">
                        Manajemen Ukuran Produk
                    </h1>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                {{-- Tombol Tambah --}}
                <button @click="tambahModal = true"
                    class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-500 hover:to-red-600 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 flex items-center justify-center gap-2 whitespace-nowrap focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Tambah Ukuran
                </button>
            </div>
        </div>

        {{-- Form Pencarian --}}
        {{-- Mengubah grid-cols menjadi sm:grid-cols-2 dan lg:grid-cols-3 --}}
        <form method="GET" action="{{ route('ukuran_index') }}" class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl shadow-inner border border-gray-200 dark:border-gray-700 space-y-4 sm:space-y-0 sm:grid sm:grid-cols-2 sm:gap-4 lg:grid-cols-3 lg:gap-6 items-end">
            <div>
                <label for="search_produk_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Produk</label>
                <select name="search_produk_id" id="search_produk_id" x-model="searchProdukId"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    <option value="">-- Semua Produk --</option>
                    <template x-for="prod in allProduk" :key="prod.id">
                        <option :value="prod.id" x-text="prod.nama_produk"></option>
                    </template>
                </select>
            </div>

            <div>
                <label for="search_jenis_kain_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Jenis Kain</label>
                <select name="search_jenis_kain_id" id="search_jenis_kain_id" x-model="searchJenisKainId"
                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        :disabled="!searchProdukId">
                    <option value="">-- Semua Jenis Kain --</option>
                    <template x-for="kain in filteredSearchJenisKain" :key="kain.id">
                        <option :value="kain.id" x-text="kain.nama_kain"></option>
                    </template>
                </select>
            </div>

            {{-- Input Nama Ukuran Dihapus --}}

            {{-- Tombol Cari dan Reset --}}
            {{-- Mengubah col-span agar tombol menyesuaikan layout grid baru --}}
            <div class="sm:col-span-2 lg:col-span-1 flex gap-2 sm:justify-end">
                <button type="button" @click="resetSearch()"
                        class="flex-1 sm:flex-none px-4 py-2 bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-500 transition-colors duration-150 font-medium focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Reset
                </button>
                <button type="submit"
                        class="flex-1 sm:flex-none px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150 font-medium shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    Cari
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel Desktop --}}
    <div class="hidden lg:block bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden mx-auto max-w-7xl">
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-gray-800 dark:text-gray-200 divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-900 border-b border-gray-300 dark:border-gray-700">
                    <tr>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 w-16">No</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 min-w-40">Produk</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 min-w-40">Jenis Kain</th>
                        <th class="text-left py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 min-w-40">Ukuran</th>
                        <th class="text-center py-3 px-4 font-semibold text-gray-700 dark:text-gray-200 w-40">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($ukuran as $index => $item)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                        <td class="py-3 px-4 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 whitespace-nowrap font-medium">{{ $item->produk->nama_produk ?? '-' }}</td>
                        <td class="py-3 px-4 whitespace-nowrap">{{ $item->jenisKain->nama_kain ?? '-' }}</td>
                        <td class="py-3 px-4 whitespace-nowrap font-semibold">{{ $item->nama_ukuran }}</td>
                        <td class="py-3 px-4 text-center space-x-2 whitespace-nowrap">
                            {{-- Edit Button --}}
                            <button @click="showEditModal({{ $item->toJson() }})"
                                class="px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-lg text-sm font-medium hover:from-amber-400 hover:to-amber-500 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-amber-500">
                                Edit
                            </button>
                            {{-- Delete Form --}}
                            <form action="{{ route('ukuran_destroy', $item->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg text-sm font-medium hover:from-red-500 hover:to-red-600 transition-all duration-200 shadow-sm hover:shadow-md transform hover:-translate-y-0.5 focus:outline-none focus:ring-2 focus:ring-red-500"
                                    onclick="return confirm('Yakin ingin menghapus ukuran ini?')">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-16 text-gray-500 dark:text-gray-400">
                            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            <p class="text-lg">Belum ada data ukuran.</p>
                            <p class="text-sm mt-1">Klik tombol "Tambah Ukuran" untuk memulai.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Layout Kartu Mobile --}}
    <div class="lg:hidden mx-auto max-w-7xl px-4 space-y-4">
        @forelse ($ukuran as $index => $item)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-md transform transition-transform duration-200 hover:scale-[1.01]">
            <div class="flex items-start justify-between mb-3">
                <div class="flex-1">
                    <div class="text-xs text-gray-500 dark:text-gray-400 mb-1">#{{ $index + 1 }}</div>
                    <h3 class="font-semibold text-gray-900 dark:text-white text-lg mb-1">{{ $item->produk->nama_produk ?? '-' }}</h3>
                    <div class="space-y-1">
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                            </svg>
                            <span>{{ $item->jenisKain->nama_kain ?? '-' }}</span>
                        </div>
                        <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                            <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            <span class="font-medium">{{ $item->nama_ukuran }}</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- Tombol Aksi --}}
            <div class="flex gap-2 mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
                {{-- Edit Button --}}
                <button @click="showEditModal({{ $item->toJson() }})"
                    class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-700 text-amber-700 dark:text-amber-400 text-sm font-semibold rounded-xl hover:bg-amber-100 dark:hover:bg-amber-800/20 transition-colors duration-150 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-amber-500">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </button>
                {{-- Delete Button --}}
                <form action="{{ route('ukuran_destroy', $item->id) }}" method="POST" class="flex-1">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-700 dark:text-red-400 text-sm font-semibold rounded-xl hover:bg-red-100 dark:hover:bg-red-800/20 transition-colors duration-150 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-red-500"
                        onclick="return confirm('Yakin ingin menghapus ukuran ini?')">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
            <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
            </svg>
            <p class="text-gray-500 dark:text-gray-400 text-lg">Belum ada data ukuran.</p>
            <p class="text-gray-400 dark:text-gray-500 text-sm mt-1">Klik tombol "Tambah Ukuran" untuk memulai.</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination Component --}}
    <div class="mt-6 mx-auto max-w-7xl flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
        {{-- Results Info --}}
        <div class="text-sm text-gray-600 dark:text-gray-400">
            Menampilkan {{ $ukuran->firstItem() ?? 0 }} sampai {{ $ukuran->lastItem() ?? 0 }}
            dari {{ $ukuran->total() }} hasil
        </div>

        {{-- Pagination Links --}}
        @if ($ukuran->hasPages())
        <div class="flex items-center space-x-1">
            {{-- Previous Page Link --}}
            @if ($ukuran->onFirstPage())
                <span class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </span>
            @else
                <a href="{{ $ukuran->previousPageUrl() }}"
                   class="px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-red-300 dark:hover:border-red-500 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
            @endif

            {{-- Page Numbers --}}
            @foreach ($ukuran->getUrlRange(1, $ukuran->lastPage()) as $page => $url)
                @if ($page == $ukuran->currentPage())
                    <span class="px-3 py-2 text-sm bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg font-semibold shadow-md">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $url }}"
                       class="px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 hover:border-red-300 dark:hover:border-red-500 hover:text-red-600 dark:hover:text-red-400 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($ukuran->hasMorePages())
                <a href="{{ $ukuran->nextPageUrl() }}"
                   class="px-3 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-red-300 dark:hover:border-red-500 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            @else
                <span class="px-3 py-2 text-sm bg-gray-100 dark:bg-gray-700 text-gray-400 dark:text-gray-500 rounded-lg cursor-not-allowed">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            @endif
        </div>
        @endif
    </div>

    {{-- Advanced Pagination with Jump to Page (Optional) --}}
    <div class="mt-4 mx-auto max-w-7xl flex flex-col sm:flex-row items-center justify-between space-y-3 sm:space-y-0">
        {{-- Per Page Selector --}}
        <div class="flex items-center space-x-2">
            <label class="text-sm text-gray-600 dark:text-gray-400">Tampilkan:</label>
            <select onchange="window.location.href=this.value"
                    class="px-3 py-1 text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 10]) }}" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 25]) }}" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 50]) }}" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="{{ request()->fullUrlWithQuery(['per_page' => 100]) }}" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-sm text-gray-600 dark:text-gray-400">per halaman</span>
        </div>

        {{-- Jump to Page --}}
        @if ($ukuran->lastPage() > 1)
        <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-600 dark:text-gray-400">Halaman:</span>
            <input type="number"
                   min="1"
                   max="{{ $ukuran->lastPage() }}"
                   value="{{ $ukuran->currentPage() }}"
                   class="w-16 px-2 py-1 text-sm text-center border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                   onchange="if(this.value >= 1 && this.value <= {{ $ukuran->lastPage() }}) { window.location.href = '{{ $ukuran->url(1) }}'.replace('page=1', 'page=' + this.value) }">
            <span class="text-sm text-gray-600 dark:text-gray-400">dari {{ $ukuran->lastPage() }}</span>
        </div>
        @endif
    </div>

    {{-- Mobile-Only Simplified Pagination --}}
    <div class="sm:hidden mt-4 mx-auto max-w-7xl px-4">
        @if ($ukuran->hasPages())
        <div class="flex justify-center space-x-2">
            {{-- Previous --}}
            @if (!$ukuran->onFirstPage())
                <a href="{{ $ukuran->previousPageUrl() }}"
                   class="flex-1 px-4 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                    ← Sebelumnya
                </a>
            @endif

            {{-- Current Page Info --}}
            <div class="flex-1 px-4 py-2 text-sm bg-gradient-to-r from-red-500 to-red-600 text-white rounded-lg text-center font-semibold shadow-md">
                {{ $ukuran->currentPage() }} / {{ $ukuran->lastPage() }}
            </div>

            {{-- Next --}}
            @if ($ukuran->hasMorePages())
                <a href="{{ $ukuran->nextPageUrl() }}"
                   class="flex-1 px-4 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg text-center hover:bg-gray-50 dark:hover:bg-gray-700 transition focus:outline-none focus:ring-2 focus:ring-red-500">
                    Selanjutnya →
                </a>
            @endif
        </div>
        @endif
    </div>

    {{-- Modal Tambah Ukuran --}}
    <div x-show="tambahModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto custom-scrollbar" @click.outside="tambahModal = false"
            x-data="ukuranForm('', '')"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="p-4 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Tambah Ukuran</h2>
                    <button @click="tambahModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('ukuran_store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="produk_id_add" class="block font-medium mb-2 text-sm md:text-base text-gray-700 dark:text-gray-300">Pilih Produk</label>
                        <select name="produk_id" id="produk_id_add" x-model="selectedProdukId" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm md:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">-- Pilih Produk --</option>
                            <template x-for="prod in allProduk" :key="prod.id">
                                <option :value="prod.id" x-text="prod.nama_produk"></option>
                            </template>
                        </select>
                    </div>

                    <div>
                        <label for="jenis_kain_id_add" class="block font-medium mb-2 text-sm md:text-base text-gray-700 dark:text-gray-300">Pilih Jenis Kain</label>
                        <select name="jenis_kain_id" id="jenis_kain_id_add" x-model="selectedJenisKainId" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm md:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="">-- Pilih Jenis Kain --</option>
                            <template x-for="kain in filteredJenisKain" :key="kain.id">
                                <option :value="kain.id" x-text="kain.nama_kain"></option>
                            </template>
                        </select>
                    </div>

                    <div x-data="{ ukuranList: [''] }">
                        <label class="block font-medium mb-2 text-sm md:text-base text-gray-700 dark:text-gray-300">Ukuran Produk</label>

                        <template x-for="(ukuran, index) in ukuranList" :key="index">
                            <div class="flex space-x-2 mb-3 items-center">
                                <input type="text" :name="'nama_ukuran[]'" x-model="ukuranList[index]" required
                                        class="flex-1 border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm md:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                        placeholder="Contoh: M, L, XL"/>
                                <button type="button" @click="ukuranList.splice(index, 1)"
                                        x-show="ukuranList.length > 1"
                                        class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </template>

                        <button type="button" @click="ukuranList.push('')"
                                class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Tambah Ukuran
                        </button>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2 mt-6 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="tambahModal = false"
                                class="w-full sm:w-auto px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-500">
                            Batal
                        </button>
                        <button type="submit"
                                class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-600 hover:to-red-700 transition shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-red-500">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal Edit Ukuran --}}
    <div x-show="editModal" x-cloak x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-lg max-h-[90vh] overflow-y-auto custom-scrollbar" @click.outside="editModal = false"
            x-data="ukuranForm(editData.produk_id, editData.jenis_kain_id)"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="p-4 md:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Edit Ukuran</h2>
                    <button @click="editModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-gray-500 rounded-full p-1">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form :action="'{{ url('ukuran') }}/' + editData.id" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="produk_id_edit" class="block font-medium mb-2 text-sm md:text-base text-gray-700 dark:text-gray-300">Pilih Produk</label>
                        <select name="produk_id" id="produk_id_edit" x-model="selectedProdukId" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm md:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <template x-for="prod in allProduk" :key="prod.id">
                                <option :value="prod.id" x-text="prod.nama_produk"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label for="jenis_kain_id_edit" class="block font-medium mb-2 text-sm md:text-base text-gray-700 dark:text-gray-300">Pilih Jenis Kain</label>
                        <select name="jenis_kain_id" id="jenis_kain_id_edit" x-model="selectedJenisKainId" required class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm md:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <template x-for="kain in filteredJenisKain" :key="kain.id">
                                <option :value="kain.id" x-text="kain.nama_kain"></option>
                            </template>
                        </select>
                    </div>
                    <div>
                        <label for="nama_ukuran_edit" class="block font-medium mb-2 text-sm md:text-base text-gray-700 dark:text-gray-300">Nama Ukuran</label>
                        <input type="text" name="nama_ukuran" id="nama_ukuran_edit" x-model="editData.nama_ukuran" required
                               class="w-full border-gray-300 dark:border-gray-600 rounded-lg p-2 text-sm md:text-base focus:ring-2 focus:ring-red-500 focus:border-red-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                               placeholder="Contoh: M, L, XL"/>
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-2 pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="button" @click="editModal = false" class="w-full sm:w-auto px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition focus:outline-none focus:ring-2 focus:ring-gray-500">Batal</button>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-lg hover:from-amber-400 hover:to-amber-500 transition shadow-lg hover:shadow-xl focus:outline-none focus:ring-2 focus:ring-amber-500">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
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
