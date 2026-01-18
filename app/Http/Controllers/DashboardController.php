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

        // Hitung total keterlambatan dengan memanggil accessor 'status'
        $totalLates = $allAttendances->filter(function ($attendance) {
            return $attendance->status === 'Telat';
        })->count();

        // Ambil riwayat absensi untuk paginasi
        $paginatedAttendances = $user->attendances()->latest()->paginate(5);

        // Ambil QR token milik user
        $qrToken = $user->qr_token;

        return view('dashboard', compact(
            'paginatedAttendances',
            'totalAttendance',
            'attendanceThisMonth',
            'totalLates',
            'qrToken'
        ));
    }
}
