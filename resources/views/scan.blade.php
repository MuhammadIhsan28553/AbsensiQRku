<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Scan QR Code Absensi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Layout Grid Dua Kolom --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">

                {{-- Kolom Kiri: Instruksi dan Hasil Scan --}}
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Pindai Kode QR Anda
                        </h3>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                            Arahkan kode QR absensi Anda ke area pindai di samping. Pastikan pencahayaan cukup dan kode QR terlihat jelas di dalam kotak.
                            <br><span class="text-red-500 font-bold">*Pastikan izin lokasi (GPS) aktif dan diizinkan di browser.</span>
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Status
                            </h3>
                            {{-- Area untuk menampilkan hasil scan --}}
                            <div id="qr-reader-results" class="min-h-[60px] flex items-center justify-center p-4 rounded-md bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                                <span class="text-sm text-gray-500">Menunggu inisialisasi...</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Area Scanner --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    {{-- Area untuk menampilkan kamera --}}
                    <div id="qr-reader" class="w-full"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Memuat library QR Scanner dari CDN --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

    <script>
        const resultContainer = document.getElementById('qr-reader-results');
        let html5QrcodeScanner;
        
        // --- Variabel Global untuk Lokasi ---
        let userLat = null;
        let userLng = null;
        let locationError = null;

        // --- Fungsi Helper Feedback UI ---
        function createFeedbackHtml(type, message) {
            let icon, colorClass;
            switch (type) {
                case 'loading':
                    icon = `<svg class="animate-spin h-5 w-5 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
                    colorClass = 'text-blue-600 dark:text-blue-400';
                    break;
                case 'success':
                    icon = `<svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
                    colorClass = 'text-green-600 dark:text-green-400';
                    break;
                case 'error':
                    icon = `<svg class="h-5 w-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>`;
                    colorClass = 'text-red-600 dark:text-red-400';
                    break;
            }
            return `<div class="flex items-center font-semibold ${colorClass}">${icon} ${message}</div>`;
        }

        // --- 1. Request Lokasi Saat Halaman Dimuat (UTAMA) ---
        document.addEventListener('DOMContentLoaded', (event) => {
            // Tampilkan status sedang mencari lokasi
            resultContainer.innerHTML = createFeedbackHtml('loading', 'Meminta izin lokasi GPS...');

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    // Success Callback: Lokasi Ditemukan
                    function(position) {
                        userLat = position.coords.latitude;
                        userLng = position.coords.longitude;
                        console.log("Lokasi didapat:", userLat, userLng);
                        
                        resultContainer.innerHTML = createFeedbackHtml('success', 'Lokasi siap! Silakan scan QR Code.');
                        
                        // Inisialisasi scanner setelah lokasi didapat
                        initScanner();
                    }, 
                    // Error Callback: Gagal Dapat Lokasi
                    function(error) {
                        let msg = "Gagal mendapatkan lokasi.";
                        switch(error.code) {
                            case error.PERMISSION_DENIED:
                                msg = "Izin lokasi ditolak. Harap reset izin situs ini di pengaturan browser.";
                                break;
                            case error.POSITION_UNAVAILABLE:
                                msg = "Informasi lokasi tidak tersedia di perangkat ini.";
                                break;
                            case error.TIMEOUT:
                                msg = "Waktu permintaan lokasi habis. Coba refresh halaman.";
                                break;
                        }
                        locationError = msg;
                        resultContainer.innerHTML = createFeedbackHtml('error', msg);
                        
                        // Tampilkan alert agar user sadar
                        alert(msg);
                        
                        // Tetap jalankan scanner, tapi nanti akan error saat scan
                        initScanner();
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            } else {
                locationError = "Browser ini tidak mendukung fitur GPS.";
                resultContainer.innerHTML = createFeedbackHtml('error', locationError);
                alert(locationError);
            }
        });

        // --- Inisialisasi Scanner ---
        function initScanner() {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader", 
                { fps: 10, qrbox: {width: 250, height: 250} }, 
                false
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        }

        // --- 2. Callback Saat QR Berhasil Discan ---
        function onScanSuccess(decodedText, decodedResult) {
            // Pause scanner agar tidak scan berkali-kali
            html5QrcodeScanner.pause();

            // Cek apakah lokasi sudah tersedia?
            if (userLat === null || userLng === null) {
                let msg = locationError ? locationError : "Sedang mengambil lokasi... Tunggu sebentar.";
                resultContainer.innerHTML = createFeedbackHtml('error', msg);
                
                // Jika error belum pasti (masih loading), beri tahu user
                if (!locationError) {
                    alert("Lokasi belum ditemukan. Pastikan GPS aktif dan tunggu sebentar.");
                } else {
                    alert(msg);
                }

                // Resume scanner setelah 3 detik
                setTimeout(() => html5QrcodeScanner.resume(), 3000);
                return;
            }

            // Jika lokasi aman, kirim data ke server
            sendAttendanceData(decodedText, userLat, userLng);
        }

        function onScanFailure(error) {
            // Biarkan kosong
        }

        // --- 3. Kirim Data ke Server ---
        function sendAttendanceData(token, lat, lng) {
            resultContainer.innerHTML = createFeedbackHtml('loading', 'Memvalidasi Absensi...');

            fetch('{{ route("scan.record") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ 
                    token: token,
                    latitude: lat,
                    longitude: lng
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    resultContainer.innerHTML = createFeedbackHtml('success', data.message);
                    html5QrcodeScanner.clear(); // Hapus scanner
                    
                    // Redirect ke dashboard setelah 2 detik
                    setTimeout(() => window.location.href = '{{ route("dashboard") }}', 2000);
                } else {
                    resultContainer.innerHTML = createFeedbackHtml('error', data.message);
                    alert(data.message); // Munculkan pesan error (misal: Jarak Terlalu Jauh)
                    
                    // Resume scanner setelah 3 detik
                    setTimeout(() => html5QrcodeScanner.resume(), 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultContainer.innerHTML = createFeedbackHtml('error', 'Terjadi kesalahan sistem.');
                setTimeout(() => html5QrcodeScanner.resume(), 3000);
            });
        }
    </script>
</x-app-layout>
