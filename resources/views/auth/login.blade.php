<x-guest-layout>
    <!-- Container utama -->
    <div class="min-h-screen flex items-center justify-center bg-red-900 p-4">
        <!-- Card -->
        <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-gray-200">
            
            <!-- Header dengan logo KAOSTA -->
            <div class="bg-red-700 flex justify-center py-6 relative">
                <img src="{{ asset('images/LOGO-BARU-KAOSTA2.png') }}" alt="KAOSTA Logo" class="h-16">
            </div>

            <!-- Konten form -->
            <div class="p-8 bg-white">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                    <p class="text-gray-600 text-sm">Masuk ke akun kamu</p>
                </div>

                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div class="space-y-2">
                        <x-input-label for="email" :value="__('Email Address')" class="text-gray-800 font-semibold text-sm" />
                        <x-text-input id="email"
                            class="block w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-red-500 focus:ring-red-200 transition-all bg-gray-50 focus:bg-white text-gray-900 placeholder-gray-500"
                            type="email" name="email" :value="old('email')" required autofocus placeholder="Masukkan Email mu" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- Password -->
                    <div class="space-y-2">
                        <x-input-label for="password" :value="__('Password')" class="text-gray-800 font-semibold text-sm" />
                        <x-text-input id="password"
                            class="block w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-red-500 focus:ring-red-200 transition-all bg-gray-50 focus:bg-white text-gray-900 placeholder-gray-500"
                            type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-600" />
                    </div>

                    <!-- Remember Me & Lupa Password -->
                    <div class="flex items-center justify-between text-sm">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer">
                            <input id="remember_me" type="checkbox"
                                class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500"
                                name="remember">
                            <span class="ml-2 text-gray-700">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-red-600 hover:text-red-800 font-medium hover:underline"
                                href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Tombol Login -->
                    <div class="pt-2">
                        <x-primary-button
                            class="w-full justify-center bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl py-3 transition duration-200">
                            {{ __('Sign In') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-guest-layout>
