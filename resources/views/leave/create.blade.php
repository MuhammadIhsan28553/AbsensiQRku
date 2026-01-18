{{-- file: resources/views/leave/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Buat Pengajuan Izin / Cuti
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('leave.store') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Formulir Pengajuan</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Isi detail di bawah ini untuk mengajukan izin.</p>
                    </div>

                    <div class="p-6 space-y-6">
                        {{-- (Tampilkan error jika ada) --}}

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="start_date" value="Tanggal Mulai" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" required />
                            </div>
                            <div>
                                <x-input-label for="end_date" value="Tanggal Selesai" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" required />
                            </div>
                        </div>

                        <div>
                            <x-input-label for="type" value="Tipe Pengajuan" />
                            <select name="type" id="type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>
                                <option value="sakit" {{ old('type') == 'sakit' ? 'selected' : '' }}>Izin Sakit</option>
                                <option value="cuti" {{ old('type') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            </select>
                        </div>

                        <div>
                            <x-input-label for="reason" value="Alasan" />
                            <textarea id="reason" name="reason" rows="4" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 rounded-md shadow-sm" required>{{ old('reason') }}</textarea>
                        </div>
                    </div>

                    <div class="p-6 flex items-center justify-end gap-4 bg-gray-50 dark:bg-gray-800/50 border-t">
                        <a href="{{ route('leave.index') }}" class="text-sm text-gray-600">Batal</a>
                        <x-primary-button>Kirim Pengajuan</x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
