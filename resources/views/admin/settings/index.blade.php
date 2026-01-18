<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pengaturan Jadwal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Jadwal Kerja
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Atur jam masuk yang akan menjadi batas waktu keterlambatan.
                        </p>
                    </div>

                    <div class="p-6">
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="max-w-md">
                            <x-input-label for="work_start_time" value="Jam Masuk Kerja" />
                            <x-text-input id="work_start_time" class="block mt-1 w-full" type="time" name="work_start_time" :value="old('work_start_time', $workStartTime)" required />
                            <x-input-error :messages="$errors->get('work_start_time')" class="mt-2" />
                        </div>
                    </div>

                    <div class="p-6 flex items-center justify-end gap-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                        <x-primary-button>
                            Simpan Pengaturan
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
