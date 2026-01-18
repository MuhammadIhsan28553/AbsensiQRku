<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Tambah Shift Baru
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('admin.shifts.store') }}" method="POST">
                        @csrf
                        {{-- Memanggil form partial KHUSUS SHIFT --}}
                        {{-- Pastikan nama file partial sudah benar --}}
                       @include('admin.shifts._shift_form', ['shift' => $shift])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

