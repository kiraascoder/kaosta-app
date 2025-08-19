@extends('layouts.public')

@section('title', 'Beranda')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">Selamat Datang di KAOSTA</h1>
    <p class="text-gray-600 mb-8">
        Kami menyediakan layanan konveksi berkualitas untuk kebutuhan Anda.
    </p>

    <a href="{{route('pemesanan')}}" class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition">Pesan Sekarang</a>
</div>
@endsection
