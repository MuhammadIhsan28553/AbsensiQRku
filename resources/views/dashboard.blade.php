<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Karyawan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- 1. Hero Section: Selamat Datang (Dengan Gradient) --}}
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 shadow-lg shadow-sky-500/20">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white/10 blur-3xl"></div>
                <div class="absolute bottom-0 left-0 -ml-16 -mb-16 w-48 h-48 rounded-full bg-white/10 blur-3xl"></div>
                
                <div class="relative p-8 flex flex-col md:flex-row items-center justify-between gap-6">
                    <div class="text-white space-y-2">
                        <h3 class="text-3xl font-bold">
                            {{ __("Halo, " . Auth::user()->name . "!") }} 👋
                        </h3>
                        <p class="text-blue-100 text-lg max-w-xl">
                            Selamat beraktivitas! Jangan lupa untuk melakukan absensi masuk dan pulang tepat waktu ya.
                        </p>
                    </div>
                    {{-- Tombol Scan Cepat --}}
                    <a href="{{ route('scan') }}" class="group flex items-center gap-3 bg-white text-blue-600 px-6 py-3 rounded-xl font-bold shadow-lg hover:bg-blue-50 transition-all transform hover:scale-105 active:scale-95">
                        <div class="bg-blue-100 p-1.5 rounded-lg group-hover:bg-blue-200 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 6h6v6H6V6zm12 0h-6v6h6V6zm-6 12H6v-6h6v6z" />
                            </svg>
                        </div>
                        Scan QR Code
                    </a>
                </div>
            </div>

            {{-- Layout Grid Utama --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- Kolom Kiri (Statistik & Tabel) --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- 2. Kartu Statistik --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        {{-- Total Kehadiran --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-blue-50 dark:bg-blue-900/20 text-blue-600 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Absen</p>
                                    <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $totalAttendance }}</h4>
                                </div>
                            </div>
                        </div>

                        {{-- Bulan Ini --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-emerald-50 dark:bg-emerald-900/20 text-emerald-600 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bulan Ini</p>
                                    <h4 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ $attendanceThisMonth }}</h4>
                                </div>
                            </div>
                        </div>

                        {{-- Terlambat --}}
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-4">
                                <div class="p-3 bg-rose-50 dark:bg-rose-900/20 text-rose-600 rounded-xl">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Terlambat</p>
                                    <h4 class="text-2xl font-bold text-rose-600 dark:text-rose-400">{{ $totalLates }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Tabel Riwayat Absensi --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <span class="w-1.5 h-6 bg-sky-500 rounded-full"></span>
                                Riwayat Absensi Terkini
                            </h3>
                            <a href="#" class="text-sm text-sky-600 hover:text-sky-700 font-medium">Lihat Semua &rarr;</a>
                        </div>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Jam Masuk</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status Kehadiran</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                    @forelse ($paginatedAttendances as $attendance)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $attendance->check_in_time->isoFormat('dddd, D MMMM Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 font-mono">
                                                {{ $attendance->check_in_time->format('H:i') }} WIB
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if ($attendance->status === 'Tepat Waktu' || $attendance->status === 'Hadir')
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-emerald-100 text-emerald-800 border border-emerald-200">
                                                        ✅ Tepat Waktu
                                                    </span>
                                                @else
                                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-rose-100 text-rose-800 border border-rose-200">
                                                        ⏰ Terlambat
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-12 text-center text-gray-400 dark:text-gray-500">
                                                <div class="flex flex-col items-center justify-center">
                                                    <svg class="h-10 w-10 mb-2 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                                    </svg>
                                                    <p>Belum ada data absensi.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                         <div class="p-4 border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                            {{ $paginatedAttendances->links() }}
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan (QR Code) --}}
                <div class="lg:col-span-1 space-y-8">
                    
                    {{-- 4. Kartu QR Code --}}
                    @if($qrToken)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden relative group">
                            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-sky-400 to-blue-600"></div>
                            
                            <div class="p-6 text-center">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-2">ID Card Digital</h3>
                                <p class="text-sm text-gray-500 mb-6">Tunjukkan QR ini kepada petugas jika scanner error.</p>
                                
                                {{-- Container QR Code dengan background putih agar terbaca di dark mode --}}
                                <div class="bg-white p-4 rounded-xl border-2 border-dashed border-gray-300 inline-block shadow-inner">
                                    {!! QrCode::size(180)->generate($qrToken) !!}
                                </div>

                                <div class="mt-6 flex justify-center gap-2">
                                    <button onclick="window.print()" class="text-sm text-gray-500 hover:text-sky-600 flex items-center gap-1 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                        </svg>
                                        Cetak Kartu
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Informasi Jam Kerja (Opsional) --}}
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl shadow-lg text-white p-6 relative overflow-hidden">
                        <div class="relative z-10">
                            <h4 class="font-semibold text-lg mb-4 flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Jam Operasional
                            </h4>
                            <div class="space-y-3 text-sm text-gray-300">
                                <div class="flex justify-between border-b border-gray-700 pb-2">
                                    <span>Jam Masuk</span>
                                    <span class="font-mono text-white">08:00 WIB</span>
                                </div>
                                <div class="flex justify-between border-b border-gray-700 pb-2">
                                    <span>Batas Terlambat</span>
                                    <span class="font-mono text-white">08:15 WIB</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Jam Pulang</span>
                                    <span class="font-mono text-white">17:00 WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
