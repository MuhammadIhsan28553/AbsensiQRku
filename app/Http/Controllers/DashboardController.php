<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua data absensi milik pengguna
        $allAttendances = $user->attendances()->get();

        // Hitung statistik
        $totalAttendance = $allAttendances->count();
        $attendanceThisMonth = $allAttendances->whereBetween('check_in_time', [now()->startOfMonth(), now()->endOfMonth()])->count();

        // Hitung total keterlambatan
        // Perbaikan: Kita gunakan in_array untuk mengecek status 'Terlambat' (standar baru) atau 'Telat' (data lama)
        $totalLates = $allAttendances->filter(function ($attendance) {
            return in_array($attendance->status, ['Terlambat', 'Telat']);
        })->count();

        // Ambil riwayat absensi untuk paginasi (5 data terbaru)
        $paginatedAttendances = $user->attendances()->latest()->paginate(5);

        // --- PERBAIKAN UTAMA (FITUR QR DINAMIS) ---
        // Alih-alih mengambil token statis dari database ($user->qr_token),
        // kita panggil method getDynamicQrToken() yang sudah dibuat di Model User.
        // Ini memastikan QR code berubah setiap 15 menit.
        $qrToken = $user->getDynamicQrToken(); 

        return view('dashboard', compact(
            'paginatedAttendances',
            'totalAttendance',
            'attendanceThisMonth',
            'totalLates',
            'qrToken'
        ));
    }
}
