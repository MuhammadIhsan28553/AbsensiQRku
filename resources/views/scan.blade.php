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
                            <br><span class="text-red-500 font-bold">*Pastikan izin lokasi (GPS) aktif.</span>
                        </p>
                    </div>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                             <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                Status
                            </h3>
                            {{-- Area untuk menampilkan hasil scan dengan styling modern --}}
                            <div id="qr-reader-results" class="min-h-[60px] flex items-center justify-center p-4 rounded-md bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700">
                                <span class="text-sm text-gray-500">Arahkan kamera ke kode QR...</span>
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
        let lastResult = null;
        let html5QrcodeScanner; // Definisikan di scope yang lebih luas

        // --- Fungsi untuk membuat template feedback ---
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

        function onScanSuccess(decodedText, decodedResult) {
            // Mencegah scan berulang-ulang dengan cepat
            if (decodedText !== lastResult) {
                lastResult = decodedText;

                // Hentikan scanner agar tidak terus memindai
                html5QrcodeScanner.pause();

                // 1. Tampilkan status loading mencari lokasi
                resultContainer.innerHTML = createFeedbackHtml('loading', 'Mendapatkan lokasi GPS...');

                // 2. Ambil Lokasi GPS Browser
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        // --- SUKSES DAPAT LOKASI ---
                        const latitude = position.coords.latitude;
                        const longitude = position.coords.longitude;
                        
                        // Lanjut kirim ke server
                        sendAttendanceData(decodedText, latitude, longitude);

                    }, function(error) {
                        // --- GAGAL DAPAT LOKASI ---
                        let errorMsg = "Gagal mendapatkan lokasi GPS.";
                        if (error.code === error.PERMISSION_DENIED) {
                            errorMsg = "Izin lokasi ditolak. Harap aktifkan GPS browser.";
                        } else if (error.code === error.POSITION_UNAVAILABLE) {
                            errorMsg = "Informasi lokasi tidak tersedia.";
                        } else if (error.code === error.TIMEOUT) {
                            errorMsg = "Waktu permintaan lokasi habis.";
                        }
                        
                        resultContainer.innerHTML = createFeedbackHtml('error', errorMsg);
                        
                        // Izinkan scan lagi setelah 3 detik
                        setTimeout(() => { 
                            lastResult = null; 
                            html5QrcodeScanner.resume(); 
                        }, 3000);
                    });
                } else {
                    resultContainer.innerHTML = createFeedbackHtml('error', 'Browser Anda tidak mendukung GPS.');
                }
            }
        }

        // Fungsi terpisah untuk mengirim data ke server
        function sendAttendanceData(token, lat, lng) {
            resultContainer.innerHTML = createFeedbackHtml('loading', 'Memvalidasi & menyimpan...');

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
                    // Hentikan total scanner setelah berhasil
                    html5QrcodeScanner.clear();
                    // Alihkan ke dashboard setelah 2 detik
                    setTimeout(() => {
                        window.location.href = '{{ route("dashboard") }}';
                    }, 2000);
                } else {
                    resultContainer.innerHTML = createFeedbackHtml('error', data.message);
                    // Izinkan scan lagi setelah 3 detik agar user sempat baca error
                    setTimeout(() => {
                        lastResult = null;
                        html5QrcodeScanner.resume(); 
                    }, 3000);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                resultContainer.innerHTML = createFeedbackHtml('error', 'Terjadi kesalahan sistem.');
                setTimeout(() => {
                    lastResult = null;
                    html5QrcodeScanner.resume();
                }, 3000);
            });
        }

        function onScanFailure(error) {
            // Tidak melakukan apa-apa saat gagal (misal tidak menemukan QR code)
        }

        // Inisialisasi scanner saat dokumen dimuat
        document.addEventListener('DOMContentLoaded', (event) => {
            html5QrcodeScanner = new Html5QrcodeScanner(
                "qr-reader",
                { fps: 10, qrbox: {width: 250, height: 250} },
                false // verbose
            );
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        });

    </script>
</x-app-layout>
