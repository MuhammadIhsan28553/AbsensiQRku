<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Detail Pengguna
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <!-- Header Detail -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-6 border-b">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-16 w-16 bg-gray-200 rounded-full flex items-center justify-center">
                                {{-- Menampilkan inisial nama --}}
                                <span class="text-2xl font-bold text-gray-600">{{ strtoupper(substr($user->name, 0, 2)) }}</span>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-2xl font-bold">{{ $user->name }}</h3>
                                <p class="text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-4 md:mt-0">
                            <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                                &larr; Kembali
                            </a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                                Edit Pengguna
                            </a>
                        </div>
                    </div>

                    <!-- Grid Konten -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                        <!-- Kolom Informasi Pengguna -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4 text-gray-800">Informasi Pengguna</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="text-sm font-medium text-gray-500">NIK</label>
                                    <p class="text-lg">{{ $user->nik }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">No. Registrasi</label>
                                    <p class="text-lg">{{ $user->no_regis }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Role</label>
                                    <p class="text-lg">{{ ucfirst($user->role) }}</p>
                                </div>
                                <div>
                                    <label class="text-sm font-medium text-gray-500">Shift Default</label>
                                    <p>
                                        @if ($user->shift)
                                            <span class="px-2 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full text-xs">
                                                {{ $user->shift->name }} ({{ \Carbon\Carbon::parse($user->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($user->shift->end_time)->format('H:i') }})
                                            </span>
                                        @else
                                            <span class="text-gray-500">Belum diatur</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Kolom QR Code -->
                        <div class="text-center md:text-left">
                             <h4 class="text-lg font-semibold mb-4 text-gray-800">QR Code Absensi</h4>
                             @if($user->qr_token)
                                <div class="flex justify-center md:justify-start">
                                    <div class="p-4 border rounded-lg inline-block">
                                        {!! QrCode::size(200)->generate($user->qr_token) !!}
                                    </div>
                                </div>
                                <a href="{{ route('admin.users.downloadQr', $user) }}" class="mt-4 inline-block w-full md:w-auto px-6 py-2 bg-green-600 text-white text-center rounded-md hover:bg-green-700">
                                    Download QR Code
                                </a>
                            @else
                                <div class="p-4 border rounded-lg text-center text-gray-500">
                                    <p>QR Code belum dibuat.</p>
                                    <p class="text-sm">Silakan edit pengguna untuk generate QR Code.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

