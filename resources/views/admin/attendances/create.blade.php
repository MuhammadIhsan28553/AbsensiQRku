<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Absensi Manual
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.attendances.store') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">

                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Formulir Koreksi Absensi
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Buat catatan absensi baru untuk pengguna.
                        </p>
                    </div>

                    <div class="p-6">
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                                {{ session('error') }}
                            </div>
                        @endif
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-md">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="user_id" value="Pilih Pengguna" />
                                    <select name="user_id" id="user_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                        <option value="">-- Pilih Pengguna --</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <x-input-label for="attendance_date" value="Tanggal Absen" />
                                    <x-text-input id="attendance_date" class="block mt-1 w-full" type="date" name="attendance_date" :value="old('attendance_date', now()->format('Y-m-d'))" required />
                                </div>
                                <div>
                                    <x-input-label for="attendance_time" value="Jam Absen" />
                                    <x-text-input id="attendance_time" class="block mt-1 w-full" type="time" name="attendance_time" :value="old('attendance_time', now()->format('H:i'))" required />
                                </div>
                            </div>
                            <div>
                                <x-input-label for="notes" value="Keterangan / Alasan" />
                                <textarea id="notes" name="notes" rows="8" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>{{ old('notes') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="p-6 flex items-center justify-end gap-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.attendances.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">Batal</a>
                        <x-primary-button>
                            Simpan Catatan
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
