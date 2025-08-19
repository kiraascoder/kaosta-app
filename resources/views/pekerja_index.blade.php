@extends('layouts.app')

@section('title', 'Beranda')
@section('header', 'Daftar Pekerja di Kaosta')

@section('content')
    {{-- Container utama yang mengelola padding horizontal dan latar belakang --}}
    <div x-data="{
        tambahModal: false,
        editModal: false,
        pekerjaEdit: {},
        confirmDelete: false,
        deleteData: {},
        showDelete(id, nama, route) {
            this.confirmDelete = true;
            this.deleteData = { id, nama, route };
        },
        hideDelete() {
            this.confirmDelete = false;
            this.deleteData = {};
        },
        showEdit(pekerja) {
            this.pekerjaEdit = JSON.parse(JSON.stringify(pekerja));
            this.editModal = true;
        }
    }" class="min-h-screen bg-gray-50"> {{-- Hapus p-4 sm:p-6 di sini --}}

        <div class="container mx-auto px-4 py-6 lg:py-8"> {{-- Tambahkan container dan padding global di sini --}}

            {{-- Notifikasi Sukses --}}
            @if (session('success'))
                <div
                    class="bg-gradient-to-r from-green-50 to-green-100 border-l-4 border-green-400 text-green-800 p-4 rounded-lg mb-6 shadow-md animate-fade-in-down">
                    <div class="flex items-center">
                        <svg class="w-6 h-6 mr-3 flex-shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-base font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div x-show="confirmDelete" x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90"
                class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md transform transition-all duration-300"
                    @click.outside="hideDelete()">
                    <div class="p-6 text-center">
                        <div
                            class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4 animate-scale-in">
                            <svg class="h-8 w-8 text-red-600 animate-bounce-custom" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.232 16.5c-.77.833.192 2.5 1.732 2.5z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Konfirmasi Penghapusan</h3>
                        <p class="text-base text-gray-600 mb-1">Anda yakin ingin menghapus pekerja:</p>
                        <p x-text="deleteData.nama" class="text-lg font-semibold text-red-600 break-words"></p>
                        <div class="mt-4 p-3 bg-red-50 rounded-xl border border-red-200">
                            <div class="flex items-center text-red-800 text-sm">
                                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                <span>Data yang dihapus tidak dapat dikembalikan!</span>
                            </div>
                        </div>
                    </div>

                    <div class="px-6 pb-6">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <button type="button" @click="hideDelete()"
                                class="w-full sm:flex-1 px-4 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-all duration-200 font-medium transform hover:scale-105">
                                <span class="flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Batal
                                </span>
                            </button>

                            <form :action="deleteData.route" method="POST" class="w-full sm:flex-1">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:scale-105">
                                    <span class="flex items-center justify-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                        Ya, Hapus
                                    </span>
                                </button>
                            </form>
                        </div>
                        <div class="mt-4 text-center">
                            <p class="text-xs text-gray-500">Pastikan Anda sudah yakin dengan keputusan ini</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Header & Pencarian --}}
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6 sm:p-8 mb-8">
                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-6">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">Daftar Pekerja</h1>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 w-full lg:w-auto">
                        <form method="GET" action="{{ route('pekerja_index') }}" class="flex flex-grow gap-2">
                            <div class="relative flex-grow">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Cari nama / ID / role"
                                    class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                                <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 transform -translate-y-1/2"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <button type="submit"
                                class="px-6 py-3 bg-gray-900 text-white rounded-xl hover:bg-gray-800 transition-all duration-200 shadow-lg hover:shadow-xl font-medium flex-shrink-0">
                                Cari
                            </button>
                        </form>

                        <button @click="tambahModal = true"
                            class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg hover:shadow-xl font-medium flex items-center justify-center gap-2 flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Pekerja
                        </button>
                    </div>
                </div>
            </div>

            {{-- Tampilan Desktop/Tablet - Tabel --}}
            {{-- Mengubah breakpoint dari md:block menjadi lg:block --}}
            <div
                class="hidden lg:block bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden mb-8 flex flex-col">
                <div class="overflow-x-auto flex-grow">
                    <table class="w-full min-w-max divide-y divide-gray-200">
                        <thead class="bg-gradient-to-r from-gray-900 to-black">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider w-12">
                                    #</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider w-32">
                                    ID Pekerja
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider min-w-40">
                                    Nama</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider w-32">
                                    No HP
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider min-w-40">
                                    Email
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider min-w-60">
                                    Alamat
                                </th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-white uppercase tracking-wider w-32">
                                    Role</th>
                                <th
                                    class="px-6 py-4 text-center text-xs font-bold text-white uppercase tracking-wider w-40">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-100">
                            @forelse($pekerja as $index => $pk)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $pekerja->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $pk->pekerja_id }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                        {{ $pk->nama }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pk->nohp ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pk->email ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $pk->alamat ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span
                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                                {{ $pk->role == 'Sales'
                                                    ? 'bg-blue-100 text-blue-800'
                                                    : ($pk->role == 'Desain Grafis'
                                                        ? 'bg-purple-100 text-purple-800'
                                                        : ($pk->role == 'Pemotong Kain'
                                                            ? 'bg-green-100 text-green-800'
                                                            : ($pk->role == 'Penjahit'
                                                                ? 'bg-yellow-100 text-yellow-800'
                                                                : ($pk->role == 'Sablon'
                                                                    ? 'bg-indigo-100 text-indigo-800'
                                                                    : 'bg-pink-100 text-pink-800')))) }}">
                                            {{ $pk->role }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <div class="flex justify-center gap-2">
                                            <button
                                                @click="showEdit({
                                                        id: {{ $pk->id }},
                                                        nama: '{{ addslashes($pk->nama) }}',
                                                        role: '{{ $pk->role }}',
                                                        nohp: '{{ $pk->nohp }}',
                                                        email: '{{ $pk->email }}',
                                                        alamat: '{{ addslashes($pk->alamat) }}',
                                                        tanggal_masuk: '{{ $pk->tanggal_masuk }}'
                                                    })"
                                                class="inline-flex items-center px-3 py-2 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-semibold rounded-lg hover:bg-amber-100 transition-colors duration-150 transform hover:scale-105">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                    </path>
                                                </svg>
                                                Edit
                                            </button>
                                            <button
                                                @click="showDelete({{ $pk->id }}, '{{ addslashes($pk->nama) }}', '{{ route('pekerja_destroy', $pk->id) }}')"
                                                class="inline-flex items-center px-3 py-2 bg-red-50 border border-red-200 text-red-700 text-xs font-semibold rounded-lg hover:bg-red-100 transition-colors duration-150 transform hover:scale-105">
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
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center px-6 py-16">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                                </path>
                                            </svg>
                                            <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data pekerja</h3>
                                            <p class="text-gray-500">Mulai dengan menambahkan pekerja baru</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Tampilan Mobile - Cards --}}
            {{-- Mengubah breakpoint dari md:hidden menjadi lg:hidden --}}
            <div class="lg:hidden space-y-4 mb-8">
                @php
                    $roleColors = [
                        'Sales' => 'bg-blue-100 text-blue-800',
                        'Desain Grafis' => 'bg-purple-100 text-purple-800',
                        'Pemotong Kain' => 'bg-green-100 text-green-800',
                        'Penjahit' => 'bg-yellow-100 text-yellow-800',
                        'Sablon' => 'bg-indigo-100 text-indigo-800',
                        'Quality Control' => 'bg-pink-100 text-pink-800',
                    ];
                @endphp

                @forelse($pekerja as $index => $pk)
                    <div
                        class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-shadow duration-300 transform hover:-translate-y-0.5">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $pk->nama }}</h3>
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            ID: {{ $pk->pekerja_id }}
                                        </span>
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$pk->role] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $pk->role }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span
                                        class="text-sm font-medium text-gray-500">#{{ $pekerja->firstItem() + $index }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 gap-3 mb-4 text-gray-700">
                                @if ($pk->nohp)
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                                            </path>
                                        </svg>
                                        <span>{{ $pk->nohp }}</span>
                                    </div>
                                @endif
                                @if ($pk->email)
                                    <div class="flex items-center text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8m-9 6h.01M3 12h.01M21 12h.01M3 16h.01M21 16h.01M3 20h.01M21 20h.01M1 4h22a1 1 0 011 1v14a1 1 0 01-1 1H1a1 1 0 01-1-1V5a1 1 0 011-1z">
                                            </path>
                                        </svg>
                                        <span class="truncate">{{ $pk->email }}</span>
                                    </div>
                                @endif
                                @if ($pk->alamat)
                                    <div class="flex items-start text-sm">
                                        <svg class="w-4 h-4 mr-2 text-gray-500 mt-0.5 flex-shrink-0" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <span class="leading-relaxed">{{ $pk->alamat }}</span>
                                    </div>
                                @endif
                                <div class="flex items-center text-sm">
                                    <svg class="w-4 h-4 mr-2 text-gray-500 flex-shrink-0" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h.01M12 11h.01M15 11h.01M7 15h.01M11 15h.01M15 15h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <span class="text-gray-700">
                                        Tanggal Masuk:
                                        {{ $pk->tanggal_masuk ? \Carbon\Carbon::parse($pk->tanggal_masuk)->format('d M Y') : '-' }}
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-2 pt-3 border-t border-gray-100">
                                <button @click='showEdit(@json($pk))'
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-amber-50 border border-amber-200 text-amber-700 text-sm font-semibold rounded-xl hover:bg-amber-100 transition-colors duration-150 transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Edit
                                </button>
                                <button
                                    @click="showDelete({{ $pk->id }}, '{{ addslashes($pk->nama) }}', '{{ route('pekerja_destroy', $pk->id) }}')"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-red-50 border border-red-200 text-red-700 text-sm font-semibold rounded-xl hover:bg-red-100 transition-colors duration-150 transform hover:scale-105">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                        </path>
                                    </svg>
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16 text-gray-500">Belum ada data pekerja</div>
                @endforelse
            </div>

            {{-- Pagination --}}
            <div class="mt-8"> {{-- Hapus px-4 sm:px-0 di sini karena sudah di handle oleh container mx-auto --}}
                {{ $pekerja->appends(request()->query())->links('vendor.pagination.tailwind') }}
            </div>

            <div x-show="tambahModal"
                class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4" x-cloak
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
                    @click.outside="tambahModal = false">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">Tambah Pekerja Baru</h3>
                            <button @click="tambahModal = false"
                                class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form action="{{ route('pekerja_store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <label for="nama_tambah" class="block text-sm font-semibold text-gray-700 mb-2">Nama
                                    Lengkap</label>
                                <input type="text" id="nama_tambah" name="nama" required
                                    placeholder="Masukkan nama lengkap"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                            </div>

                            <div>
                                <label for="role_tambah"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Role/Posisi</label>
                                <select id="role_tambah" name="role" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                                    <option value="">-- Pilih Role --</option>
                                    <option>Sales</option>
                                    <option>Desain Grafis</option>
                                    <option>Pemotong Kain</option>
                                    <option>Penjahit</option>
                                    <option>Sablon</option>
                                    <option>Quality Control</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nohp_tambah" class="block text-sm font-semibold text-gray-700 mb-2">Nomor
                                        HP</label>
                                    <input type="text" id="nohp_tambah" name="nohp" placeholder="08xxxxxxxxxx"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                                </div>
                                <div>
                                    <label for="email_tambah"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" id="email_tambah" name="email"
                                        placeholder="email@example.com"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="alamat_tambah"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                                <textarea id="alamat_tambah" name="alamat" rows="3" placeholder="Masukkan alamat lengkap"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none shadow-sm"></textarea>
                            </div>

                            <div>
                                <label for="tanggal_masuk_tambah"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk</label>
                                <input type="date" id="tanggal_masuk_tambah" name="tanggal_masuk" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                            </div>

                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                                <button type="button" @click="tambahModal = false"
                                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors duration-150 font-medium transform hover:scale-105">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all duration-150 font-medium shadow-lg transform hover:scale-105">
                                    Simpan Pekerja
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div x-show="editModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 p-4"
                x-cloak x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-90">
                <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto"
                    @click.outside="editModal = false">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-bold text-gray-900">Edit Data Pekerja</h3>
                            <button @click="editModal = false"
                                class="text-gray-400 hover:text-gray-600 p-2 rounded-full hover:bg-gray-100 transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <form :action="'/pekerja/' + pekerjaEdit.id" method="POST" class="space-y-6">
                            @csrf
                            @method('PUT')
                            <div>
                                <label for="nama_edit" class="block text-sm font-semibold text-gray-700 mb-2">Nama
                                    Lengkap</label>
                                <input type="text" id="nama_edit" name="nama" x-model="pekerjaEdit.nama" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                            </div>

                            <div>
                                <label for="role_edit"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Role/Posisi</label>
                                <select id="role_edit" name="role" x-model="pekerjaEdit.role" required
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                                    <option value="">-- Pilih Role --</option>
                                    <option>Sales</option>
                                    <option>Desain Grafis</option>
                                    <option>Pemotong Kain</option>
                                    <option>Penjahit</option>
                                    <option>Sablon</option>
                                    <option>Quality Control</option>
                                </select>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nohp_edit" class="block text-sm font-semibold text-gray-700 mb-2">Nomor
                                        HP</label>
                                    <input type="text" id="nohp_edit" name="nohp" x-model="pekerjaEdit.nohp"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                                </div>
                                <div>
                                    <label for="email_edit"
                                        class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                                    <input type="email" id="email_edit" name="email" x-model="pekerjaEdit.email"
                                        class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                                </div>
                            </div>

                            <div>
                                <label for="alamat_edit"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                                <textarea id="alamat_edit" name="alamat" rows="3" x-model="pekerjaEdit.alamat"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none shadow-sm"></textarea>
                            </div>

                            <div>
                                <label for="tanggal_masuk_edit"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Masuk</label>
                                <input type="date" id="tanggal_masuk_edit" name="tanggal_masuk"
                                    x-model="pekerjaEdit.tanggal_masuk"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent shadow-sm">
                            </div>

                            <div class="flex justify-end gap-4 pt-6 border-t border-gray-100">
                                <button type="button" @click="editModal = false"
                                    class="px-6 py-3 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors duration-150 font-medium transform hover:scale-105">
                                    Batal
                                </button>
                                <button type="submit"
                                    class="px-6 py-3 bg-gradient-to-r from-gray-900 to-black text-white rounded-xl hover:from-gray-800 hover:to-gray-900 transition-all duration-150 font-medium shadow-lg transform hover:scale-105">
                                    Update Pekerja
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div> {{-- Penutup div container mx-auto --}}
    </div>
@endsection
