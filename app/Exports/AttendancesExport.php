<?php

namespace App\Exports;

use App\Models\Attendance;
use App\Models\LeaveRequest; // <-- TAMBAHKAN INI
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Carbon\Carbon;

class AttendancesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $startDate;
    protected $endDate;

    public function __construct($startDate, $endDate)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // === LOGIKA BARU UNTUK MENGGABUNGKAN DATA ===

        $start = $this->startDate ? Carbon::parse($this->startDate)->startOfDay() : null;
        $end = $this->endDate ? Carbon::parse($this->endDate)->endOfDay() : null;

        // 1. Ambil data absensi
        $attendancesQuery = Attendance::with('user');

        // 2. Ambil data izin yang disetujui
        $leaveRequestsQuery = LeaveRequest::with('user')->where('status', 'approved');

        if ($start && $end) {
            $attendancesQuery->whereBetween('check_in_time', [$start, $end]);
            $leaveRequestsQuery->where(function ($query) use ($start, $end) {
                $query->where('start_date', '<=', $end)
                      ->where('end_date', '>=', $start);
            });
        }

        $attendances = $attendancesQuery->get();
        $leaveRequests = $leaveRequestsQuery->get();

        // 3. Gabungkan kedua data
        $combinedData = collect();

        foreach ($attendances as $item) {
            $item->event_type = 'attendance';
            $item->event_date = $item->check_in_time;
            $combinedData->push($item);
        }

        foreach ($leaveRequests as $leave) {
            $period = Carbon::parse($leave->start_date)->daysUntil($leave->end_date->addDay());
            foreach ($period as $date) {
                // Hanya proses tanggal yang masuk dalam rentang filter
                if ($start && $end && !$date->between($start, $end)) {
                    continue;
                }
                $leaveClone = clone $leave;
                $leaveClone->event_type = 'leave';
                $leaveClone->event_date = $date;
                $combinedData->push($leaveClone);
            }
        }

        // 4. Urutkan berdasarkan tanggal
        return $combinedData->sortByDesc('event_date');
    }

    /**
     * Tentukan heading untuk setiap kolom di file Excel.
     */
    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Email',
            'NIK',
            'Tanggal',
            'Jam Masuk',
            'Status',
            'Keterangan',
        ];
    }

    /**
     * Petakan data ke format array untuk setiap baris.
     *
     * @param mixed $item
     * @return array
     */
    public function map($item): array
    {
        // === LOGIKA BARU UNTUK MEMETAKAN DATA GABUNGAN ===

        // Jika data adalah absensi biasa
        if ($item->event_type === 'attendance') {
            return [
                $item->user->name ?? 'Pengguna Dihapus',
                $item->user->email ?? '-',
                $item->user->nik ?? '-',
                $item->event_date->format('d-m-Y'),
                $item->event_date->format('H:i:s'),
                $item->status,
                $item->notes ?? 'Absen QR',
            ];
        }

        // Jika data adalah izin/cuti
        if ($item->event_type === 'leave') {
            return [
                $item->user->name ?? 'Pengguna Dihapus',
                $item->user->email ?? '-',
                $item->user->nik ?? '-',
                $item->event_date->format('d-m-Y'),
                '-', // Kolom Jam Masuk dikosongkan
                ucfirst($item->type), // Status diisi 'Sakit' atau 'Cuti'
                $item->reason,
            ];
        }

        return []; // Fallback jika ada tipe data lain
    }
}
