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

{{-- Kolom-kolom Form untuk Shift --}}

<!-- Nama Shift -->
<div class="mb-4">
    <x-input-label for="name" value="Nama Shift" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $shift->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<!-- Jam Mulai -->
<div class="mb-4">
    <x-input-label for="start_time" value="Jam Mulai" />
    <x-text-input id="start_time" class="block mt-1 w-full" type="time" name="start_time" :value="old('start_time', isset($shift->start_time) ? \Carbon\Carbon::parse($shift->start_time)->format('H:i') : '')" required />
    <x-input-error :messages="$errors->get('start_time')" class="mt-2" />
</div>

<!-- Jam Selesai -->
<div class="mb-4">
    <x-input-label for="end_time" value="Jam Selesai" />
    <x-text-input id="end_time" class="block mt-1 w-full" type="time" name="end_time" :value="old('end_time', isset($shift->end_time) ? \Carbon\Carbon::parse($shift->end_time)->format('H:i') : '')" required />
    <x-input-error :messages="$errors->get('end_time')" class="mt-2" />
</div>


<div class="flex items-center gap-4 mt-6">
    <x-primary-button>Simpan Shift</x-primary-button>
    <a href="{{ route('admin.shifts.index') }}" class="text-gray-600 hover:text-gray-900">Batal</a>
</div>

