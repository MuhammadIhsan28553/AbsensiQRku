<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Shift: {{ $shift->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.shifts.update', $shift) }}" method="POST">
                        @csrf
                        @method('PUT') {{-- Penting untuk proses update --}}

                        {{-- Memanggil form partial KHUSUS SHIFT --}}
                        {{-- Ganti _form menjadi _shift_form --}}
                        @include('admin.shifts._shift_form', ['shift' => $shift])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

