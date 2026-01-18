<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- PERUBAHAN 1: Hapus 'overflow-hidden' agar sticky header berfungsi --}}
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg flex flex-col">

                {{-- PERUBAHAN 2: Tata ulang seluruh bagian header kartu agar lebih rapi --}}
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 space-y-4">
                    {{-- Baris Judul dan Tombol Aksi Utama --}}
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Riwayat Absensi Semua Pengguna
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Lihat atau filter catatan kehadiran yang terekam dalam sistem.
                            </p>
                        </div>
                        <div class="mt-4 sm:mt-0 flex-shrink-0 flex gap-2">
                            <a href="{{ route('admin.attendances.export', request()->query()) }}"
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Export ke Excel
                            </a>
                            <a href="{{ route('admin.attendances.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                + Tambah Manual
                            </a>
                        </div>
                    </div>

                    {{-- Baris Form Filter --}}
                    <form method="GET" action="{{ route('admin.attendances.index') }}" class="flex flex-col sm:flex-row gap-4 sm:items-end">
                        <div>
                            <x-input-label for="start_date" :value="__('Dari Tanggal')" />
                            <x-text-input id="start_date" class="block mt-1 w-full sm:w-auto" type="date" name="start_date" :value="request('start_date')" />
                        </div>
                        <div>
                            <x-input-label for="end_date" :value="__('Sampai Tanggal')" />
                            <x-text-input id="end_date" class="block mt-1 w-full sm:w-auto" type="date" name="end_date" :value="request('end_date')" />
                        </div>
                        <div class="flex gap-2">
                            <x-primary-button>
                                {{ __('Filter') }}
                            </x-primary-button>
                            <a href="{{ route('admin.attendances.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-800 dark:text-gray-100 uppercase tracking-widest">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- Container tabel tetap sama, akan berfungsi setelah 'overflow-hidden' dihapus dari parent --}}
                <div class="overflow-x-auto max-h-[60vh] overflow-y-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700 sticky top-0 z-10">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Pengguna</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jam Absen</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Keterangan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
    @forelse ($attendances as $item)
        {{-- PERUBAHAN UTAMA DI SINI --}}

        {{-- Jika data adalah ABSENSI BIASA --}}
        @if ($item->event_type === 'attendance')
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                @if($item->user)
                                    <span class="font-semibold text-gray-600">{{ strtoupper(substr($item->user->name, 0, 2)) }}</span>
                                @else
                                    <span class="font-semibold text-gray-600">?</span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->user->name ?? 'Pengguna Dihapus' }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->user->email ?? '-' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    {{ $item->event_date->format('d F Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono">
                    {{ $item->event_date->format('H:i:s') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    @if ($item->status === 'Tepat Waktu')
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Tepat Waktu</span>
                    @else
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Telat</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    {{ $item->notes ?? '-' }}
                </td>
            </tr>

        {{-- Jika data adalah IZIN/CUTI --}}
        @elseif ($item->event_type === 'leave')
            <tr class="bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                         <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                 @if($item->user)
                                    <span class="font-semibold text-gray-600">{{ strtoupper(substr($item->user->name, 0, 2)) }}</span>
                                @else
                                    <span class="font-semibold text-gray-600">?</span>
                                @endif
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $item->user->name ?? 'Pengguna Dihapus' }}</div>
                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->user->email ?? '-' }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    {{ $item->event_date->format('d F Y') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 font-mono" colspan="2">
                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                        {{ $item->type }}
                    </span>
                </td>
                <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 max-w-xs truncate" title="{{ $item->reason }}">
                    {{ $item->reason }}
                </td>
            </tr>
        @endif

    @empty
        <tr>
            <td colspan="5" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                Tidak ada data absensi atau izin untuk ditampilkan.
            </td>
        </tr>
    @endforelse
</tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
