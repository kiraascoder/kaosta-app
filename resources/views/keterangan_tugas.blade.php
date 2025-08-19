@extends('layouts.app')

@section('title', 'Dashboard Pekerja')
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
                                <path d="M12 2l3.09 6.26L22 9l-5 4.87L18.18 21 12 17.77 5.82 21 7 13.87 2 9l6.91-.74L12 2z"/>
                            </svg>
                            Tugas Pekerja (Desain Grafis, Pemotong Kain, Penjahit, Sablon, Quality Control)
                        </h2>
                        <p class="text-red-100 mt-2">Melaksanakan tugas-tugas spesifik sesuai dengan tahapan produksi</p>
                    </div>
                    
                    <div class="p-6">
                        <!-- Task Item 1 -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-red-600 hover:bg-red-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-red-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 102 0V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 2a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">1. Melihat Tugas yang Ditugaskan</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Mengakses daftar tugas produksi yang saat ini sedang mereka kerjakan.
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
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">2. Melihat Tugas yang Tersedia</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Menemukan tugas-tugas produksi yang belum diambil oleh pekerja lain dan siap untuk dikerjakan pada tahapan mereka.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Task Item 3 -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500 hover:bg-blue-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-blue-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">3. Mengambil Tugas</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Mengklaim tugas yang tersedia untuk memulai pengerjaan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Tasks Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6">
                        <!-- Task Item 4 -->
                        <div class="mb-6 p-4 bg-gray-50 rounded-lg border-l-4 border-green-500 hover:bg-green-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-green-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">4. Menyelesaikan Tugas</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Menandai tugas sebagai selesai setelah pengerjaan, dengan opsi untuk menambahkan catatan dan mengunggah bukti hasil pekerjaan.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Task Item 5 -->
                        <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-purple-500 hover:bg-purple-50 transition-colors duration-200">
                            <div class="flex items-start space-x-4">
                                <div class="bg-purple-100 p-2 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zM14 6a2 2 0 012 2v4a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2h8zM6 8a2 2 0 000 4h8a2 2 0 000-4H6z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">5. Melihat Detail Tugas</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">
                                        Mengakses informasi rinci mengenai setiap tugas, termasuk detail order, produk, dan log produksi terkait.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection