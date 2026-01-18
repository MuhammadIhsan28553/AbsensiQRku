<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Edit Pengguna
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Form dibungkus dalam satu kartu utama --}}
            <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 md:p-8 text-gray-900 dark:text-gray-100">
                             {{-- Memanggil form partial pengguna YANG SUDAH DIRENAME --}}
                             {{-- Pastikan Anda sudah rename file _form.blade.php dari folder shifts ke users --}}
                             @include('admin.users._form', ['user' => $user, 'shifts' => $shifts])
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
