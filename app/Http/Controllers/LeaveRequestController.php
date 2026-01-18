<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LeaveRequest;

class LeaveRequestController extends Controller
{
    /**
     * Menampilkan riwayat pengajuan izin milik pengguna yang sedang login.
     */
    public function index()
    {
        $leaveRequests = Auth::user()->leaveRequests()->latest()->paginate(10);
        return view('leave.index', compact('leaveRequests'));
    }
    public function create()
    {
        return view('leave.create');
    }

    /**
     * Menyimpan pengajuan izin baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'type' => 'required|in:sakit,cuti',
            'reason' => 'required|string|max:1000',
        ]);

        Auth::user()->leaveRequests()->create([
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'reason' => $request->reason,
            // Status default 'pending' sudah diatur di migrasi
        ]);

        return redirect()->route('leave.index')->with('success', 'Pengajuan Anda berhasil dikirim.');
    }
}
