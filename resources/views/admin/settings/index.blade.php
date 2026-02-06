<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Sistem') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                    
                    {{-- Bagian 1: Jadwal Kerja --}}
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Jadwal Kerja Global
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Atur jam masuk yang akan menjadi batas waktu keterlambatan default (jika user tidak memiliki shift).
                        </p>
                        
                        <div class="mt-4 max-w-md">
                            <x-input-label for="work_start_time" value="Jam Masuk Kerja" />
                            <x-text-input id="work_start_time" class="block mt-1 w-full" type="time" name="work_start_time" :value="old('work_start_time', $workStartTime)" required />
                            <x-input-error :messages="$errors->get('work_start_time')" class="mt-2" />
                        </div>
                    </div>

                    {{-- Bagian 2: Pengaturan Lokasi (Geofencing) --}}
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Pembatasan Lokasi (Geofencing)
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Karyawan hanya bisa melakukan absensi jika berada dalam radius tertentu dari titik koordinat kantor.
                        </p>

                        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Input Latitude --}}
                            <div>
                                <x-input-label for="office_latitude" value="Latitude (Garis Lintang)" />
                                <x-text-input id="office_latitude" class="block mt-1 w-full" type="text" name="office_latitude" :value="old('office_latitude', $officeLatitude)" placeholder="Contoh: -6.200000" />
                                <p class="text-xs text-gray-400 mt-1">Dapatkan dari Google Maps (klik kanan pada lokasi kantor).</p>
                                <x-input-error :messages="$errors->get('office_latitude')" class="mt-2" />
                            </div>
                            
                            {{-- Input Longitude --}}
                            <div>
                                <x-input-label for="office_longitude" value="Longitude (Garis Bujur)" />
                                <x-text-input id="office_longitude" class="block mt-1 w-full" type="text" name="office_longitude" :value="old('office_longitude', $officeLongitude)" placeholder="Contoh: 106.816666" />
                                <x-input-error :messages="$errors->get('office_longitude')" class="mt-2" />
                            </div>
                            
                            {{-- Input Radius --}}
                            <div>
                                <x-input-label for="office_radius" value="Maksimal Jarak (Meter)" />
                                <x-text-input id="office_radius" class="block mt-1 w-full" type="number" name="office_radius" :value="old('office_radius', $officeRadius ?? 50)" min="10" />
                                <p class="text-xs text-gray-400 mt-1">Jarak maksimum karyawan dari titik pusat (Default: 50m).</p>
                                <x-input-error :messages="$errors->get('office_radius')" class="mt-2" />
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Simpan --}}
                    <div class="p-6 flex items-center justify-end gap-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
                        @if (session('success'))
                            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-green-600 font-medium">
                                {{ session('success') }}
                            </div>
                        @endif

                        <x-primary-button>
                            {{ __('Simpan Pengaturan') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
