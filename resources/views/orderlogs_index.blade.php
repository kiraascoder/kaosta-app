@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto py-6">
    <h2 class="text-xl font-semibold mb-4">Input Log Produksi - Order #{{ $order->kode_order }}</h2>

    <form action="{{ route('produksi_log_store', $order->id) }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block font-medium">Tahapan</label>
            <select name="tahapan" class="w-full border rounded p-2">
                <option value="desain">Desain</option>
                <option value="gunting">Gunting</option>
                <option value="jahit">Jahit</option>
                <option value="bordir">Bordir</option>
                <option value="sablon">Sablon</option>
                <option value="qc">QC</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Pekerja</label>
            <select name="pekerja_id" class="w-full border rounded p-2">
                @foreach ($pekerjaList as $pekerja)
                    <option value="{{ $pekerja->id }}">{{ $pekerja->nama }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Status</label>
            <select name="status" class="w-full border rounded p-2">
                <option value="proses">Proses</option>
                <option value="selesai">Selesai</option>
                <option value="revisi">Revisi</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Catatan</label>
            <textarea name="catatan" class="w-full border rounded p-2"></textarea>
        </div>

        <div class="mb-4">
            <label class="block font-medium">Upload Bukti File (opsional)</label>
            <input type="file" name="bukti_file" class="w-full">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
                <label class="block font-medium">Waktu Mulai</label>
                <input type="datetime-local" name="waktu_mulai" class="w-full border rounded p-2">
            </div>
            <div>
                <label class="block font-medium">Waktu Selesai</label>
                <input type="datetime-local" name="waktu_selesai" class="w-full border rounded p-2">
            </div>
        </div>

        <div class="text-right">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Simpan Log</button>
        </div>
    </form>
</div>
@endsection
