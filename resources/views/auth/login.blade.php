<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Logo / Judul --}}
    <div class="flex justify-center mb-8">
        <a href="/" class="font-bold text-4xl text-blue-600 tracking-tight hover:text-blue-700 transition duration-150">
            Absensi-Ku
        </a>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            {{-- WRAPPER RELATIVE: Pastikan wrapper ini menjadi acuan posisi --}}
            <div class="relative mt-1 w-full">
                {{-- INPUT: 
                     - pr-10: Memberi ruang di kanan agar teks tidak tertabrak icon 
                     - m-0: Menghapus margin bawaan jika ada, agar tinggi wrapper pas dengan input
                --}}
                <x-text-input id="password" class="block w-full pr-10 m-0"
                                type="password"
                                name="password"
                                required autocomplete="current-password" />

                {{-- TOMBOL ICON: 
                     - absolute: Mengambang di atas elemen lain
                     - right-3: Jarak dari sisi kanan
                     - top-1/2: Titik atas tombol ada di 50% tinggi container
                     - -translate-y-1/2: Geser tombol ke atas 50% dari ukurannya sendiri (centering presisi)
                     - z-10: Memastikan icon di atas input
                --}}
                <button type="button" onclick="togglePassword()"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 z-10 text-gray-400 hover:text-gray-600 focus:outline-none cursor-pointer">
                    <svg id="eye-icon" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <!-- Default: Eye Open -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-blue-600 hover:bg-blue-700 focus:ring-blue-500">
                {{ __('Masuk') }}
            </x-primary-button>
        </div>
    </form>

    {{-- Script Toggle Password --}}
    <script>
        function togglePassword() {
            var passwordField = document.getElementById("password");
            var eyeIcon = document.getElementById("eye-icon");

            if (passwordField.type === "password") {
                passwordField.type = "text";
                // Icon Mata Dicoret (Hidden)
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />';
            } else {
                passwordField.type = "password";
                // Icon Mata Biasa (Show)
                eyeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />';
            }
        }
    </script>
</x-guest-layout>