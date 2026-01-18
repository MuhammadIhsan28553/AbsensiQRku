<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Manajemen Shift
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4">
                <a href="{{ route('admin.shifts.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Tambah Shift Baru
                </a>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded">{{ session('success') }}</div>
                    @endif
                    <table class="min-w-full bg-white">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="py-2 px-4 text-left">Nama Shift</th>
                                <th class="py-2 px-4 text-left">Jam Mulai</th>
                                <th class="py-2 px-4 text-left">Jam Selesai</th>
                                <th class="py-2 px-4">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($shifts as $shift)
                                <tr class="border-b">
                                    <td class="py-2 px-4">{{ $shift->name }}</td>
                                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}</td>
                                    <td class="py-2 px-4">{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}</td>
                                    <td class="py-2 px-4 flex justify-center gap-2">
                                        <a href="{{ route('admin.shifts.edit', $shift) }}" class="text-yellow-600 hover:text-yellow-900 font-semibold">Edit</a>
                                        <form action="{{ route('admin.shifts.destroy', $shift) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus shift ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">Belum ada data shift.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

