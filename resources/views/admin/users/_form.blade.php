{{-- Menampilkan error validasi jika ada --}}
@if ($errors->any())
    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Kolom-kolom Form --}}

<!-- Name -->
<div class="mb-4">
    <x-input-label for="name" value="Nama" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Email -->
<div class="mb-4">
    <x-input-label for="email" value="Email" />
    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email ?? '')" required />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<!-- NIK -->
<div class="mb-4">
    <x-input-label for="nik" value="NIK" />
    <x-text-input id="nik" class="block mt-1 w-full" type="text" name="nik" :value="old('nik', $user->nik ?? '')" required />
    <x-input-error :messages="$errors->get('nik')" class="mt-2" />
</div>

<!-- No. Regis -->
<div class="mb-4">
    <x-input-label for="no_regis" value="No. Registrasi" />
    <x-text-input id="no_regis" class="block mt-1 w-full" type="text" name="no_regis" :value="old('no_regis', $user->no_regis ?? '')" required />
    <x-input-error :messages="$errors->get('no_regis')" class="mt-2" />
</div>

<!-- Default Shift -->
<div class="mb-4">
    <x-input-label for="shift_id" value="Shift Default" />
    {{-- Pastikan variabel $shifts dikirim dari controller --}}
    @isset($shifts)
        <select name="shift_id" id="shift_id" class="block mt-1 w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 dark:focus:border-indigo-600 dark:focus:ring-indigo-600">
            <option value="">-- Tidak Ada / Hari Libur --</option>
            @foreach($shifts as $shift)
                <option value="{{ $shift->id }}"
                    {{-- Cek apakah shift ini cocok dengan data lama atau data user yang sedang diedit --}}
                    @if(old('shift_id', $user->shift_id ?? '') == $shift->id) selected @endif>
                    {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('shift_id')" class="mt-2" />
    @else
        <p class="text-sm text-red-600 dark:text-red-400 mt-1">Error: Data shifts tidak ditemukan. Pastikan variabel $shifts dikirim dari controller.</p>
    @endisset
</div>

<!-- Password -->
<div class="mt-4">
    <x-input-label for="password" value="Password" />
    <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
    <small class="text-gray-500 dark:text-gray-400">Kosongkan jika tidak ingin mengubah password.</small>
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<!-- Confirm Password -->
<div class="mt-4">
    <x-input-label for="password_confirmation" value="Konfirmasi Password" />
    <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
</div>

<div class="flex items-center gap-4 mt-6">
    <x-primary-button>Simpan Pengguna</x-primary-button>
    <a href="{{ route('admin.users.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Batal</a>
</div>
