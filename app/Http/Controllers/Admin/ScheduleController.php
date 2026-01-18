<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Models\User;
use App\Models\UserSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    /**
     * Menampilkan halaman manajemen penjadwalan.
     */
    public function index(Request $request)
    {
        // Tentukan tanggal yang dipilih, defaultnya hari ini
        $selectedDate = $request->input('date') ? Carbon::parse($request->input('date')) : Carbon::today();

        // Ambil semua data yang dibutuhkan
        $users = User::where('role', 'user')->orderBy('name')->get();
        $shifts = Shift::orderBy('start_time')->get();

        // Ambil jadwal yang sudah ada untuk tanggal yang dipilih agar lebih efisien
        $schedules = UserSchedule::where('date', $selectedDate->toDateString())
                                 ->get()
                                 ->keyBy('user_id'); // Jadikan user_id sebagai key array

        return view('admin.schedules.index', compact('users', 'shifts', 'schedules', 'selectedDate'));
    }

    /**
     * Menyimpan atau mengupdate jadwal shift untuk pengguna.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'shift_id' => 'nullable|exists:shifts,id', // Boleh kosong untuk hari libur
            'date' => 'required|date',
        ]);

        $userId = $request->input('user_id');
        $shiftId = $request->input('shift_id');
        $date = $request->input('date');

        // Jika admin memilih "Hari Libur" (shift_id kosong), hapus jadwal yang ada
        if (is_null($shiftId)) {
            UserSchedule::where('user_id', $userId)->where('date', $date)->delete();
        } else {
            // Gunakan updateOrCreate untuk efisiensi:
            // Jika sudah ada jadwal untuk user & tanggal tsb, update shift_id nya.
            // Jika belum ada, buat record baru.
            UserSchedule::updateOrCreate(
                [
                    'user_id' => $userId,
                    'date'    => $date,
                ],
                [
                    'shift_id' => $shiftId,
                ]
            );
        }

        // Redirect kembali ke halaman yang sama (tanggal yang sama)
        return redirect()->route('admin.schedules.index', ['date' => $date])->with('success', 'Jadwal berhasil diperbarui.');
    }
}
