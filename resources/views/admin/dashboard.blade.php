<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Kartu Statistik --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Total Pengguna --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pengguna</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalUsers }}</p>
                </div>
                {{-- Hadir Hari Ini --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Hadir Hari Ini</h3>
                    <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $attendedToday }}</p>
                </div>
                {{-- Telat Hari Ini --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Telat Hari Ini</h3>
                    <p class="mt-1 text-3xl font-semibold text-red-600 dark:text-red-400">{{ $lateToday }}</p>
                </div>
            </div>

            {{-- Aktivitas Absensi Terkini --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                        Aktivitas Absensi Terkini
                    </h3>
                </div>
                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($recentAttendances as $attendance)
                        <li class="p-4 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                                    <span class="font-semibold text-gray-600">{{ strtoupper(substr($attendance->user->name ?? '?', 0, 2)) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $attendance->user->name ?? 'Pengguna Dihapus' }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">melakukan absensi</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $attendance->check_in_time->diffForHumans() }}</p>
                                <p class="text-xs font-mono text-gray-400">{{ $attendance->check_in_time->format('H:i') }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="p-6 text-center text-gray-500 dark:text-gray-400">
                            Belum ada aktivitas absensi hari ini.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
</x-app-layout>
