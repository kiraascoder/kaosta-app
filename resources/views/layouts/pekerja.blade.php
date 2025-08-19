<!DOCTYPE html>
<html lang="en" class="h-full bg-gradient-to-br from-gray-50 via-red-50 to-gray-100">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Kaosta</title>

    {{-- ✅ Meta Tag CSRF dan Pemuatan CSS via Vite --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite('resources/css/app.css')

    <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="h-full font-inter antialiased" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen">

        <div class="fixed inset-0 z-40 flex md:hidden" x-show="sidebarOpen" x-transition>
            <div
                class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-900/95 backdrop-blur-xl border-r border-gray-700/50 shadow-xl">
                <div class="absolute top-0 right-0 -mr-12 pt-2">
                    <button @click="sidebarOpen = false"
                        class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none text-white hover:bg-red-500/30">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="flex flex-col h-full">
                    <div class="p-6 border-b border-gray-700/50">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                            <div>
                                <h1 class="text-xl font-bold text-white">KAOSTA</h1>
                                <p class="text-xs text-gray-400 font-medium">Pekerja</p>
                            </div>
                        </div>
                    </div>
                    <nav class="flex-1 px-4 py-6 space-y-2">
                        <a href="{{ route('dashboard_pekerja') }}"
                            class="flex items-center px-4 py-3 rounded-lg text-white hover:bg-red-600 transition-colors group">
                            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('pekerja_keterangan') }}"
                            class="flex items-center px-4 py-3 rounded-lg text-white hover:bg-red-600 transition-colors group">
                            <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white" fill="none"
                                stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Keterangan Tugas
                        </a>
                    </nav>
                    <div class="p-4 border-t border-gray-700/50 space-y-3">
                        <div
                            class="flex items-center p-3 rounded-xl bg-gradient-to-r from-gray-800 to-gray-700 border border-gray-600/50">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <p class="text-sm font-semibold text-white">{{ Auth::user()->name ?? 'Pekerja' }}</p>
                                <p class="text-xs text-gray-400">{{ Auth::user()->role ?? 'Online' }}</p>
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-center px-4 py-3 text-sm font-medium rounded-xl text-gray-300 hover:text-white hover:bg-red-500/20 transition-all border border-gray-600/50 hover:border-red-500/50 group">
                                <svg class="w-5 h-5 mr-3 group-hover:text-red-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="flex-shrink-0 w-full bg-black bg-opacity-50" @click="sidebarOpen = false"></div>
        </div>

        <div class="hidden md:flex md:w-72 md:flex-col">
            <div class="flex flex-col h-full bg-gray-900/95 backdrop-blur-xl border-r border-gray-700/50 shadow-2xl">
                <div class="p-6 border-b border-gray-700/50">
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/25">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">KAOSTA</h1>
                            <p class="text-xs text-gray-400 font-medium">Pekerja Dashboard</p>
                        </div>
                    </div>
                </div>
                <nav class="flex-1 px-4 py-6 space-y-2">
                    <a href="{{ route('dashboard_pekerja') }}"
                        class="flex items-center px-4 py-3 rounded-lg text-white hover:bg-red-600 transition-colors group {{ request()->routeIs('dashboard_pekerja') ? 'bg-red-600' : '' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white {{ request()->routeIs('dashboard_pekerja') ? 'text-white' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 5a2 2 0 012-2h2a2 2 0 012 2v2H8V5z"></path>
                        </svg>
                        Dashboard
                    </a>
                    <a href="{{ route('pekerja_keterangan') }}"
                        class="flex items-center px-4 py-3 rounded-lg text-white hover:bg-red-600 transition-colors group {{ request()->routeIs('pekerja_keterangan') ? 'bg-red-600' : '' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-400 group-hover:text-white {{ request()->routeIs('pekerja_keterangan') ? 'text-white' : '' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Keterangan Tugas
                    </a>
                </nav>
                <div class="p-4 border-t border-gray-700/50 space-y-4">
                    <div
                        class="flex items-center p-3 rounded-xl bg-gradient-to-r from-gray-800/80 to-gray-700/80 border border-gray-600/50 backdrop-blur-sm">
                        <div
                            class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-lg flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-white">{{ Auth::user()->name ?? 'Pekerja' }}</p>
                            <p class="text-xs text-gray-400">{{ Auth::user()->role ?? 'Staff' }}</p>
                        </div>
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-3 text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-red-500/20 to-red-600/20 hover:from-red-500 hover:to-red-600 transition-all duration-200 border border-red-500/30 hover:border-red-500 shadow-lg hover:shadow-red-500/25 group transform hover:scale-105">
                            <svg class="w-5 h-5 mr-3 text-red-400 group-hover:text-white transition-colors"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span class="text-red-400 group-hover:text-white transition-colors">Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="flex-1 flex flex-col">
            <header class="bg-white/95 backdrop-blur-xl border-b border-gray-200/50 shadow-sm">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button @click="sidebarOpen = !sidebarOpen"
                                class="md:hidden p-2 text-gray-600 hover:text-red-500 focus:outline-none">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div>
                                <h1
                                    class="text-2xl font-bold bg-gradient-to-r from-gray-800 to-gray-900 bg-clip-text text-transparent">
                                    @yield('header', 'Dashboard')</h1>
                                <p class="text-sm text-gray-600 mt-1">Kelola tugas produksi Anda dengan efisien</p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button
                                class="relative p-2 text-gray-500 hover:text-red-500 focus:outline-none transition-colors duration-200 hover:bg-red-50 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 17h5l-5 5v-5zM4 19h8M15 13h5l-5-5v5zM4 13h8" />
                                </svg>
                                <span
                                    class="absolute top-0 right-0 block h-2.5 w-2.5 rounded-full bg-red-500 ring-2 ring-white"></span>
                            </button>
                            <div class="relative" x-data="{ dropdownOpen: false }">
                                <button @click="dropdownOpen = !dropdownOpen"
                                    class="w-8 h-8 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <span
                                        class="text-sm font-semibold text-white">{{ substr(Auth::user()->name ?? 'A', 0, 1) }}</span>
                                </button>
                                <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                    <div class="px-4 py-2 border-b border-gray-200">
                                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                                        <p class="text-xs text-gray-500">{{ Auth::user()->role }}</p>
                                    </div>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                            class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto bg-gradient-to-br from-gray-50/80 to-white">
                <div class="p-6">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @vite(['resources/js/app.js', 'resources/js/fcm.js', 'resources/js/bootstrap.js'])

    {{-- ✅ KOREKSI: Skrip di akhir body --}}
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
