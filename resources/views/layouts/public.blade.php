<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') - Kaosta</title>

    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>
</head>
<body class="font-inter antialiased text-gray-800 bg-white">

    <!-- Navbar -->
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <div class="text-lg font-bold text-red-600">KAOSTA</div>
                <nav class="hidden md:flex space-x-6 font-medium text-sm">
                    <a href="#" class="hover:text-red-600 transition">Beranda</a>
                    <a href="#" class="hover:text-red-600 transition">Tentang Kami</a>
                    <a href="#" class="hover:text-red-600 transition">Layanan</a>
                    <a href="#" class="hover:text-red-600 transition">Produk</a>
                    <a href="#" class="hover:text-red-600 transition">Hubungi Kami</a>
                    <a href="#" class="hover:text-red-600 transition">Blog</a>
                    <a href="#" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">Pesan Sekarang</a>
                </nav>

                <!-- Mobile Menu Button -->
                <div class="md:hidden">
                    <button @click="open = !open" x-data="{ open: false }" class="text-gray-600 focus:outline-none">
                        <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-cloak>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>

                        <!-- Mobile Dropdown -->
                        <div x-show="open" x-cloak class="absolute top-16 right-4 bg-white rounded-lg shadow-md w-40 py-2 space-y-2 text-sm">
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Beranda</a>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Tentang Kami</a>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Layanan</a>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Produk</a>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Hubungi Kami</a>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Blog</a>
                            <a href="{{route('pemesanan')}}" class="block px-4 py-2 bg-red-600 text-white hover:bg-red-700 rounded">Pesan Sekarang</a>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Content -->
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 border-t text-sm text-gray-600 py-6">
        <div class="max-w-7xl mx-auto px-4 text-center">
            © {{ date('Y') }} KAOSTA. All rights reserved.
        </div>
    </footer>

</body>
</html>
