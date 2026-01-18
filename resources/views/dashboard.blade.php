<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Pesan Selamat Datang --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-xl font-semibold">
                        {{ __("Selamat Datang, " . Auth::user()->name . "!") }}
                    </h3>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">
                        Ini adalah pusat kendali Anda untuk semua aktivitas absensi.
                    </p>
                </div>
            </div>

            {{-- Layout Grid Utama --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Kolom Kiri (Konten Utama) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Kartu Statistik --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Kehadiran</h4>
                            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $totalAttendance }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Hadir Bulan Ini</h4>
                            <p class="mt-1 text-3xl font-semibold text-gray-900 dark:text-gray-100">{{ $attendanceThisMonth }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm">
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Terlambat</h4>
                            <p class="mt-1 text-3xl font-semibold text-yellow-600 dark:text-yellow-400">{{ $totalLates }}</p>
                        </div>
                    </div>

                    {{-- Kartu Riwayat Absensi Terkini --}}
                    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Riwayat Absensi Terkini</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Jam Absen</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($paginatedAttendances as $attendance)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $attendance->check_in_time->format('d F Y') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $attendance->check_in_time->format('H:i:s') }}</td>
                                            <td class="px-6 py-4 text-sm">
                                                @if ($attendance->status === 'Tepat Waktu')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tepat Waktu</span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Telat</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-8 text-center text-gray-500">Belum ada riwayat absensi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                         <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                            {{ $paginatedAttendances->links() }}
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan (Aksi & QR Code Pribadi) --}}
                <div class="lg:col-span-1 space-y-6">
                    {{-- Kartu Aksi Utama --}}
                    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm text-center">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Siap untuk Absen?</h3>
                        <a href="{{ route('scan') }}" class="w-full inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition">
                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                            Scan QR Code Absen
                        </a>
                    </div>

                    {{-- Kartu QR Code Saya --}}
                    @if($qrToken)
                        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-sm text-center">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">QR Code Saya</h3>
                            <div class="flex justify-center p-2 bg-white rounded-md">
                                {!! QrCode::size(200)->generate($qrToken) !!}
                            </div>
                            <p class="mt-4 text-xs text-gray-500">Gunakan kode ini jika diperlukan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
