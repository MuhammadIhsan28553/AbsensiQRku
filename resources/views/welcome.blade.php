<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi-Ku</title>
    {{-- Kita pakai Tailwind CSS via CDN agar cepat --}}
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">

    <div class="flex flex-col min-h-screen">

        <nav class="bg-white shadow-md">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <a href="/" class="font-bold text-2xl text-blue-600">Absensi-Ku</a>
                <div>
                    <a href="{{ route('login') }}" class="px-4 py-2 text-gray-600 hover:text-blue-600">Login</a>
                    <a href="{{ route('register') }}" class="ml-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Register</a>
                </div>
            </div>
        </nav>

        <main class="flex-grow container mx-auto px-6 py-20 text-center">
            <h1 class="text-5xl font-extrabold text-gray-800 mb-4">
                Selamat Datang di Absensi-Ku
            </h1>
            <p class="text-lg text-gray-600 mb-8">
                Solusi modern untuk mencatat kehadiran dengan mudah dan cepat menggunakan QR Code.
            </p>
            <a href="#" class="px-8 py-3 bg-blue-600 text-white text-lg font-semibold rounded-lg hover:bg-blue-700">
                Mulai Absen Sekarang
            </a>
        </main>

        <footer class="bg-white mt-auto">
            <div class="container mx-auto px-6 py-4 text-center text-gray-500">
                &copy; 2025 Absensi-Ku. All Rights Reserved.
            </div>
        </footer>

    </div>

</body>
</html>
