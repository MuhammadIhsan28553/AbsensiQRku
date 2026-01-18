<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Exports\ReportExport; // <-- TAMBAHKAN INI
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Tentukan bulan dan tahun, default ke bulan ini jika tidak ada input
        $selectedMonth = $request->input('month', now()->format('Y-m'));
        $startOfMonth = Carbon::parse($selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::parse($selectedMonth)->endOfMonth();

        // Ambil semua pengguna
        $users = User::where('role', 'user')->get();

        $reportData = [];

        // Ambil semua data absensi dan izin pada rentang bulan yang dipilih untuk efisiensi query
        $attendances = Attendance::whereBetween('check_in_time', [$startOfMonth, $endOfMonth])->get()->groupBy('user_id');
        $leaveRequests = LeaveRequest::where('status', 'approved')
            ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                $query->where('start_date', '<=', $endOfMonth)
                      ->where('end_date', '>=', $startOfMonth);
            })->get();

        foreach ($users as $user) {
            $userAttendances = $attendances->get($user->id, collect());

            $summary = [
                'name' => $user->name,
                'present' => 0,
                'late' => 0,
                'sick' => 0,
                'leave' => 0,
                'absent' => 0,
            ];

            // Buat periode untuk setiap hari dalam sebulan
            $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

            foreach ($period as $date) {
                // Lewati hari Sabtu dan Minggu
                if ($date->isWeekend()) {
                    continue;
                }

                // Cek apakah ada data izin pada tanggal ini
                $isOnLeave = $leaveRequests->first(function ($leave) use ($user, $date) {
                    return $leave->user_id == $user->id && $date->between($leave->start_date, $leave->end_date);
                });

                if ($isOnLeave) {
                    if ($isOnLeave->type == 'sakit') {
                        $summary['sick']++;
                    } else { // 'cuti'
                        $summary['leave']++;
                    }
                    continue; // Lanjut ke hari berikutnya
                }

                // Cek apakah ada data absensi pada tanggal ini
                $attendanceOnDay = $userAttendances->first(function ($att) use ($date) {
                    return Carbon::parse($att->check_in_time)->isSameDay($date);
                });

                if ($attendanceOnDay) {
                    if ($attendanceOnDay->status == 'Tepat Waktu') {
                        $summary['present']++;
                    } else { // 'Telat'
                        $summary['late']++;
                    }
                } else {
                    // Jika tidak ada data absen dan tidak ada data izin, maka dianggap Alpa
                    $summary['absent']++;
                }
            }
            $reportData[] = $summary;
        }

        return view('admin.reports.index', compact('reportData', 'selectedMonth'));
    }
    public function exportReport(Request $request)
    {
        $selectedMonth = $request->query('month', now()->format('Y-m'));
        $monthName = Carbon::parse($selectedMonth)->format('F-Y');

        $fileName = 'laporan-kehadiran-' . $monthName . '.xlsx';

        return Excel::download(new ReportExport($selectedMonth), $fileName);
    }
}
