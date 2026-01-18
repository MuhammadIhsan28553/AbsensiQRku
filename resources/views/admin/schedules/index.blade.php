<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Penjadwalan
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-4 bg-white p-4 rounded-lg shadow-sm">
                <form action="{{ route('admin.schedules.index') }}" method="GET">
                    <label for="date" class="block text-sm font-medium text-gray-700">Pilih Tanggal:</label>
                    <div class="flex items-center gap-2 mt-1">
                        <input type="date" name="date" id="date" value="{{ $selectedDate->format('Y-m-d') }}" class="rounded-md border-gray-300 shadow-sm">
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Tampilkan Jadwal</button>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">Jadwal untuk Tanggal: {{ $selectedDate->format('d F Y') }}</h3>
                    @if (session('success'))
                        <div class="mb-4 p-3 bg-green-100 text-green-700 rounded-md">{{ session('success') }}</div>
                    @endif
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4 text-left">Nama Pengguna</th>
                                <th class="py-2 px-4 text-left">Jadwal Shift</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="border-b">
                                    <td class="py-3 px-4">{{ $user->name }}</td>
                                    <td class="py-3 px-4">
                                        <form action="{{ route('admin.schedules.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="user_id" value="{{ $user->id }}">
                                            <input type="hidden" name="date" value="{{ $selectedDate->format('Y-m-d') }}">
                                            <div class="flex items-center gap-2">
                                                <select name="shift_id" class="rounded-md border-gray-300 shadow-sm w-full">
                                                    <option value="">-- Hari Libur --</option>
                                                    @foreach ($shifts as $shift)
                                                        <option value="{{ $shift->id }}"
                                                            {{-- Cek apakah user ini punya jadwal & cocok dengan shift ini --}}
                                                            @if(isset($schedules[$user->id]) && $schedules[$user->id]->shift_id == $shift->id) selected @endif>
                                                            {{ $shift->name }} ({{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm">Simpan</button>
                                            </div>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center py-4">Tidak ada data pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
