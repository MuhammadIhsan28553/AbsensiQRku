<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Absensi-Ku - Absensi QR Code Modern</title>
    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Google Fonts: Poppins untuk kesan modern --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            400: '#38bdf8', // Sky-400 (Biru Muda Utama)
                            500: '#0ea5e9', // Sky-500
                            600: '#0284c7', // Sky-600
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .blob {
            position: absolute;
            filter: blur(40px);
            z-index: -1;
            opacity: 0.4;
            animation: move 10s infinite alternate;
        }
        @keyframes move {
            from { transform: translate(0, 0) scale(1); }
            to { transform: translate(20px, -20px) scale(1.1); }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 font-sans antialiased overflow-x-hidden relative">

    {{-- Dekorasi Latar Belakang (Blurry Blobs) --}}
    <div class="blob bg-brand-400 w-64 h-64 rounded-full top-0 left-0 -ml-20 -mt-20"></div>
    <div class="blob bg-blue-400 w-96 h-96 rounded-full bottom-0 right-0 -mr-20 -mb-20"></div>

    <div class="flex flex-col min-h-screen relative z-10">

        {{-- Navbar dengan efek Glassmorphism --}}
        <nav class="fixed w-full z-50 top-0 transition-all duration-300 bg-white/70 backdrop-blur-md border-b border-white/20 shadow-sm">
            <div class="container mx-auto px-6 py-4 flex justify-between items-center">
                <a href="/" class="flex items-center gap-2 group">
                    {{-- Ikon QR Sederhana dengan SVG --}}
                    <div class="bg-gradient-to-br from-brand-400 to-brand-600 text-white p-2 rounded-lg shadow-lg group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 6h6v6H6V6zm12 0h-6v6h6V6zm-6 12H6v-6h6v6z" />
                        </svg>
                    </div>
                    <span class="font-bold text-xl tracking-tight text-slate-800 group-hover:text-brand-600 transition-colors">Absensi-Ku</span>
                </a>
                
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="font-medium text-slate-600 hover:text-brand-600 transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-brand-500 text-white font-medium rounded-full shadow-lg shadow-brand-500/30 hover:bg-brand-600 hover:shadow-brand-500/50 transition-all transform hover:-translate-y-0.5">
                        Daftar Akun
                    </a>
                </div>

                {{-- Mobile Menu Button (Hanya visual untuk contoh ini) --}}
                <button class="md:hidden text-slate-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
                    </svg>
                </button>
            </div>
        </nav>

        {{-- Hero Section --}}
        <main class="flex-grow flex items-center pt-24 pb-12">
            <div class="container mx-auto px-6 flex flex-col-reverse md:flex-row items-center gap-12">
                
                {{-- Kolom Kiri: Teks --}}
                <div class="w-full md:w-1/2 text-center md:text-left space-y-6">
                    <div class="inline-block px-4 py-1.5 rounded-full bg-brand-50 border border-brand-100 text-brand-600 text-sm font-semibold mb-2">
                        🚀 Versi Terbaru 2.0
                    </div>
                    <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 leading-tight">
                        Absensi Jadi Lebih <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-400 to-blue-600">
                            Cepat & Modern
                        </span>
                    </h1>
                    <p class="text-lg text-slate-600 md:pr-10 leading-relaxed">
                        Tinggalkan cara lama. Catat kehadiran karyawan atau siswa dengan teknologi <span class="font-semibold text-slate-800">QR Code</span> yang akurat, real-time, dan mudah diakses dari mana saja.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center md:justify-start pt-4">
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-gradient-to-r from-brand-400 to-brand-600 text-white text-lg font-semibold rounded-xl shadow-xl shadow-brand-500/30 hover:shadow-2xl hover:scale-105 transition-all duration-300">
                            Mulai Absen Sekarang
                        </a>
                        <a href="#fitur" class="px-8 py-4 bg-white text-slate-700 border border-slate-200 text-lg font-semibold rounded-xl hover:bg-slate-50 hover:border-brand-300 transition-all duration-300">
                            Pelajari Fitur
                        </a>
                    </div>
                    
                    <div class="pt-8 flex items-center justify-center md:justify-start gap-6 text-slate-400 grayscale opacity-70">
                        {{-- Placeholder Logo Partner/Tech (Hanya visual) --}}
                        <span class="font-bold text-xl">Laravel</span>
                        <span class="font-bold text-xl">Tailwind</span>
                        <span class="font-bold text-xl">MySQL</span>
                    </div>
                </div>

                {{-- Kolom Kanan: Ilustrasi Visual CSS --}}
                <div class="w-full md:w-1/2 flex justify-center relative">
                    <div class="relative w-72 h-72 md:w-96 md:h-96 bg-gradient-to-tr from-brand-100 to-white rounded-3xl shadow-2xl border border-white/50 flex items-center justify-center transform rotate-3 hover:rotate-0 transition-all duration-500">
                        
                        {{-- Simulasi Kartu QR --}}
                        <div class="absolute inset-0 bg-white/40 backdrop-blur-sm rounded-3xl"></div>
                        
                        <div class="relative z-10 bg-white p-6 rounded-2xl shadow-xl text-center">
                            <div class="w-48 h-48 bg-slate-900 rounded-lg flex items-center justify-center mb-4 relative overflow-hidden group">
                                {{-- Efek Scan --}}
                                <div class="absolute w-full h-1 bg-brand-400 shadow-[0_0_15px_rgba(56,189,248,0.8)] top-0 animate-[scan_2s_infinite]"></div>
                                
                                {{-- Fake QR Pattern --}}
                                <div class="grid grid-cols-4 gap-1 p-2">
                                    <div class="w-8 h-8 bg-white rounded-sm"></div>
                                    <div class="w-8 h-8 bg-white rounded-sm opacity-20"></div>
                                    <div class="w-8 h-8 bg-white rounded-sm"></div>
                                    <div class="w-8 h-8 bg-white rounded-sm opacity-50"></div>
                                    <div class="w-8 h-8 bg-white rounded-sm opacity-30"></div>
                                    <div class="w-8 h-8 bg-white rounded-sm"></div>
                                    <div class="w-8 h-8 bg-white rounded-sm opacity-10"></div>
                                    <div class="w-8 h-8 bg-white rounded-sm"></div>
                                </div>
                            </div>
                            <p class="font-bold text-slate-800">Scan untuk Masuk</p>
                            <p class="text-xs text-slate-500">{{ date('d F Y') }}</p>
                        </div>

                        {{-- Floating Badge --}}
                        <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-xl shadow-lg flex items-center gap-3 animate-bounce">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                ✓
                            </div>
                            <div>
                                <p class="text-xs text-slate-500">Status</p>
                                <p class="font-bold text-slate-800 text-sm">Hadir Tepat Waktu</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <footer class="bg-white/50 backdrop-blur-sm border-t border-slate-200 mt-auto">
            <div class="container mx-auto px-6 py-6 flex flex-col md:flex-row justify-between items-center text-sm text-slate-500">
                <p>&copy; 2025 <span class="font-bold text-brand-600">Absensi-Ku</span>. All Rights Reserved.</p>
                <div class="flex gap-4 mt-2 md:mt-0">
                    <a href="#" class="hover:text-brand-600">Privacy Policy</a>
                    <a href="#" class="hover:text-brand-600">Terms of Service</a>
                </div>
            </div>
        </footer>

    </div>

    {{-- Animasi Custom untuk Garis Scan --}}
    <style>
        @keyframes scan {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
    </style>
</body>
</html>
