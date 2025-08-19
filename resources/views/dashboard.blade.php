@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('header', 'Kaosta Parepare')

@section('content')
<div class="min-h-screen bg-gray-50">

    <!-- Main Content -->
    <div class="container mx-auto px-6 py-8">

        <!-- Main Content Sections -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Main Tasks -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Tugas Utama Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-red-600 to-black p-6">
                        <h2 class="text-xl font-bold text-white flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Tugas Admin & Sales
                        </h2>
                        <p class="text-red-100 mt-2">Pengelolaan operasional sistem secara menyeluruh</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Task Item 1 -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-red-600 hover:bg-red-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-red-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">1. Manajemen Pengguna</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Mengelola akun pekerja dan pelanggan, termasuk penambahan, pengeditan, dan penghapusan data pengguna.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Task Item 2 -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-black hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-gray-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-black" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">2. Manajemen Katalog Produk</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Mengelola daftar produk, jenis kain, ukuran, dan harga yang tersedia dalam sistem.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Task Item 3 -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500 hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-blue-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 2a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">3. Pengawasan Pesanan (Order)</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Memantau status pesanan pelanggan dari awal hingga akhir, termasuk konfirmasi pembayaran.
                                    </p>
                                </div>
                            </div>
                        </div>
                                               <!-- Task Item 4 -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-green-500 hover:bg-green-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">4. Manajemen Log Produksi Global</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Melihat semua tugas produksi yang ditugaskan kepada karyawan, memantau kemajuan setiap tahapan produksi (desain, potong, jahit, sablon, quality control), dan mengidentifikasi hambatan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Task Item 5 -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-purple-500 hover:bg-purple-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-purple-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                                        <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1V8a1 1 0 00-1-1h-3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">5. Manajemen Distribusi</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Mengelola proses pengiriman order yang sudah selesai ke pelanggan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Task Item 6 -->
                        <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-yellow-500 hover:bg-yellow-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-yellow-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">6. Pelaporan</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Mengakses ringkasan dan detail untuk analisis kinerja sistem secara keseluruhan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</div>
@endsection
