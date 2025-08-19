@extends('layouts.pekerja')

@section('title', 'Dashboard Pekerja')
@section('header', 'Kaosta Parepare')

@section('content')

    {{-- Script Laravel Echo dan Socket.IO --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.Echo) {
                window.Echo.channel('notifications')
                    .listen('PekerjaanBaruEvent', (e) => {
                        console.log(e);
                    });
            }
        });
    </script>

    {{-- Kode HTML lainnya --}}
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Dashboard Pekerja</h1>
                <p class="text-gray-600 dark:text-gray-400">Kelola tugas produksi Anda dengan efisien</p>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                {{-- Bagian Tugas Saya --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            Tugas Saya
                        </h2>
                        <span
                            class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-1 rounded-full text-sm font-medium">
                            {{ count($tugasSaya) }} Tugas
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse ($tugasSaya as $log)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 overflow-hidden">
                                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-white font-semibold">{{ ucfirst($log->tahapan) }}</span>
                                        <span class="bg-white/20 text-white px-2 py-1 rounded-full text-xs">
                                            {{ ucfirst($log->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ $log->order->produk->nama_produk ?? '-' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Jenis Kain:
                                                {{ $log->order->jenisKain->nama_kain ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ukuran &
                                            Jumlah:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($log->order->orderDetails as $detail)
                                                <span
                                                    class="bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-sm border">
                                                    {{ $detail->ukuran->nama_ukuran ?? '-' }} ({{ $detail->jumlah }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if ($log->order->desain_file || $log->order->catatan_desain)
                                        <div class="border-t dark:border-gray-700 pt-4 space-y-3">
                                            @if ($log->order->desain_file)
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                        </path>
                                                    </svg>
                                                    <a href="{{ asset('storage/' . $log->order->desain_file) }}"
                                                        target="_blank"
                                                        class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">Lihat
                                                        Desain</a>
                                                </div>
                                            @endif
                                            @if ($log->order->catatan_desain)
                                                <div
                                                    class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-3">
                                                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                                        <strong>Catatan:</strong> {{ $log->order->catatan_desain }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="pt-4">
                                        @if ($log->status === 'dikerjakan')
                                            <form action="{{ route('pekerja_selesai', $log->id) }}" method="POST"
                                                enctype="multipart/form-data" class="space-y-4">
                                                @csrf
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan
                                                        (Opsional)
                                                    </label>
                                                    <textarea name="catatan" rows="2"
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white resize-none"></textarea>
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File
                                                        Bukti (Opsional)</label>
                                                    <input type="file" name="file_bukti" accept="image/*,.pdf"
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                                                    @error('file_bukti')
                                                        <span
                                                            class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <button type="submit"
                                                    class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105">
                                                    ✓ Selesaikan Tugas
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-center py-2">
                                                <span class="text-gray-500 dark:text-gray-400 text-sm">Status:
                                                    {{ ucfirst($log->status) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">Belum ada tugas yang sedang dikerjakan</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Bagian Tugas Tersedia --}}
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            Tugas Tersedia
                        </h2>
                        <span
                            class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-3 py-1 rounded-full text-sm font-medium">
                            {{ count($tugasTersedia) }} Tersedia
                        </span>
                    </div>

                    <div class="space-y-4">
                        @forelse ($tugasTersedia as $log)
                            <div
                                class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 overflow-hidden group">
                                <div class="bg-gradient-to-r from-green-500 to-emerald-600 px-6 py-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-white font-semibold">Tugas Baru</span>
                                        <div class="flex items-center space-x-2">
                                            <span class="bg-white/20 text-white px-2 py-1 rounded-full text-xs">
                                                {{ ucfirst($log->tahapan) }}
                                            </span>
                                            <div class="w-2 h-2 bg-green-300 rounded-full animate-pulse"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="p-6 space-y-4">
                                    <div class="flex items-start space-x-3">
                                        <div
                                            class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4">
                                                </path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-semibold text-gray-900 dark:text-white">
                                                {{ $log->order->produk->nama_produk ?? '-' }}</p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Jenis Kain:
                                                {{ $log->order->jenisKain->nama_kain ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Ukuran &
                                            Jumlah:</p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($log->order->orderDetails as $detail)
                                                <span
                                                    class="bg-white dark:bg-gray-600 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-sm border">
                                                    {{ $detail->ukuran->nama_ukuran ?? '-' }} ({{ $detail->jumlah }})
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if ($log->order->desain_file || $log->order->catatan_desain)
                                        <div class="border-t dark:border-gray-700 pt-4 space-y-3">
                                            @if ($log->order->desain_file)
                                                <div class="flex items-center space-x-2">
                                                    <svg class="w-4 h-4 text-green-500" fill="none"
                                                        stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                        </path>
                                                    </svg>
                                                    <a href="{{ asset('storage/' . $log->order->desain_file) }}"
                                                        target="_blank"
                                                        class="text-blue-600 dark:text-blue-400 hover:underline text-sm font-medium">Lihat
                                                        Desain</a>
                                                </div>
                                            @endif
                                            @if ($log->order->catatan_desain)
                                                <div
                                                    class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-3">
                                                    <p class="text-sm text-blue-800 dark:text-blue-200">
                                                        <strong>Catatan:</strong> {{ $log->order->catatan_desain }}
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="pt-4">
                                        <form action="{{ route('pekerja_terima', $log->id) }}" method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="w-full bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105 group-hover:shadow-lg">
                                                <span class="flex items-center justify-center space-x-2">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    <span>Ambil Tugas</span>
                                                </span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z">
                                    </path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">Tidak ada tugas yang tersedia saat ini</p>
                                <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Tugas baru akan muncul di sini</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (session('success') || session('error'))
        <div id="toast" class="fixed top-4 right-4 z-50 transform transition-all duration-300 translate-x-0">
            <div
                class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border-l-4 {{ session('success') ? 'border-green-500' : 'border-red-500' }} p-4 max-w-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if (session('success'))
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @else
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        @endif
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                            {{ session('success') ?? session('error') }}
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <button onclick="document.getElementById('toast').remove()"
                            class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <script>
            {{-- ✅ KOREKSI: Hanya panggil 'app.js' yang sudah mengimpor yang lain --}}
            @vite(['resources/css/app.css', 'resources/js/app.js'])
            // Auto hide toast after 5 seconds
            setTimeout(() => {
                const toast = document.getElementById('toast');
                if (toast) {
                    toast.classList.add('translate-x-full');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        </script>
    @endif
@endsection
