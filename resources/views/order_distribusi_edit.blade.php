@extends('layouts.app')

@section('title', 'Edit Distribusi Order')
@section('header', 'Edit Distribusi Order')

@section('content')
    <div class="max-w-3xl mx-auto p-6 bg-white shadow rounded" x-data="{ metode: '{{ $distribusi->metode_pengiriman }}' }">
        <h2 class="text-xl font-bold mb-6">Edit Distribusi: {{ $distribusi->order->kode_order }}</h2>

        <form action="{{ route('orderdistribusi_update', $distribusi->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Metode Pengiriman --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Metode Pengiriman</label>
                <select name="metode_pengiriman" x-model="metode" class="mt-1 block w-full border rounded p-2" required>
                    <option value="antar">Diantar</option>
                    <option value="ambil">Ambil di Tempat</option>
                </select>
            </div>

            {{-- Ongkos Kirim --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Ongkos Kirim (Rp)</label>
                <input type="number" name="biaya_ongkir" x-bind:disabled="metode !== 'antar'"
                    :class="{ 'bg-gray-100 text-gray-500': metode !== 'antar' }"
                    class="mt-1 block w-full border rounded p-2"
                    value="{{ old('biaya_ongkir', $distribusi->biaya_ongkir) }}" min="0">
            </div>

            {{-- Pelunasan Nominal --}}
            @php
                $pelunasan = $distribusi->order->total_harga - $distribusi->order->dp_nominal + ($distribusi->biaya_ongkir ?? 0);
            @endphp
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nominal Pelunasan</label>
                <input type="number" value="{{ $pelunasan }}" readonly
                    class="mt-1 block w-full border rounded p-2 bg-gray-100 text-gray-700">
                <input type="hidden" name="pelunasan_nominal" value="{{ $pelunasan }}">
            </div>

            {{-- Nomor Resi --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nomor Resi</label>
                <input type="text" name="nomor_resi" x-bind:disabled="metode !== 'antar'"
                    :class="{ 'bg-gray-100 text-gray-500': metode !== 'antar' }"
                    class="mt-1 block w-full border rounded p-2"
                    value="{{ old('nomor_resi', $distribusi->nomor_resi) }}">
            </div>

            {{-- Keterangan --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Keterangan</label>
                <textarea name="keterangan" rows="3"
                    class="mt-1 block w-full border rounded p-2">{{ old('keterangan', $distribusi->keterangan) }}</textarea>
            </div>

            {{-- Bukti Pengambilan (opsional) --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Bukti Pengambilan</label>
                <input type="file" name="bukti_pengambilan" accept="image/*,.pdf"
                    class="mt-1 block w-full border rounded p-2">
                @if ($distribusi->bukti_pengambilan)
                    <p class="text-sm text-gray-600 mt-2">
                        Saat ini:
                        <a href="{{ asset('storage/' . $distribusi->bukti_pengambilan) }}" target="_blank"
                            class="text-green-600 underline">Lihat File</a>
                    </p>
                @endif
            </div>

            {{-- Tombol --}}
            <div class="text-right">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
