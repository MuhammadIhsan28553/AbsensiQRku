<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Tambah Pengguna Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Form dibungkus dalam satu kartu utama --}}
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">

                    {{-- Header Kartu --}}
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Formulir Pengguna Baru
                        </h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Isi detail di bawah ini untuk membuat akun baru.
                        </p>
                    </div>

                    {{-- Konten Form dengan Layout Grid Dua Kolom --}}
                    <div class="p-6">
                        @if ($errors->any())
                            <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-md">
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Kolom Kiri: Informasi Pribadi & Identitas --}}
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="name" value="Nama Lengkap" />
                                    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                                </div>
                                <div>
                                    <x-input-label for="email" value="Alamat Email" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                                </div>
                                <div>
                                    <x-input-label for="nik" value="NIK" />
                                    <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik')" required />
                                </div>
                                <div>
                                    <x-input-label for="no_regis" value="No. Registrasi" />
                                    <x-text-input id="no_regis" class="block mt-1 w-full" type="text" name="no_regis" :value="old('no_regis')" required />
                                </div>
                            </div>

                            {{-- Kolom Kanan: Keamanan Akun --}}
                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="password" value="Password" />
                                    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
                                </div>
                                <div>
                                    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
                                    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Kartu: Tombol Aksi --}}
                    <div class="p-6 flex items-center justify-end gap-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900">Batal</a>
                        <x-primary-button>
                            Simpan Pengguna
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
