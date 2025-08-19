@extends('layouts.app')

@section('title', 'Harga')
@section('header', 'Manajemen Harga')

@section('content')
    <style>
        /* Custom styles for enhanced aesthetics */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            /* Light gray background */
        }

        .card-shadow {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .gradient-button {
            background-image: linear-gradient(to right, var(--tw-gradient-stops));
        }

        .gradient-button:hover {
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .table-header-gradient {
            background-image: linear-gradient(to right, var(--tw-gradient-stops));
        }

        /* Ensure modal content scrolls if it's too tall */
        .max-h-[90vh] {
            max-height: 90vh;
        }

        .overflow-y-auto {
            overflow-y: auto;
        }

        /* Mobile-specific table styling (card view) */
        @media (max-width: 639px) {

            /* Tailwind's 'sm' breakpoint is 640px */
            .table-auto-layout table,
            .table-auto-layout thead,
            .table-auto-layout tbody,
            .table-auto-layout th,
            .table-auto-layout td,
            .table-auto-layout tr {
                display: block;
            }

            .table-auto-layout thead tr {
                position: absolute;
                top: -9999px;
                left: -9999px;
            }

            .table-auto-layout tr {
                border: 1px solid #e2e8f0;
                /* border-gray-200 */
                margin-bottom: 1rem;
                /* space between cards */
                border-radius: 0.75rem;
                /* rounded-xl */
                padding: 1rem;
                background-color: #ffffff;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            }

            .table-auto-layout td {
                border: none;
                position: relative;
                padding-left: 50%;
                /* Space for the label */
                text-align: right;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }

            .table-auto-layout td:before {
                content: attr(data-label);
                position: absolute;
                left: 1rem;
                width: calc(50% - 1rem);
                padding-right: 10px;
                white-space: nowrap;
                text-align: left;
                font-weight: 600;
                /* font-semibold */
                color: #4a5568;
                /* text-gray-700 */
            }

            .table-auto-layout td:last-child {
                text-align: left;
                /* Align action buttons to the left */
            }
        }
    </style>

    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg shadow-md">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white card-shadow rounded-2xl p-6 border border-gray-200">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-6 gap-4">
            <h2 class="text-2xl font-extrabold text-gray-900 flex-grow">Data Harga Produk</h2>

            <!-- Modal Tambah Harga -->
            <div x-data="hargaForm()" x-init="init()" x-cloak>
                <a @click="open = true"
                    class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white text-base font-semibold rounded-xl shadow-lg hover:from-green-600 hover:to-green-700 transition transform hover:scale-105 cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                            clip-rule="evenodd" />
                    </svg>
                    Tambah Harga
                </a>

                <div x-show="open" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50 p-4">
                    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 ease-out"
                        x-transition:enter="opacity-0 scale-90" x-transition:enter-start="opacity-0 scale-90"
                        x-transition:enter-end="opacity-100 scale-100" x-transition:leave="opacity-100 scale-100"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90"
                        @click.outside="open = false">
                        <h2 class="text-2xl font-bold mb-6 text-gray-800">Tambah Harga Baru</h2>

                        <form action="{{ route('harga_store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label for="produk_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih
                                    Produk</label>
                                <select id="produk_id" name="produk_id" x-model="produk_id" @change="filterJenisKain()"
                                    required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Produk --</option>
                                    @foreach ($produk as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="jenis_kain_id" class="block text-sm font-medium text-gray-700 mb-1">Pilih Jenis
                                    Kain</label>
                                <select id="jenis_kain_id" name="jenis_kain_id" x-model="jenis_kain_id"
                                    @change="filterUkuran()" required
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">-- Pilih Kain --</option>
                                    <template x-for="kain in filteredJenisKain" :key="kain.id">
                                        <option :value="kain.id" x-text="kain.nama_kain"></option>
                                    </template>
                                </select>
                            </div>

                            <div x-show="filteredUkuran.length > 0">
                                <label class="block text-sm font-bold text-gray-800 mb-3">Input Harga</label>
                                <template x-for="u in filteredUkuran" :key="u.id">
                                    <div class="mb-5 p-4 border border-gray-200 rounded-xl bg-white shadow-sm">
                                        <div class="mb-2">
                                            <p class="text-base font-semibold text-gray-900"
                                                x-text="'Ukuran: ' + u.nama_ukuran"></p>
                                        </div>
                                        <div class="space-y-3">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga
                                                    Lusinan</label>
                                                <input :name="'harga_lusinan[' + u.id + ']'" type="number" required
                                                    min="0"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan
                                                    (Opsional)</label>
                                                <input :name="'harga_satuan[' + u.id + ']'" type="number" min="0"
                                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6">
                                <button type="button" @click="open = false"
                                    class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition transform hover:scale-105">Batal</button>
                                <button type="submit"
                                    class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition transform hover:scale-105">Simpan
                                    Harga</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div x-data="searchFilter()" x-init="initSearch()"
            class="mb-6 p-5 bg-gray-50 rounded-xl border border-gray-100 shadow-inner">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="search_produk" class="block text-sm font-medium text-gray-700 mb-1">Cari Produk</label>
                    <select id="search_produk" x-model="searchProdukId" @change="filterTable()"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Produk --</option>
                        @foreach ($produk as $item)
                            <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="search_jenis_kain" class="block text-sm font-medium text-gray-700 mb-1">Cari Jenis
                        Kain</label>
                    <select id="search_jenis_kain" x-model="searchJenisKainId" @change="filterTable()"
                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Semua Jenis Kain --</option>
                        <template x-for="kain in filteredSearchJenisKain" :key="kain.id">
                            <option :value="kain.id" x-text="kain.nama_kain"></option>
                        </template>
                    </select>
                </div>
                <div class="flex justify-end md:justify-start">
                    <button @click="resetFilters()"
                        class="px-5 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition transform hover:scale-105 w-full md:w-auto">
                        Lihat Semua Harga
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel Harga -->
        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-md table-auto-layout">
            <table class="min-w-full text-sm text-gray-800">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-100 to-gray-200 border-b table-header-gradient">
                        <th class="py-3 px-4 font-semibold text-left text-gray-700">No</th>
                        <th class="py-3 px-4 font-semibold text-left text-gray-700">Produk</th>
                        <th class="py-3 px-4 font-semibold text-left text-gray-700">Jenis Kain</th>
                        <th class="py-3 px-4 font-semibold text-left text-gray-700">Ukuran</th>
                        <th class="py-3 px-4 font-semibold text-left text-gray-700">Harga Lusinan</th>
                        <th class="py-3 px-4 font-semibold text-left text-gray-700">Harga Satuan</th>
                        <th class="py-3 px-4 font-semibold text-left text-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody x-ref="hargaTableBody">
                    @forelse ($harga as $index => $item)
                        <tr class="border-b hover:bg-gray-50 transition-colors duration-150"
                            data-produk-id="{{ $item->produk->id ?? '' }}"
                            data-jenis-kain-id="{{ $item->jenisKain->id ?? '' }}">
                            <td class="py-3 px-4" data-label="No">{{ $index + 1 }}</td>
                            <td class="py-3 px-4" data-label="Produk">{{ $item->produk->nama_produk ?? '-' }}</td>
                            <td class="py-3 px-4" data-label="Jenis Kain">{{ $item->jenisKain->nama_kain ?? '-' }}</td>
                            <td class="py-3 px-4" data-label="Ukuran">{{ $item->ukuran->nama_ukuran ?? '-' }}</td>
                            <td class="py-3 px-4" data-label="Harga">Rp{{ number_format($item->harga, 0, ',', '.') }}
                            <td class="py-3 px-4" data-label="Harga">Rp{{ number_format($item->harga_satuan, 0, ',', '.') }}
                            </td>
                            <td class="py-3 px-4 flex flex-col sm:flex-row gap-2" data-label="Aksi">
                                <!-- Edit Modal -->
                                <div x-data="{ open: false }">
                                    <button @click="open = true"
                                        class="px-4 py-2 text-xs bg-yellow-400 text-white rounded-lg hover:bg-yellow-500 transition transform hover:scale-105 w-full sm:w-auto">Edit</button>
                                    <div x-show="open"
                                        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                                        <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto transform transition-all duration-300 ease-out"
                                            x-transition:enter="opacity-0 scale-90"
                                            x-transition:enter-start="opacity-0 scale-90"
                                            x-transition:enter-end="opacity-100 scale-100"
                                            x-transition:leave="opacity-100 scale-100"
                                            x-transition:leave-start="opacity-100 scale-100"
                                            x-transition:leave-end="opacity-0 scale-90" @click.outside="open = false">
                                            <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Harga</h2>
                                            <form action="{{ route('harga_update', $item->id) }}" method="POST"
                                                class="space-y-5">
                                                @csrf
                                                @method('PUT')

                                                <div>
                                                    <label for="edit_harga_lusinan"
                                                        class="block text-sm font-medium text-gray-700 mb-1">Harga
                                                        Lusinan</label>
                                                    <input id="edit_harga_lusinan" type="number" name="harga"
                                                        value="{{ $item->harga }}" required min="0"
                                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500 p-2" />
                                                </div>
                                                <div>
                                                    <label for="edit_harga_satuan"
                                                        class="block text-sm font-medium text-gray-700 mb-1">Harga Satuan
                                                        (Opsional)</label>
                                                    <input id="edit_harga_satuan" type="number" name="harga_satuan"
                                                        value="{{ $item->harga_satuan ?? '' }}" min="0"
                                                        class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-yellow-500 focus:border-yellow-500 p-2" />
                                                </div>

                                                <div class="flex justify-end space-x-3 mt-6">
                                                    <button type="button" @click="open = false"
                                                        class="px-5 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition transform hover:scale-105">Batal</button>
                                                    <button type="submit"
                                                        class="px-5 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition transform hover:scale-105">Simpan
                                                        Perubahan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hapus -->
                                <form action="{{ route('harga_destroy', $item->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus data harga ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="px-4 py-2 text-xs bg-red-500 text-white rounded-lg hover:bg-red-600 transition transform hover:scale-105 w-full sm:w-auto">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 text-center text-gray-500">Belum ada data harga.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <script>
        // Alpine.js for Add Harga Modal
        function hargaForm() {
            return {
                open: false,
                produk_id: '',
                jenis_kain_id: '',
                allJenisKain: @json($jenis_kain),
                allUkuran: @json($ukuran),
                filteredJenisKain: [],
                filteredUkuran: [],

                init() {
                    this.filteredJenisKain = [];
                    this.filteredUkuran = [];
                },

                filterJenisKain() {
                    this.filteredJenisKain = this.allJenisKain.filter(kain => kain.produk_id == this.produk_id);
                    this.jenis_kain_id = ''; // Reset jenis_kain_id when produk changes
                    this.filteredUkuran = []; // Clear ukuran when produk changes
                },

                filterUkuran() {
                    this.filteredUkuran = this.allUkuran.filter(u => u.produk_id == this.produk_id && u.jenis_kain_id ==
                        this.jenis_kain_id);
                }
            }
        }

        // Alpine.js for Search and Filter
        function searchFilter() {
            return {
                searchProdukId: '',
                searchJenisKainId: '',
                allJenisKain: @json($jenis_kain), // Re-use from backend data
                filteredSearchJenisKain: [],
                allHargaData: [], // To store all initial harga data for client-side filtering

                initSearch() {
                    // Populate initial filtered search jenis kain based on default searchProdukId (empty)
                    this.filteredSearchJenisKain = this.allJenisKain;
                    // Store all table rows for filtering
                    this.allHargaData = Array.from(this.$refs.hargaTableBody.children);
                    this.$watch('searchProdukId', (value) => {
                        // When produk changes, re-filter jenis kain options for search
                        this.filteredSearchJenisKain = this.allJenisKain.filter(kain => !value || kain.produk_id ==
                            value);
                        this.searchJenisKainId = ''; // Reset jenis kain filter when produk changes
                        this.filterTable(); // Re-filter table
                    });
                },

                filterTable() {
                    const produkId = this.searchProdukId;
                    const jenisKainId = this.searchJenisKainId;

                    this.allHargaData.forEach(row => {
                        const rowProdukId = row.dataset.produkId;
                        const rowJenisKainId = row.dataset.jenisKainId;

                        let showRow = true;

                        if (produkId && rowProdukId !== produkId) {
                            showRow = false;
                        }

                        if (jenisKainId && rowJenisKainId !== jenisKainId) {
                            showRow = false;
                        }

                        row.style.display = showRow ? '' : 'none';
                    });
                },

                resetFilters() {
                    this.searchProdukId = '';
                    this.searchJenisKainId = '';
                    // The watch on searchProdukId will handle re-filtering jenis kain options and the table
                }
            }
        }
    </script>
@endsection
