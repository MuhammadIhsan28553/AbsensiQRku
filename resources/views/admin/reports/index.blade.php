<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Laporan Rekapitulasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                Rekapitulasi Kehadiran Bulanan
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                Pilih bulan untuk melihat ringkasan kehadiran semua pengguna.
                            </p>
                        </div>
                        {{-- TOMBOL BARU --}}
                        <a href="{{ route('admin.reports.export', ['month' => $selectedMonth]) }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Export Excel
                        </a>
                    </div>

                    <form method="GET" action="{{ route('admin.reports.index') }}" class="mt-4 flex items-end gap-4">
                        <div>
                            <x-input-label for="month" value="Pilih Bulan & Tahun" />
                            <x-text-input id="month" class="block mt-1" type="month" name="month" :value="$selectedMonth" />
                        </div>
                        <x-primary-button>
                            Tampilkan
                        </x-primary-button>
                    </form>
                </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pengguna</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Hadir</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Telat</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Sakit</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Cuti</th>
                                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Alpa</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($reportData as $data)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $data['name'] }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">{{ $data['present'] }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-yellow-600 font-semibold">{{ $data['late'] }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-blue-600">{{ $data['sick'] }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-indigo-600">{{ $data['leave'] }}</td>
                                    <td class="px-6 py-4 text-center text-sm text-red-600 font-bold">{{ $data['absent'] }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada data untuk ditampilkan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
