@extends('layouts.app')

@section('title', 'Daftar Distribusi Order')
@section('header', 'Daftar Distribusi Order')

@section('content')
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 pb-10">
        <div class="bg-gradient-to-r from-gray-800 to-gray-900 text-white dark:from-gray-700 dark:to-gray-800 shadow-lg">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <h1 class="text-2xl sm:text-3xl font-bold">Daftar Distribusi Order</h1>
                <p class="mt-2 text-gray-300">Pantau dan kelola semua proses pengiriman pesanan.</p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Semua Order Distribusi</h2>

                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300 rounded-tl-lg">Kode Order</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Customer</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Ongkir</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Metode</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Resi</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Status</th>
                                <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300 rounded-tr-lg">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($distribusiOrders as $item)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-4 py-4 text-gray-900 dark:text-white">{{ $item->order->kode_order ?? '-' }}</td>
                                    <td class="px-4 py-4 text-gray-700 dark:text-gray-300">{{ $item->order->customer->nama ?? '-' }}</td>
                                    <td class="px-4 py-4 text-gray-700 dark:text-gray-300">Rp {{ number_format($item->biaya_ongkir ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 text-gray-700 dark:text-gray-300 capitalize">{{ str_replace('_', ' ', $item->metode_pengiriman) ?? '-' }}</td>
                                    <td class="px-4 py-4 text-gray-700 dark:text-gray-300">{{ $item->nomor_resi ?? '-' }}</td>
                                    <td class="px-4 py-4">
                                        @php
                                            $status = $item->status ?? 'draft';
                                            $statusColor = match ($status) {
                                                'menunggu_pelunasan' => 'bg-yellow-100 text-yellow-800',
                                                'selesai' => 'bg-green-100 text-green-800',
                                                'dibatalkan' => 'bg-red-100 text-red-800',
                                                default => 'bg-gray-100 text-gray-800',
                                            };
                                        @endphp
                                        <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('order_show', $item->order->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">Detail</a>
                                            <a href="{{ route('orderdistribusi_edit', $item->id) }}" class="inline-block bg-indigo-500 text-white px-3 py-1 rounded-lg text-xs font-medium hover:bg-indigo-600 transition-colors">
                                                Lengkapi
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">Belum ada data distribusi.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-4">
                    @forelse($distribusiOrders as $item)
                        <div class="bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-bold text-lg text-gray-900 dark:text-white">{{ $item->order->kode_order ?? '-' }}</span>
                                @php
                                    $status = $item->status ?? 'draft';
                                    $statusColor = match ($status) {
                                        'menunggu_pelunasan' => 'bg-yellow-100 text-yellow-800',
                                        'selesai' => 'bg-green-100 text-green-800',
                                        'dibatalkan' => 'bg-red-100 text-red-800',
                                        default => 'bg-gray-100 text-gray-800',
                                    };
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                </span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-2 space-y-1 text-gray-700 dark:text-gray-300 text-sm">
                                <p><strong>Customer:</strong> {{ $item->order->customer->nama ?? '-' }}</p>
                                <p><strong>Ongkir:</strong> Rp {{ number_format($item->biaya_ongkir ?? 0, 0, ',', '.') }}</p>
                                <p><strong>Metode:</strong> <span class="capitalize">{{ str_replace('_', ' ', $item->metode_pengiriman) ?? '-' }}</span></p>
                                <p><strong>Resi:</strong> {{ $item->nomor_resi ?? '-' }}</p>
                            </div>
                            <div class="mt-4 flex justify-end space-x-2">
                                <a href="{{ route('order_show', $item->order->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline text-xs font-medium">Detail Order</a>
                                <a href="{{ route('orderdistribusi_edit', $item->id) }}" class="inline-block bg-indigo-500 text-white px-3 py-1 rounded-lg text-xs font-medium hover:bg-indigo-600 transition-colors">
                                    Lengkapi
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="bg-white dark:bg-gray-800 p-6 text-center text-gray-500 dark:text-gray-400 rounded-lg shadow">
                            Belum ada data distribusi.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection