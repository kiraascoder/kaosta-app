<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50 dark:bg-gray-900">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Kaosta Admin</title>

    {{-- ✅ Meta Tag CSRF dan PWA --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    {{-- ✅ Pemuatan CSS via Vite --}}
    @vite('resources/css/app.css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        [x-cloak] {
            display: none !important;
        }

        @keyframes pulse-slow {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(1.1);
            }
        }

        .animate-pulse-slow {
            animation: pulse-slow 3s infinite ease-in-out;
        }
    </style>
    @stack('styles')
</head>

<body class="h-full font-inter antialiased text-gray-900 dark:text-gray-100" x-data="{ sidebarOpen: false }">

    <div class="flex h-screen overflow-hidden">
        {{-- Konten sidebar, header, dan main di sini ... --}}
        <div x-show="sidebarOpen" @click="sidebarOpen = false"
            class="fixed inset-0 z-30 bg-black bg-opacity-60 md:hidden transition-opacity duration-300"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        </div>
        <div :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 w-72 transform bg-gray-900 dark:bg-gray-950 backdrop-blur-lg border-r border-gray-700/50 dark:border-gray-800 shadow-2xl transition-transform duration-300 md:static md:translate-x-0 md:flex md:flex-col overflow-y-auto custom-scrollbar">
            <div class="flex flex-col h-full">
                <div class="p-6 border-b border-gray-700/50 dark:border-gray-800">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <img src="{{ asset('images/LOGO-BARU-KAOSTA2.png') }}" alt="Kaosta Logo"
                                class="w-72 h-12 object-contain rounded-xl shadow-lg shadow-red-500/25 p-1 bg-gradient-to-br from-red-500 to-red-600">
                        </div>
                    </div>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <div class="space-y-1">
                        <a href="{{ route('dashboard') }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-300 hover:text-white hover:bg-red-500/20 dark:hover:bg-red-700/30 transition-all duration-200 hover:translate-x-1
                            {{ request()->routeIs('dashboard') ? 'text-white bg-gradient-to-r from-red-500 to-red-600 shadow-lg shadow-red-500/25 transform scale-[1.02]' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 5a2 2 0 012-2h4a2 2 0 012 2v4H8V5z" />
                            </svg>
                            Dashboard
                            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <div
                                    class="w-1.5 h-1.5 {{ request()->routeIs('dashboard') ? 'bg-white' : 'bg-red-400' }} rounded-full">
                                </div>
                            </div>
                        </a>
                        <a href="{{ route('customer_index') }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-300 hover:text-white hover:bg-red-500/20 dark:hover:bg-red-700/30 transition-all duration-200 hover:translate-x-1
                            {{ request()->routeIs('customer*') ? 'text-white bg-gradient-to-r from-red-500 to-red-600 shadow-lg shadow-red-500/25 transform scale-[1.02]' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Data Pelanggan
                            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <div
                                    class="w-1.5 h-1.5 {{ request()->routeIs('customer*') ? 'bg-white' : 'bg-red-400' }} rounded-full">
                                </div>
                            </div>
                        </a>
                        <div x-data="{ openOrder: {{ request()->routeIs('order*') ? 'true' : 'false' }} }">
                            <button @click="openOrder = !openOrder"
                                class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-300 hover:text-white hover:bg-red-500/20 dark:hover:bg-red-700/30 transition-all duration-200 hover:translate-x-1 focus:outline-none"
                                :class="openOrder ?
                                    'bg-gradient-to-r from-red-500 to-red-600 shadow-lg shadow-red-500/25 text-white scale-[1.02]' :
                                    ''">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Orderan
                                <svg class="ml-auto w-4 h-4 transform transition-transform duration-200"
                                    :class="openOrder ? 'rotate-90' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <div x-show="openOrder" x-collapse class="pl-10 mt-2 space-y-1">
                                <a href="{{ route('order_index') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-red-500/20 dark:hover:bg-red-700/30 hover:text-white transition duration-150
                                    {{ request()->routeIs('order_index') ? 'text-white font-semibold' : '' }}">Permintaan
                                    Produksi</a>
                                <a href="{{ route('orderdistribusi_index') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-red-500/20 dark:hover:bg-red-700/30 hover:text-white transition duration-150
                                    {{ request()->routeIs('orderdistribusi_index') ? 'text-white font-semibold' : '' }}">Permintaan
                                    Distribusi</a>
                            </div>
                        </div>
                        <div x-data="{ openProduk: {{ request()->routeIs('produk*') || request()->routeIs('jenis_kain*') || request()->routeIs('ukuran*') || request()->routeIs('harga*') ? 'true' : 'false' }} }">
                            <button @click="openProduk = !openProduk"
                                class="w-full flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-300 hover:text-white hover:bg-red-500/20 dark:hover:bg-red-700/30 transition-all duration-200 hover:translate-x-1 focus:outline-none
                                {{ request()->routeIs('produk*') || request()->routeIs('jenis_kain*') || request()->routeIs('ukuran*') || request()->routeIs('harga*') ? 'bg-gradient-to-r from-red-500 to-red-600 shadow-lg shadow-red-500/25 transform scale-[1.02]' : '' }}">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                                Produk
                                <svg class="ml-auto w-4 h-4 transform transition-transform duration-200"
                                    :class="openProduk ? 'rotate-90' : ''" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5l7 7-7 7" />
                                </svg>
                            </button>
                            <div x-show="openProduk" x-collapse class="pl-10 mt-2 space-y-1">
                                <a href="{{ route('produk_index') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-red-500/20 dark:hover:bg-red-700/30 hover:text-white transition duration-150
                                    {{ request()->routeIs('produk_index') ? 'text-white font-semibold' : '' }}">Produk</a>
                                <a href="{{ route('jenis_kain_index') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-red-500/20 dark:hover:bg-red-700/30 hover:text-white transition duration-150
                                    {{ request()->routeIs('jenis_kain_index') ? 'text-white font-semibold' : '' }}">Jenis
                                    Kain</a>
                                <a href="{{ route('ukuran_index') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-red-500/20 dark:hover:bg-red-700/30 hover:text-white transition duration-150
                                    {{ request()->routeIs('ukuran_index') ? 'text-white font-semibold' : '' }}">Ukuran</a>
                                <a href="{{ route('harga_index') }}"
                                    class="block px-3 py-2 text-sm text-gray-300 rounded-lg hover:bg-red-500/20 dark:hover:bg-red-700/30 hover:text-white transition duration-150
                                    {{ request()->routeIs('harga_index') ? 'text-white font-semibold' : '' }}">Harga</a>
                            </div>
                        </div>
                        <a href="{{ route('pekerja_index') }}"
                            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl text-gray-300 hover:text-white hover:bg-red-500/20 dark:hover:bg-red-700/30 transition-all duration-200 hover:translate-x-1
                            {{ request()->routeIs('pekerja*') ? 'text-white bg-gradient-to-r from-red-500 to-red-600 shadow-lg shadow-red-500/25 transform scale-[1.02]' : '' }}">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                            </svg>
                            Pekerja
                            <div class="ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                <div
                                    class="w-1.5 h-1.5 {{ request()->routeIs('pekerja*') ? 'bg-white' : 'bg-red-400' }} rounded-full">
                                </div>
                            </div>
                        </a>
                    </div>
                </nav>
                <div class="p-4 border-t border-gray-700/50 dark:border-gray-800 space-y-3">
                    <div
                        class="flex items-center p-3 rounded-xl bg-gray-800 dark:bg-gray-800/80 border border-gray-600/50 dark:border-gray-700">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                            <p class="text-xs text-gray-400">Online</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium rounded-xl text-gray-300 hover:text-white hover:bg-red-500/20 dark:hover:bg-red-700/30 transition-all duration-200 group border border-gray-600/50 dark:border-gray-700 hover:border-red-500/50 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50">
                            <svg class="w-5 h-5 mr-3 group-hover:text-red-400 flex-shrink-0" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="flex-1 flex flex-col">
            <header
                class="bg-white/95 dark:bg-gray-900/90 backdrop-blur-lg border-b border-gray-200/50 dark:border-gray-800 shadow-sm px-6 py-4 flex items-center justify-between">
                <div>
                    <button @click="sidebarOpen = true"
                        class="md:hidden text-gray-600 dark:text-gray-300 hover:text-red-500 dark:hover:text-red-400 focus:outline-none focus:text-red-500 dark:focus:text-red-400 p-2 rounded-md transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h1
                        class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-900 bg-clip-text text-transparent dark:from-white dark:to-gray-200 inline-block align-middle">
                        @yield('header', 'Dashboard')
                    </h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button
                        class="relative p-2 text-gray-500 dark:text-gray-400 hover:text-red-500 dark:hover:text-red-400 focus:outline-none focus:text-red-500 dark:focus:text-red-400 transition-colors duration-200 hover:bg-red-50 dark:hover:bg-gray-700 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-5 5v-5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 19h8" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 13h5l-5-5v5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13h8" />
                        </svg>
                        <span
                            class="absolute top-1 right-1 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-900"></span>
                    </button>
                    <div x-data="{ profileOpen: false }" class="relative">
                        <button @click="profileOpen = !profileOpen"
                            class="flex items-center space-x-3 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50 rounded-full p-0.5 -m-0.5 transition-all duration-150">
                            <div
                                class="w-9 h-9 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg cursor-pointer flex-shrink-0">
                                <span
                                    class="text-base font-semibold text-white">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                            </div>
                            <span
                                class="hidden lg:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name ?? 'Admin' }}</span>
                            <svg class="hidden lg:block w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="profileOpen" @click.away="profileOpen = false" x-cloak
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border border-gray-200 dark:border-gray-700">
                            <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">Profil</a>
                            <a href="#"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">Pengaturan</a>
                            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors duration-150">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>
            @if ($errors->any())
                <div class="mx-6 mt-4 p-4 bg-red-100 dark:bg-red-900/20 border border-red-300 dark:border-red-700 text-red-800 dark:text-red-300 rounded-lg shadow-sm animate-fade-in"
                    x-init="setTimeout(() => $el.classList.remove('opacity-0'), 100)" x-cloak>
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <main
                class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50/80 to-white dark:from-gray-900/80 dark:to-gray-800/80">
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    {{-- ✅ KOREKSI: Semua skrip dipindahkan ke akhir body untuk performa dan urutan yang benar --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.plugin(window.AlpineCollapse);
        });
    </script>

    {{-- ✅ KOREKSI: Hanya panggil 'app.js' yang sudah mengimpor yang lain --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ✅ Registrasi Service Worker di akhir body --}}
    <script defer>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then(reg => console.log('Service Worker registered', reg))
                .catch(err => console.error('Service Worker error', err));
        }
    </script>
    @stack('scripts')
</body>

</html>
