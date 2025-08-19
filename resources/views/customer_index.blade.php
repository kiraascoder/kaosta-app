@extends('layouts.app')

@section('title', 'Customer')
@section('header', 'Data Customer')

@section('content')

@if (session('success'))
    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-500 text-green-800 rounded-lg shadow-sm">
        <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            {{ session('success') }}
        </div>
    </div>
@endif

<div class="bg-white shadow-2xl rounded-3xl border border-gray-100 overflow-hidden">
    <!-- Header dengan gradient -->
    <div class="bg-gradient-to-r from-gray-900 via-black to-red-600 px-6 py-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Customer Management</h1>
            </div>

            <!-- Modal Tambah Customer -->
            <div x-data="{ open: false }" x-cloak>
                <button @click="open = true" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah Customer
                </button>

                <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 flex items-center justify-center z-50 bg-black/60 backdrop-blur-sm p-4">
                    <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-md sm:max-w-lg" @click.outside="open = false">
                        <div class="flex items-center justify-between mb-6">
                            <h2 class="text-2xl font-bold text-gray-900">Tambah Customer</h2>
                            <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('customer_store') }}" method="POST" class="space-y-5">
                            @csrf

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Customer</label>
                                <input type="text" name="nama" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                                <input type="text" name="nomor_telepon" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition">
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                                <textarea name="alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 transition resize-none"></textarea>
                            </div>

                            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                                <button type="button" @click="open = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                                    Batal
                                </button>
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    Simpan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <div class="p-6">
        <!-- Desktop Table -->
        <div class="hidden md:block">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b-2 border-gray-200">
                            <th class="text-left py-4 px-6 font-bold text-gray-800 text-sm uppercase tracking-wider">No</th>
                            <th class="text-left py-4 px-6 font-bold text-gray-800 text-sm uppercase tracking-wider">Nama</th>
                            <th class="text-left py-4 px-6 font-bold text-gray-800 text-sm uppercase tracking-wider">Telepon</th>
                            <th class="text-left py-4 px-6 font-bold text-gray-800 text-sm uppercase tracking-wider">Alamat</th>
                            <th class="text-left py-4 px-6 font-bold text-gray-800 text-sm uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($customers as $index => $customer)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="py-4 px-6 text-sm font-medium text-gray-900">{{ $index + 1 }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center text-white font-bold text-sm mr-3">
                                        {{ strtoupper(substr($customer->nama, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900">{{ $customer->nama }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-sm text-gray-700">{{ $customer->nomor_telepon }}</td>
                            <td class="py-4 px-6 text-sm text-gray-700">{{ Str::limit($customer->alamat, 30) ?: '-' }}</td>
                            <td class="py-4 px-6">
                                <div class="flex space-x-2">
                                    <!-- Edit Button -->
                                    <div x-data="{ open: false }" class="inline-block">
                                        <button @click="open = true" class="px-3 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-white text-xs font-semibold rounded-lg shadow hover:shadow-md transform hover:scale-105 transition-all duration-200">
                                            Edit
                                        </button>
                                        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 flex items-center justify-center z-50 bg-black/60 backdrop-blur-sm p-4">
                                            <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-md sm:max-w-lg" @click.outside="open = false">
                                                <div class="flex items-center justify-between mb-6">
                                                    <h2 class="text-2xl font-bold text-gray-900">Edit Customer</h2>
                                                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <form action="{{ route('customer_update', $customer->id) }}" method="POST" class="space-y-5">
                                                    @csrf
                                                    @method('PUT')
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama</label>
                                                        <input type="text" name="nama" value="{{ $customer->nama }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                                                        <input type="text" name="nomor_telepon" value="{{ $customer->nomor_telepon }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                                        <input type="email" name="email" value="{{ $customer->email }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                                                    </div>
                                                    <div>
                                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                                                        <textarea name="alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition resize-none">{{ $customer->alamat }}</textarea>
                                                    </div>
                                                    <div class="flex flex-col sm:flex-row justify-end gap-3 pt-4">
                                                        <button type="button" @click="open = false" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Delete Button -->
                                    <form action="{{ route('customer_destroy', $customer->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-xs font-semibold rounded-lg shadow hover:shadow-md transform hover:scale-105 transition-all duration-200"
                                            onclick="return confirm('Yakin ingin menghapus customer ini?')">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-12">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum ada data customer</h3>
                                    <p class="text-gray-500">Tambah customer pertama dengan klik tombol di atas</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="block md:hidden space-y-4">
            @forelse ($customers as $index => $customer)
            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-md hover:shadow-lg transition-shadow">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-red-600 rounded-full flex items-center justify-center text-white font-bold text-lg mr-3">
                            {{ strtoupper(substr($customer->nama, 0, 1)) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">{{ $customer->nama }}</h3>
                            <p class="text-sm text-gray-600">Customer #{{ $index + 1 }}</p>
                        </div>
                    </div>
                </div>
                
                <div class="space-y-2 mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $customer->nomor_telepon }}
                    </div>
                    
                    
                    @if($customer->alamat)
                    <div class="flex items-start text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $customer->alamat }}
                    </div>
                    @endif
                </div>
                
                <div class="flex space-x-2">
                    <!-- Edit Button -->
                    <div x-data="{ open: false }" class="flex-1">
                        <button @click="open = true" class="w-full px-4 py-2 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-white text-sm font-semibold rounded-lg shadow hover:shadow-md transform hover:scale-105 transition-all duration-200">
                            Edit
                        </button>
                        <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 flex items-center justify-center z-50 bg-black/60 backdrop-blur-sm p-4">
                            <div x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 transform scale-100" x-transition:leave-end="opacity-0 transform scale-95" class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-md" @click.outside="open = false">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-xl font-bold text-gray-900">Edit Customer</h2>
                                    <button @click="open = false" class="text-gray-400 hover:text-gray-600 transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <form action="{{ route('customer_update', $customer->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama</label>
                                        <input type="text" name="nama" value="{{ $customer->nama }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                                        <input type="text" name="nomor_telepon" value="{{ $customer->nomor_telepon }}" required class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                                        <textarea name="alamat" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 transition resize-none">{{ $customer->alamat }}</textarea>
                                    </div>
                                    <div class="flex flex-col gap-3 pt-4">
                                        <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-yellow-400 to-yellow-500 hover:from-yellow-500 hover:to-yellow-600 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                            Simpan
                                        </button>
                                        <button type="button" @click="open = false" class="w-full px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-xl transition">
                                            Batal
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Delete Button -->
                    <form action="{{ route('customer_destroy', $customer->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-semibold rounded-lg shadow hover:shadow-md transform hover:scale-105 transition-all duration-200"
                            onclick="return confirm('Yakin ingin menghapus customer ini?')">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
            @empty
            <div class="text-center py-12">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum ada data customer</h3>
                    <p class="text-gray-500 text-center">Tambah customer pertama dengan klik tombol di atas</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection