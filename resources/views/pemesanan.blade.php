<!-- resources/views/orders/create.blade.php -->
@extends('layouts.public')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">
  <h2 class="text-3xl font-bold mb-6 text-gray-800">Form Pemesanan Produk</h2>

  <form action="{{ route('order_store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf

    <!-- Informasi Customer -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nama Customer</label>
        <input type="text" name="nama_customer" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Nomor Telepon</label>
        <input type="text" name="nomor_telepon" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
      </div>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Alamat</label>
      <textarea name="alamat" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
    </div>

    <!-- Pilihan Produk & Kain -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Produk</label>
      <select name="produk_id" id="produkSelect">
        @foreach($produk as $item)
          <option value="{{ $item->id }}">{{ $item->nama_produk }}</option>
        @endforeach
      </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Jenis Kain</label>
        <select name="jenis_kain_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          <option value="">-- Pilih Jenis Kain --</option>
          @foreach($jenis_kain as $item)
            <option value="{{ $item->id }}">{{ $item->nama_kain }}</option>
          @endforeach
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Lengan</label>
        <select name="lengan_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          <option value="">-- Pilih Lengan --</option>
          @foreach($lengan as $item)
            <option value="{{ $item->id }}">{{ $item->tipe }}</option>
          @endforeach
        </select>
      </div>
    </div>

    <!-- Detail Ukuran & Harga -->
    <div>
      <label class="block text-sm font-medium text-gray-700 mb-1">Detail Pesanan</label>
      <div id="ukuran-wrapper" class="space-y-2">
        <!-- Baris detail ukuran akan disisipkan di sini oleh JS -->
      </div>
      <button type="button" onclick="addUkuran()" class="mt-2 px-3 py-1 bg-blue-600 text-white rounded shadow">+ Tambah Ukuran</button>
    </div>

    <!-- Jasa Tambahan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Jasa Tambahan</label>
        <select name="jasa_tambahan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
          <option value="tidak">Tidak Ada</option>
          <option value="sablon">Sablon</option>
          <option value="bordir">Bordir</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Fee Tambahan (Opsional)</label>
        <input type="number" name="fee_tambahan" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
      </div>
    </div>

    <!-- Desain -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Status Desain</label>
        <select name="desain_status" id="desain_status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" onchange="toggleDesain(this.value)">
          <option value="belum">Belum</option>
          <option value="sudah">Sudah</option>
        </select>
      </div>
      <div id="fileDesainWrapper" class="hidden">
        <label class="block text-sm font-medium text-gray-700">Upload Desain</label>
        <input type="file" name="desain_file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
      </div>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Catatan Desain</label>
      <textarea name="catatan_desain" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
    </div>

    <!-- DP & Pembayaran -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700">Nominal DP</label>
        <input type="number" name="dp_nominal" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Metode Pembayaran</label>
        <input type="text" name="metode_pembayaran" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
      </div>
    </div>
    <div>
      <label class="block text-sm font-medium text-gray-700">Upload Bukti Pembayaran</label>
      <input type="file" name="bukti_pembayaran" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
    </div>

    <!-- Submit -->
    <div class="text-right">
      <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md shadow">Kirim Pesanan</button>
    </div>
  </form>
</div>

<script>
  let index = 0;

  function addUkuran() {
    const wrapper = document.getElementById('ukuran-wrapper');
    const row = document.createElement('div');
    row.classList.add('grid', 'grid-cols-4', 'gap-2');
    row.innerHTML = `
      <input type="number" name="ukuran_detail[${index}][jumlah]" placeholder="Jumlah" required class="border px-2 py-1 rounded">
      <select name="ukuran_detail[${index}][ukuran_id]" required class="border px-2 py-1 rounded">
        <option value="">Ukuran</option>
        @foreach($ukuran as $item)
          <option value="{{ $item->id }}">{{ $item->nama_ukuran }}</option>
        @endforeach
      </select>
      <input type="number" name="ukuran_detail[${index}][harga]" placeholder="Harga" required class="border px-2 py-1 rounded">
      <select name="ukuran_detail[${index}][lengan_panjang]" required class="border px-2 py-1 rounded">
        <option value="0">Tidak</option>
        <option value="1">Lengan Panjang</option>
      </select>
    `;
    wrapper.appendChild(row);
    index++;
  }

  function toggleDesain(val) {
    const desain = document.getElementById('fileDesainWrapper');
    desain.classList.toggle('hidden', val !== 'sudah');
  }

  window.onload = () => addUkuran();
</script>
@endsection
