@extends('layouts.app')

@section('title', 'Daftar Order')
@section('header', 'Daftar Order')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
    </div>

    <div class="min-h-screen bg-gray-50">
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div
                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-6 space-y-4 sm:space-y-0">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gradient-to-r from-red-600 to-red-700 p-3 rounded-lg">
                            <svg class="w-6 h-6 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900">Daftar Order</h1>
                            <p class="text-gray-600 mt-1 text-sm sm:text-base">Kelola semua pesanan pelanggan Anda</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4 w-full sm:w-auto">
                        <a href="{{ route('order_create') }}"
                            class="w-full sm:w-auto inline-flex justify-center items-center px-4 sm:px-6 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transform hover:scale-105 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span class="whitespace-nowrap">Tambah Order</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
            <div class="hidden md:block bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Daftar Order</h3>
                    <p class="text-sm text-gray-600 mt-1">Menampilkan {{ $orders->count() }} order</p>
                </div>

                <div class="w-full overflow-auto">
                    <table class="w-full table-fixed divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Kode Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Produk</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status Produksi</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status Pembayaran</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($orders as $order)
                                @php
                                    // Logika status produksi yang diperbaiki
                                    $allLogs = $order->orderProduksiLogs;
                                    $isAllCompleted = $allLogs->every(fn($log) => $log->status === 'selesai');
                                    $isAnyProcessing = $allLogs->where('status', 'dikerjakan')->isNotEmpty();
                                    $isAnyPending = $allLogs->where('status', 'menunggu')->isNotEmpty();

                                    $statusProduksi = match (true) {
                                        $isAllCompleted => 'selesai',
                                        $isAnyProcessing => 'proses',
                                        $isAnyPending => 'menunggu_pekerja',
                                        default => 'belum_dimulai',
                                    };

                                    // Class warna untuk status produksi
                                    $statusProduksiClass = match ($statusProduksi) {
                                        'belum_dimulai', 'menunggu_pekerja' => 'bg-yellow-100 text-yellow-800',
                                        'proses' => 'bg-blue-100 text-blue-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };

                                    // Class warna untuk status pembayaran dari database
                                    $statusPembayaranClass = match ($order->status_pembayaran) {
                                        'Lunas' => 'bg-green-100 text-green-800',
                                        default => 'bg-red-100 text-red-800',
                                    };

                                    // Cek apakah semua tahapan produksi selesai untuk tombol 'Kirim ke Distribusi'
                                    $semuaTahapanSelesai = $allLogs->every(fn($log) => $log->status === 'selesai');
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->kode_order }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->customer->nama ?? '-' }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $order->customer->nomor_telepon ?? '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $order->produk->nama_produk ?? '-' }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->jenisKain->nama_kain ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusProduksiClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $statusProduksi)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusPembayaranClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $order->status_pembayaran)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium flex gap-2 items-center">
                                        <a href="{{ route('order_show', $order->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition">
                                            Detail
                                        </a>
                                        <form action="{{ route('order_destroy', $order->id) }}" method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus order ini?')" class="flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition">
                                                Hapus
                                            </button>
                                        </form>
                                        @if ($semuaTahapanSelesai && !$order->distribusi)
                                            <form action="{{ route('orderdistribusi_kirim', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition">
                                                    Kirim ke Distribusi
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="md:hidden space-y-4 px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Daftar Order</h3>
                    <p class="text-sm text-gray-600">Menampilkan {{ $orders->count() }} order</p>
                </div>

                @foreach ($orders as $order)
                    @php
                        // Logika status produksi yang diperbaiki
                        $allLogs = $order->orderProduksiLogs;
                        $isAllCompleted = $allLogs->every(fn($log) => $log->status === 'selesai');
                        $isAnyProcessing = $allLogs->where('status', 'dikerjakan')->isNotEmpty();
                        $isAnyPending = $allLogs->where('status', 'menunggu')->isNotEmpty();

                        $statusProduksi = match (true) {
                            $isAllCompleted => 'selesai',
                            $isAnyProcessing => 'proses',
                            $isAnyPending => 'menunggu_pekerja',
                            default => 'belum_dimulai',
                        };

                        $statusProduksiClass = match ($statusProduksi) {
                            'belum_dimulai', 'menunggu_pekerja' => 'bg-yellow-100 text-yellow-800',
                            'proses' => 'bg-blue-100 text-blue-800',
                            'selesai' => 'bg-green-100 text-green-800',
                            default => 'bg-gray-100 text-gray-800',
                        };

                        // Class warna untuk status pembayaran dari database
                        $statusPembayaranClass = match ($order->status_pembayaran) {
                            'Lunas' => 'bg-green-100 text-green-800',
                            default => 'bg-red-100 text-red-800',
                        };

                        $semuaTahapanSelesai = $allLogs->every(fn($log) => $log->status === 'selesai');
                    @endphp

                    <div
                        class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md transition-shadow duration-200">
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900">{{ $order->kode_order }}</h4>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('d/m/Y') }}</p>
                            </div>
                            <div class="flex flex-col items-end space-y-2">
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusPembayaranClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->status_pembayaran)) }}
                                </span>
                                <span
                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusProduksiClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $statusProduksi)) }}
                                </span>
                            </div>
                        </div>

                        <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Customer</span>
                            </div>
                            <p class="text-sm font-medium text-gray-900">{{ $order->customer->nama ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $order->customer->nomor_telepon ?? '-' }}</p>
                        </div>

                        <div class="mb-3 p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center mb-2">
                                <svg class="w-4 h-4 text-gray-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700">Produk</span>
                            </div>
                            <p class="text-sm font-medium text-gray-900">{{ $order->produk->nama_produk ?? '-' }}</p>
                            <p class="text-sm text-gray-600">{{ $order->jenisKain->nama_kain ?? '-' }}</p>
                        </div>

                        <div class="mb-4 p-3 bg-green-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700">Total Harga</span>
                                </div>
                                <span class="text-lg font-bold text-green-600">
                                    Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex flex-col space-y-2">
                            <a href="{{ route('order_show', $order->id) }}"
                                class="w-full inline-flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Detail Order
                            </a>

                            <div class="flex space-x-2">
                                @if ($semuaTahapanSelesai && !$order->distribusi)
                                    <form action="{{ route('orderdistribusi_kirim', $order->id) }}" method="POST"
                                        class="flex-1">
                                        @csrf
                                        <button type="submit"
                                            class="w-full inline-flex justify-center items-center px-4 py-2 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition font-medium">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                            Kirim ke Distribusi
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('order_destroy', $order->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus order ini?')"
                                    class="@if (!$semuaTahapanSelesai || $order->distribusi) flex-1 @endif">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-2 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
