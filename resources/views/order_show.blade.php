@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 pb-10">
        <div class="bg-gradient-to-r from-gray-900 to-black text-white dark:from-gray-700 dark:to-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold">Detail Order</h1>
                        <p class="text-gray-300 mt-2">Informasi lengkap pesanan Anda</p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="bg-red-600 dark:bg-red-800 px-4 py-2 rounded-lg shadow-md">
                            <span class="font-semibold">Order #{{ $order->kode_order }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="sm:hidden mb-6">
                <div class="bg-red-600 dark:bg-red-800 text-white px-4 py-2 rounded-lg text-center shadow-md">
                    <span class="font-semibold">Order #{{ $order->kode_order }}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-red-600 dark:bg-red-700 rounded-full flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white ml-3">Customer</h3>
                    </div>
                    <div class="space-y-2 text-gray-700 dark:text-gray-300">
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->customer->nama ?? '-' }}</p>
                        <p class="flex items-center">
                            <svg class="w-4 h-4 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            {{ $order->customer->nomor_telepon ?? '-' }}
                        </p>
                        <p class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-1 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="leading-tight">{{ $order->customer->alamat ?? '-' }}</span>
                        </p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-red-600 dark:bg-red-700 rounded-full flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white ml-3">Produk</h3>
                    </div>
                    <div class="space-y-2 text-gray-700 dark:text-gray-300">
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $order->produk->nama_produk ?? '-' }}</p>
                        <p>
                            <span class="font-medium">Jenis Kain:</span> {{ $order->jenisKain->nama_kain ?? '-' }}
                        </p>
                        <p>
                            <span class="font-medium">Jasa Tambahan:</span> {{ ucfirst($order->jasa_tambahan) }}
                            @if($order->jasa_tambahan !== 'tidak')
                                (Rp{{ number_format($order->fee_tambahan, 0, ',', '.') }})
                            @endif
                        </p>
                        @if ($order->desain_file)
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <a href="{{ asset('storage/' . $order->desain_file) }}" target="_blank"
                                   class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 font-medium underline transition-colors">
                                    Lihat Desain
                                </a>
                            </div>
                        @endif
                        @if ($order->catatan_desain)
                            <div class="bg-gray-50 dark:bg-gray-700/30 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    <span class="font-medium">Catatan Desain:</span> {{ $order->catatan_desain }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-red-600 dark:bg-red-700 rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white ml-3">Ringkasan Pembayaran</h3>
                </div>
                
                <div class="space-y-4 text-gray-700 dark:text-gray-300">
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 dark:text-gray-400">Total Harga:</span>
                        <span class="text-xl font-bold text-gray-900 dark:text-white">Rp{{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                    
                    @if ($order->jasa_tambahan && $order->jasa_tambahan !== 'tidak')
                        <div class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Jasa Tambahan ({{ ucfirst($order->jasa_tambahan) }}):</span>
                            <span class="font-semibold">Rp{{ number_format($order->fee_tambahan, 0, ',', '.') }}</span>
                        </div>
                    @endif
                    
                    <div class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-700">
                        <span class="text-gray-600 dark:text-gray-400">DP:</span>
                        <span class="font-semibold">Rp{{ number_format($order->dp_nominal, 0, ',', '.') }}</span>
                    </div>

                    @if ($order->bukti_pembayaran)
                        <div class="bg-green-50 dark:bg-green-900/10 p-4 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="text-green-800 dark:text-green-300 font-medium">Bukti Pembayaran:</span>
                                <a href="{{ asset('storage/' . $order->bukti_pembayaran) }}" target="_blank"
                                   class="text-green-600 dark:text-green-400 hover:text-green-800 dark:hover:text-green-300 font-medium underline transition-colors">
                                    Lihat Bukti
                                </a>
                            </div>
                        </div>
                    @else
                        <div class="bg-red-50 dark:bg-red-900/10 p-4 rounded-lg border border-red-200 dark:border-red-700">
                            <form action="{{ route('orderupload_dp', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="bukti_pembayaran">
                                        Upload Bukti DP
                                    </label>
                                    <input type="file"
                                           name="bukti_pembayaran"
                                           id="bukti_pembayaran"
                                           accept="image/*,application/pdf"
                                           required
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 dark:file:bg-red-900/20 dark:file:text-red-300 hover:file:bg-red-100 dark:hover:file:bg-red-800/20">
                                </div>
                                <button type="submit"
                                        class="w-full sm:w-auto bg-red-600 dark:bg-red-700 text-white px-6 py-2 rounded-lg hover:bg-red-700 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors font-medium shadow-md">
                                    Upload Bukti DP
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            @if ($order->distribusi)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mb-8">
                    <div class="flex items-center mb-6">
                        <div class="w-10 h-10 bg-red-600 dark:bg-red-700 rounded-full flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h2m10-2h2a2 2 0 002-2V6a2 2 0 00-2-2h-2m-6 2h6m-6 4h6m-6 4h6"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white ml-3">Ringkasan Distribusi & Pelunasan</h3>
                    </div>

                    <div class="space-y-4 text-gray-700 dark:text-gray-300">
                        <div class="flex justify-between items-center py-2">
                            <span class="text-gray-600 dark:text-gray-400">Status Pelunasan:</span>
                            @if ($order->distribusi->status_pelunasan === 'lunas')
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Lunas</span>
                            @else
                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Belum Lunas</span>
                            @endif
                        </div>
                        @if ($order->distribusi->pelunasan_nominal)
                            <div class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Nominal Pelunasan:</span>
                                <span class="font-semibold">Rp{{ number_format($order->distribusi->pelunasan_nominal, 0, ',', '.') }}</span>
                            </div>
                        @endif
                        @if ($order->distribusi->bukti_pelunasan)
                            <div class="flex items-center space-x-2 py-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Bukti Pelunasan:</span>
                                <a href="{{ asset('storage/' . $order->distribusi->bukti_pelunasan) }}" target="_blank"
                                   class="text-blue-600 dark:text-blue-400 hover:underline">Lihat Bukti</a>
                            </div>
                        @endif
                        <div class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-gray-600 dark:text-gray-400">Metode Pengiriman:</span>
                            <span class="font-semibold capitalize">{{ str_replace('_', ' ', $order->distribusi->metode_pengiriman) }}</span>
                        </div>
                        @if ($order->distribusi->nomor_resi)
                            <div class="flex justify-between items-center py-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Nomor Resi:</span>
                                <span class="font-semibold">{{ $order->distribusi->nomor_resi }}</span>
                            </div>
                        @endif
                        @if ($order->distribusi->bukti_pengambilan)
                            <div class="flex items-center space-x-2 py-2 border-t border-gray-200 dark:border-gray-700">
                                <span class="text-gray-600 dark:text-gray-400">Bukti Pengambilan:</span>
                                <a href="{{ asset('storage/' . $order->distribusi->bukti_pengambilan) }}" target="_blank"
                                   class="text-blue-600 dark:text-blue-400 hover:underline">Lihat Bukti</a>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Form Upload Bukti Pelunasan akan muncul HANYA JIKA status_pelunasan BUKAN 'lunas' --}}
                    @if ($order->distribusi->status_pelunasan !== 'lunas')
                        <div class="mt-6 bg-red-50 dark:bg-red-900/10 p-4 rounded-lg border border-red-200 dark:border-red-700">
                            <form action="{{ route('orderdistribusi_upload_bukti', $order->distribusi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2" for="bukti_pelunasan">
                                        Upload Bukti Pelunasan
                                    </label>
                                    <input type="file"
                                           name="bukti_pelunasan"
                                           id="bukti_pelunasan"
                                           accept="image/*,application/pdf"
                                           required
                                           class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500 transition-colors bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-red-50 file:text-red-700 dark:file:bg-red-900/20 dark:file:text-red-300 hover:file:bg-red-100 dark:hover:file:bg-red-800/20">
                                </div>
                                <button type="submit"
                                        class="w-full sm:w-auto bg-red-600 dark:bg-red-700 text-white px-6 py-2 rounded-lg hover:bg-red-700 dark:hover:bg-red-800 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors font-medium shadow-md">
                                    Upload Bukti Pelunasan
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6 border border-gray-200 dark:border-gray-700 mb-8">
                <div class="flex items-center mb-6">
                    <div class="w-10 h-10 bg-red-600 dark:bg-red-700 rounded-full flex items-center justify-center shadow-md">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white ml-3">Log Produksi</h3>
                </div>
                
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full text-sm text-gray-800 dark:text-gray-200">
                        <thead>
                            <tr class="bg-gray-900 dark:bg-gray-700 text-white">
                                <th class="px-4 py-3 text-left rounded-tl-lg">Tahapan</th>
                                <th class="px-4 py-3 text-left">Status</th>
                                <th class="px-4 py-3 text-left">Pekerja</th>
                                <th class="px-4 py-3 text-left">Catatan</th>
                                <th class="px-4 py-3 text-left">Mulai</th>
                                <th class="px-4 py-3 text-left rounded-tr-lg">Selesai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($order->orderProduksiLogs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">{{ ucfirst($log->tahapan) }}</td>
                                    <td class="px-4 py-3">
                                        @php
                                            $statusClass = '';
                                            switch ($log->status) {
                                                case 'menunggu':
                                                case 'belum_dikerjakan':
                                                    $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300';
                                                    break;
                                                case 'dikerjakan':
                                                    $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300';
                                                    break;
                                                case 'selesai':
                                                    $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300';
                                                    break;
                                                default:
                                                    $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700/50 dark:text-gray-300';
                                                    break;
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                            {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $log->pekerja->nama ?? '-' }}</td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-300">{{ $log->catatan ?? '-' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $log->mulai ? \Carbon\Carbon::parse($log->mulai)->format('d M Y H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-300">
                                        {{ $log->selesai ? \Carbon\Carbon::parse($log->selesai)->format('d M Y H:i') : '-' }}
                                    </td>
                                </tr>
                                @if ($log->file_bukti)
                                    <tr class="bg-gray-50 dark:bg-gray-700/50">
                                        <td colspan="6" class="px-4 py-2 text-sm text-gray-700 dark:text-gray-300">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                <span>Bukti Produksi:</span>
                                                <a href="{{ asset('storage/' . $log->file_bukti) }}" target="_blank" class="ml-2 text-red-600 dark:text-red-400 hover:underline">Lihat Bukti</a>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada log produksi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="lg:hidden space-y-4">
                    @forelse ($order->orderProduksiLogs as $log)
                        <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
                            <div class="flex justify-between items-start mb-3">
                                <h4 class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($log->tahapan) }}</h4>
                                @php
                                    $statusClass = '';
                                    switch ($log->status) {
                                        case 'menunggu':
                                        case 'belum_dikerjakan':
                                            $statusClass = 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300';
                                            break;
                                        case 'dikerjakan':
                                            $statusClass = 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300';
                                            break;
                                        case 'selesai':
                                            $statusClass = 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300';
                                            break;
                                        default:
                                            $statusClass = 'bg-gray-100 text-gray-800 dark:bg-gray-700/50 dark:text-gray-300';
                                            break;
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                                </span>
                            </div>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                @if ($log->pekerja)
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Pekerja:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $log->pekerja->nama ?? '-' }}</p>
                                    </div>
                                @endif
                                @if ($log->catatan)
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Catatan:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $log->catatan }}</p>
                                    </div>
                                @endif
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Mulai:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $log->mulai ? \Carbon\Carbon::parse($log->mulai)->format('d M Y H:i') : '-' }}</p>
                                    </div>
                                    <div>
                                        <span class="text-gray-600 dark:text-gray-400">Selesai:</span>
                                        <p class="text-gray-900 dark:text-white">{{ $log->selesai ? \Carbon\Carbon::parse($log->selesai)->format('d M Y H:i') : '-' }}</p>
                                    </div>
                                </div>
                                @if ($log->file_bukti)
                                    <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                        <span class="text-gray-600 dark:text-gray-400">Bukti:</span>
                                        <a href="{{ asset('storage/' . $log->file_bukti) }}" target="_blank" class="ml-2 text-red-600 dark:text-red-400 hover:underline">Lihat Bukti</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4 text-gray-500 dark:text-gray-400">Belum ada log produksi.</div>
                    @endforelse
                </div>
            </div>

            <div class="flex justify-center mt-8">
                <a href="{{ route('order_index') }}"
                   class="inline-flex items-center px-6 py-3 bg-gray-900 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-800 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors font-medium shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>
@endsection