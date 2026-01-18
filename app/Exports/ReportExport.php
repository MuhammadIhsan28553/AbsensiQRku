<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportExport implements FromCollection, WithHeadings, WithStyles
{
    protected $selectedMonth;

    public function __construct($selectedMonth)
    {
        $this->selectedMonth = $selectedMonth;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $startOfMonth = Carbon::parse($this->selectedMonth)->startOfMonth();
        $endOfMonth = Carbon::parse($this->selectedMonth)->endOfMonth();

        $users = User::where('role', 'user')->get();
        $reportData = collect();

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

            $period = CarbonPeriod::create($startOfMonth, $endOfMonth);

            foreach ($period as $date) {
                if ($date->isWeekend()) continue;

                $isOnLeave = $leaveRequests->first(function ($leave) use ($user, $date) {
                    return $leave->user_id == $user->id && $date->between($leave->start_date, $leave->end_date);
                });

                if ($isOnLeave) {
                    if ($isOnLeave->type == 'sakit') $summary['sick']++;
                    else $summary['leave']++;
                    continue;
                }

                $attendanceOnDay = $userAttendances->first(function ($att) use ($date) {
                    return Carbon::parse($att->check_in_time)->isSameDay($date);
                });

                if ($attendanceOnDay) {
                    if ($attendanceOnDay->status == 'Tepat Waktu') $summary['present']++;
                    else $summary['late']++;
                } else {
                    $summary['absent']++;
                }
            }
            $reportData->push($summary);
        }

        return $reportData;
    }

    public function headings(): array
    {
        return [
            'Nama Pengguna',
            'Hadir',
            'Telat',
            'Sakit',
            'Cuti',
            'Alpa',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            // Style baris pertama (header) menjadi bold.
            1    => ['font' => ['bold' => true]],
        ];
    }
}
