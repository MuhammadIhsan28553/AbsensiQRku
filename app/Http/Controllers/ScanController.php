<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\UserSchedule;
use App\Models\Setting; // <-- Pastikan Model Setting diimport
use Carbon\Carbon;

class ScanController extends Controller
{
    /**
     * Menampilkan halaman untuk memindai QR code.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('scan');
    }

    /**
     * Menerima, memvalidasi, dan mencatat data absensi dari hasil scan QR code
     * dengan logika shift, token dinamis, dan validasi lokasi GPS.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function record(Request $request)
    {
        // 1. Validasi Input: Token QR + Koordinat GPS
        $request->validate([
            'token' => 'required|string',
            'latitude' => 'required|numeric',  // Koordinat wajib ada
            'longitude' => 'required|numeric',
        ]);

        $scannedToken = $request->input('token');
        $userLatitude = $request->input('latitude');
        $userLongitude = $request->input('longitude');
        
        $user = Auth::user();
        $now = Carbon::now();

        // 2. Validasi QR Code Dinamis
        // Menggantikan validasi statis lama. Mengecek apakah token valid & belum expired (15 menit).
        if (!$this->isValidDynamicToken($scannedToken, $user)) {
             return response()->json([
                'success' => false,
                'message' => 'QR Code kedaluwarsa atau tidak valid. Silakan refresh dashboard Anda.'
            ]);
        }

        // 3. Validasi Lokasi GPS (Geofencing)
        // Ambil pengaturan kantor dari database (Setting)
        $officeLat = Setting::where('key', 'office_latitude')->value('value');
        $officeLng = Setting::where('key', 'office_longitude')->value('value');
        $maxRadius = Setting::where('key', 'office_radius')->value('value') ?? 100; // Default 100 meter

        $distance = 0;
        // Hanya cek lokasi jika admin sudah mengatur koordinat kantor
        if ($officeLat && $officeLng) {
            $distance = $this->calculateDistance($userLatitude, $userLongitude, $officeLat, $officeLng);
            
            if ($distance > $maxRadius) {
                 return response()->json([
                    'success' => false,
                    'message' => "Anda berada di luar jangkauan kantor. Jarak: " . round($distance) . "m (Maks: {$maxRadius}m)."
                ]);
            }
        }

        // 4. Cari jadwal pengguna untuk hari ini
        $schedule = UserSchedule::with('shift') // Eager load relasi shift
                                ->where('user_id', $user->id)
                                ->where('date', $now->toDateString())
                                ->first();

        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki jadwal shift untuk hari ini.']);
        }

        $shift = $schedule->shift;

        // 5. Cek apakah pengguna sudah absen untuk shift ini
        $alreadyAttended = Attendance::where('user_id', $user->id)
                                    ->where('shift_id', $shift->id)
                                    ->whereDate('check_in_time', $now->toDateString())
                                    ->exists();

        if ($alreadyAttended) {
            return response()->json(['success' => false, 'message' => "Anda sudah melakukan absensi untuk {$shift->name} hari ini."]);
        }

        // 6. Validasi waktu absensi
        $startTime = Carbon::parse($shift->start_time);
        $endTime = Carbon::parse($shift->end_time);

        // Beri toleransi, misal bisa absen 30 menit sebelum shift mulai
        $scanWindowStart = $startTime->copy()->subMinutes(30);

        if (!$now->between($scanWindowStart, $endTime)) {
            return response()->json(['success' => false, 'message' => "Waktu absensi untuk {$shift->name} ({$shift->start_time} - {$shift->end_time}) belum dibuka atau sudah lewat."]);
        }

        // 7. Tentukan status absensi (Tepat Waktu atau Terlambat)
        // Diberi toleransi keterlambatan 5 menit
        $status = $now->isAfter($startTime->addMinutes(5)) ? 'Terlambat' : 'Tepat Waktu';

        // 8. Jika semua valid, simpan data absensi ke database
        Attendance::create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'check_in_time' => $now,
            'status' => $status,
            'notes' => "Jarak: " . round($distance) . "m", // Simpan jarak di notes
        ]);

        return response()->json([
            'success' => true,
            'message' => "Absensi untuk {$shift->name} berhasil! Status: {$status}."
        ]);
    }

    /**
     * Helper: Validasi Token Dinamis
     */
    private function isValidDynamicToken($token, $user)
    {
        try {
            $decoded = base64_decode($token);
            $parts = explode('|', $decoded);

            // Format token: ID | NIK | TIMEBLOCK | SIGNATURE
            if (count($parts) !== 4) return false;

            [$id, $nik, $timestampBlock, $signature] = $parts;

            // 1. Cek kecocokan User ID dan NIK
            if ($id != $user->id || $nik != $user->nik) return false;

            // 2. Cek Signature (Integritas Data)
            $dataToCheck = "{$id}|{$nik}|{$timestampBlock}";
            $expectedSignature = hash_hmac('sha256', $dataToCheck, config('app.key'));

            if (!hash_equals($expectedSignature, $signature)) return false;

            // 3. Cek Waktu (Time Block)
            // 1 blok = 15 menit (900 detik)
            $currentBlock = floor(now()->timestamp / 900);
            
            // Izinkan blok waktu saat ini ATAU 1 blok sebelumnya (toleransi 15 menit)
            if ($timestampBlock == $currentBlock || $timestampBlock == ($currentBlock - 1)) {
                return true;
            }

            return false;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Helper: Hitung Jarak (Haversine Formula) dalam Meter
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
