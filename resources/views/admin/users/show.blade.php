<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pengguna') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">

                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                {{-- Menampilkan inisial nama --}}
                                <span class="text-2xl font-bold text-gray-600 dark:text-gray-300">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                                <p class="text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-4 md:mt-0">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600">
                                &larr; Kembali
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Edit Pengguna
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <div>
                            <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Informasi Pengguna</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">NIK</label>
                                    <p class="text-lg">{{ $user->nik }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">No. Registrasi</label>
                                    <p class="text-lg">{{ $user->no_regis }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Role</label>
                                    <p class="text-lg">{{ ucfirst($user->role) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Shift Default</label>
                                    <p>
                                        @if ($user->shift)
                                            <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full text-xs">
                                                {{ $user->shift->name }} ({{ \Carbon\Carbon::parse($user->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($user->shift->end_time)->format('H:i') }})
                                            </span>
                                        @else
                                            <span class="text-gray-500 dark:text-gray-400">Belum diatur</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="text-center md:text-left">
                            <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">QR Code Absensi (Dinamis)</h4>
                            
                            @if($user->qr_token)
                                <div class="flex flex-col items-center md:items-start">
                                    {{-- Container QR Code --}}
                                    <div class="p-4 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg bg-white inline-block">
                                        {{-- 
                                            PERUBAHAN UTAMA:
                                            Menggunakan getDynamicQrToken() agar QR berubah tiap 15 menit 
                                        --}}
                                        {!! QrCode::size(200)->generate($user->getDynamicQrToken()) !!}
                                    </div>
                                    
                                    <p class="mt-3 text-sm text-gray-500 dark:text-gray-400 max-w-xs text-center md:text-left">
                                        QR Code ini valid selama 15 menit.<br>
                                        <span class="text-red-500 text-xs font-semibold">*Jika scan gagal, silakan refresh halaman ini untuk memperbarui QR.</span>
                                    </p>

                                    {{-- Tombol Download --}}
                                    <a href="{{ route('admin.users.downloadQr', $user) }}" class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                        Download QR Terbaru
                                    </a>
                                </div>
                            @else
                                <div class="p-6 border border-gray-200 dark:border-gray-700 rounded-lg text-center text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700/50">
                                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                    <p>QR Code belum dibuat.</p>
                                    <p class="text-sm mt-1">Silakan edit pengguna untuk generate QR Code awal.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
