<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Pantau aktivitas absensi hari ini, {{ date('d F Y') }}
                </p>
            </div>
            
            {{-- Tombol Aksi Cepat (Opsional) --}}
            <button class="hidden md:flex items-center gap-2 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-lg shadow-sky-500/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Export Data
            </button>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            {{-- Bagian 1: Kartu Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                {{-- Card: Total Pengguna --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-start z-10 relative">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Karyawan</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $totalUsers }}</h3>
                        </div>
                        <div class="p-3 bg-blue-50 dark:bg-blue-900/30 rounded-xl text-blue-600 dark:text-blue-400 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-blue-50 dark:bg-blue-900/20 rounded-full opacity-50 blur-xl"></div>
                </div>

                {{-- Card: Hadir Hari Ini --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-start z-10 relative">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Hadir Hari Ini</p>
                            <h3 class="text-3xl font-bold text-gray-800 dark:text-white">{{ $attendedToday }}</h3>
                        </div>
                        <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl text-emerald-600 dark:text-emerald-400 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-emerald-50 dark:bg-emerald-900/20 rounded-full opacity-50 blur-xl"></div>
                </div>

                {{-- Card: Telat --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="flex justify-between items-start z-10 relative">
                        <div>
                            <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Terlambat</p>
                            <h3 class="text-3xl font-bold text-rose-600 dark:text-rose-400">{{ $lateToday }}</h3>
                        </div>
                        <div class="p-3 bg-rose-50 dark:bg-rose-900/30 rounded-xl text-rose-600 dark:text-rose-400 group-hover:scale-110 transition-transform">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-24 h-24 bg-rose-50 dark:bg-rose-900/20 rounded-full opacity-50 blur-xl"></div>
                </div>
            </div>

            {{-- Bagian 2: Aktivitas Terkini --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100 flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-sky-500 rounded-full"></span>
                        Aktivitas Absensi Live
                    </h3>
                    <a href="#" class="text-sm text-sky-600 hover:text-sky-700 font-medium">Lihat Semua &rarr;</a>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($recentAttendances as $attendance)
                        <div class="p-5 flex items-center justify-between hover:bg-sky-50/30 dark:hover:bg-gray-700/30 transition-colors group">
                            <div class="flex items-center gap-4">
                                {{-- Avatar Initials dengan Warna Random (Logic CSS sederhana) --}}
                                <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-sky-100 to-blue-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center shadow-inner">
                                    <span class="font-bold text-sky-700 dark:text-sky-200 text-lg">
                                        {{ strtoupper(substr($attendance->user->name ?? 'X', 0, 2)) }}
                                    </span>
                                </div>
                                
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100 group-hover:text-sky-600 transition-colors">
                                        {{ $attendance->user->name ?? 'Pengguna Dihapus' }}
                                    </h4>
                                    <div class="flex items-center gap-2 mt-0.5">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Masuk
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ $attendance->check_in_time->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right">
                                <div class="font-mono text-lg font-bold text-gray-700 dark:text-gray-200">
                                    {{ $attendance->check_in_time->format('H:i') }}
                                </div>
                                <div class="text-xs text-gray-400 dark:text-gray-500">WIB</div>
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center flex flex-col items-center justify-center">
                            <div class="bg-gray-100 dark:bg-gray-700 rounded-full p-4 mb-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Belum ada data absensi hari ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
