@extends('layouts.pekerja')

@section('content')
    <div class="max-w-5xl mx-auto py-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-800">Dashboard Pekerja: {{ $pekerja->nama }}</h1>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- 📋 Tugas Saya --}}
        <div class="mb-10">
            <h2 class="text-xl font-semibold mb-3">📋 Tugas Saya</h2>

            @forelse ($tugasSaya as $log)
                <div @class([
                    'bg-white rounded shadow p-4 mb-4 border-l-4',
                    'border-green-500' => $log->status === 'selesai',
                    'border-yellow-500' => $log->status === 'dalam_proses',
                    'border-gray-300' => !in_array($log->status, ['selesai', 'dalam_proses']),
                ])>
                    <p class="text-gray-800"><strong>Order #{{ $log->order_id }}</strong> | Tahapan:
                        {{ ucfirst($log->tahapan) }}</p>
                    <p class="text-sm text-gray-600">Status: <span class="font-semibold">{{ ucfirst($log->status) }}</span>
                    </p>

                    @if ($log->status === 'dalam_proses')
                        <form action="{{ route('pekerja_selesai', $log->id) }}" method="POST" enctype="multipart/form-data"
                            class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Catatan
                                    (Opsional)</label>
                                <textarea name="catatan" rows="2"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white resize-none"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">File Bukti
                                    (Opsional)</label>
                                <input type="file" name="file_bukti" accept="image/*,.pdf"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-green-500 dark:bg-gray-700 dark:text-white">
                                {{-- Tampilkan pesan error validasi di bawah input --}}
                                @error('file_bukti')
                                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 transform hover:scale-105">
                                ✓ Selesaikan Tugas
                            </button>
                        </form>
                    @elseif ($log->status === 'selesai')
                        <p class="text-green-600 mt-2 text-sm">✅ Tugas sudah selesai.</p>
                    @endif
                </div>
            @empty
                <p class="text-gray-600 italic">Belum ada tugas yang kamu ambil.</p>
            @endforelse
        </div>

        {{-- 📥 Tugas Tersedia --}}
        <div>
            <h2 class="text-xl font-semibold mb-3">📥 Tugas Tersedia</h2>

            @forelse ($tugasTersedia as $log)
                <div class="bg-gray-50 rounded border border-gray-200 p-4 mb-4">
                    <p class="text-gray-800"><strong>Order #{{ $log->order_id }}</strong> | Tahapan:
                        {{ ucfirst($log->tahapan) }}</p>
                    <p class="text-sm text-gray-600">Status: <span class="font-semibold">{{ ucfirst($log->status) }}</span>
                    </p>

                    <form action="{{ route('pekerja_terima') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="log_id" value="{{ $log->id }}">
                        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Ambil Tugas
                            Ini</button>
                    </form>
                </div>
            @empty
                <p class="text-gray-600 italic">Tidak ada tugas baru yang tersedia.</p>
            @endforelse
        </div>
    </div>
@endsection
