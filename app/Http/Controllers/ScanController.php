<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\UserSchedule; // <-- Tambahkan ini
use Carbon\Carbon;          // <-- Tambahkan ini

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
     * dengan logika shift.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function record(Request $request)
    {
        // 1. Validasi dasar: memastikan token dikirim
        $request->validate(['token' => 'required|string']);

        $scannedToken = $request->input('token');
        $user = Auth::user();
        $now = Carbon::now();

        // 2. Validasi QR Code pengguna
        if (!$user->qr_token || $user->qr_token !== $scannedToken) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid atau bukan milik Anda.'
            ]);
        }

        // 3. Cari jadwal pengguna untuk hari ini
        $schedule = UserSchedule::with('shift') // Eager load relasi shift
                                ->where('user_id', $user->id)
                                ->where('date', $now->toDateString())
                                ->first();

        if (!$schedule) {
            return response()->json(['success' => false, 'message' => 'Anda tidak memiliki jadwal shift untuk hari ini.']);
        }

        $shift = $schedule->shift;

        // 4. Cek apakah pengguna sudah absen untuk shift ini
        $alreadyAttended = Attendance::where('user_id', $user->id)
                                    ->where('shift_id', $shift->id)
                                    ->whereDate('check_in_time', $now->toDateString())
                                    ->exists();

        if ($alreadyAttended) {
            return response()->json(['success' => false, 'message' => "Anda sudah melakukan absensi untuk {$shift->name} hari ini."]);
        }

        // 5. Validasi waktu absensi
        $startTime = Carbon::parse($shift->start_time);
        $endTime = Carbon::parse($shift->end_time);

        // Beri toleransi, misal bisa absen 30 menit sebelum shift mulai
        $scanWindowStart = $startTime->copy()->subMinutes(30);

        if (!$now->between($scanWindowStart, $endTime)) {
            return response()->json(['success' => false, 'message' => "Waktu absensi untuk {$shift->name} ({$shift->start_time} - {$shift->end_time}) belum dibuka atau sudah lewat."]);
        }

        // 6. Tentukan status absensi (Tepat Waktu atau Terlambat)
        // Diberi toleransi keterlambatan 5 menit
        $status = $now->isAfter($startTime->addMinutes(5)) ? 'Terlambat' : 'Tepat Waktu';

        // 7. Jika semua valid, simpan data absensi ke database
        Attendance::create([
            'user_id' => $user->id,
            'shift_id' => $shift->id,
            'check_in_time' => $now,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Absensi untuk {$shift->name} berhasil! Status: {$status}."
        ]);
    }
}
