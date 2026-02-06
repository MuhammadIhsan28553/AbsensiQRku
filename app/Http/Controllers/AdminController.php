<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Setting;
use App\Models\Shift;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode; 
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Exports\AttendancesExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin.
     */
    public function dashboard()
    {
        // 1. Ambil semua absensi untuk hari ini
        $todayAttendances = Attendance::with('user', 'shift') 
                                ->whereDate('check_in_time', today())
                                ->get();

        // 2. Hitung statistik
        $totalUsers = User::where('role', 'user')->count();
        $attendedToday = $todayAttendances->count();

        // 3. Hitung yang telat hari ini
        $lateToday = $todayAttendances->filter(function ($attendance) {
            $workStartTime = '08:00'; // Default global
            if ($attendance->shift) {
                $workStartTime = $attendance->shift->start_time;
            } else {
                 $workStartTimeSetting = Setting::where('key', 'work_start_time')->first();
                 if ($workStartTimeSetting) {
                    $workStartTime = $workStartTimeSetting->value;
                 }
            }
            try {
                $startTime = Carbon::parse($workStartTime);
                 $deadline = $startTime->addMinutes(5);
                 return Carbon::parse($attendance->check_in_time)->isAfter($deadline);
            } catch (\Exception $e) {
                 return false;
            }
        })->count();

        // 4. Ambil 5 aktivitas absensi terbaru
        $recentAttendances = Attendance::with('user')->latest('check_in_time')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'attendedToday',
            'lateToday',
            'recentAttendances'
        ));
    }

    /**
     * Menampilkan daftar semua pengguna.
     */
    public function index()
    {
        $today = now()->toDateString();
        $users = User::where('role', 'user')
                    ->with(['schedules' => function ($query) use ($today) {
                        $query->where('date', $today)->with('shift');
                    }])
                    ->latest()
                    ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create()
    {
        $shifts = Shift::orderBy('name')->get();
        $user = new User();
        return view('admin.users.create', compact('shifts', 'user'));
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'required|string|unique:users,nik',
            'no_regis' => 'required|string|unique:users,no_regis',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        // Generate QR Token awal (opsional, karena nanti pakai dinamis)
        $qrToken = "USER_ID:{$request->email};NIK:{$request->nik};REG:{$request->no_regis}";

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nik' => $request->nik,
            'no_regis' => $request->no_regis,
            'qr_token' => $qrToken,
            'shift_id' => $request->shift_id,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman detail untuk satu pengguna.
     */
    public function show(User $user)
    {
        $user->load('shift');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     */
    public function edit(User $user)
    {
        $shifts = Shift::orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'shifts'));
    }

    /**
     * Mengupdate data pengguna di database.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nik' => 'required|string|unique:users,nik,' . $user->id,
            'no_regis' => 'required|string|unique:users,no_regis,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'shift_id' => 'nullable|exists:shifts,id',
        ]);

        $qrToken = "USER_ID:{$request->email};NIK:{$request->nik};REG:{$request->no_regis}";

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'nik' => $request->nik,
            'no_regis' => $request->no_regis,
            'qr_token' => $qrToken,
            'shift_id' => $request->shift_id,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dari database.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Menghasilkan dan mendownload QR code sebagai file PNG.
     * --- BAGIAN INI DIPERBAIKI UNTUK MENGGUNAKAN DYNAMIC TOKEN ---
     */
    public function downloadQr(User $user)
    {
        // Kita tidak perlu cek $user->qr_token statis lagi, karena kita generate realtime.
        
        try {
            // 1. Generate Token Dinamis (Sama seperti di Dashboard Pegawai)
            $dynamicToken = $user->getDynamicQrToken();

            // 2. Buat Gambar QR
            $qrCodeImage = QrCode::format('png')
                                 ->driver('gd')
                                 ->size(300)
                                 ->margin(2) // Tambah margin agar rapi
                                 ->generate($dynamicToken);
                                 
        } catch (\Exception $e) {
            Log::error('Error generating QR Code: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal membuat QR Code. Periksa log server.');
        }

        // 3. Nama file dengan timestamp agar admin tau ini QR baru
        $fileName = 'qr-code-' . Str::slug($user->name) . '-' . now()->format('Hi') . '.png';

        return new Response($qrCodeImage, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Menampilkan riwayat gabungan (absensi & izin) dengan filter tanggal.
     */
    public function attendances(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : null;
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : null;

        $attendancesQuery = Attendance::with('user', 'shift')->latest('check_in_time');
        $leaveRequestsQuery = LeaveRequest::with('user')->where('status', 'approved')->latest('start_date');

        if ($startDate && $endDate) {
            $attendancesQuery->whereBetween('check_in_time', [$startDate, $endDate]);
            $leaveRequestsQuery->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $startDate);
            });
        }

        $attendances = $attendancesQuery->get();
        $leaveRequests = $leaveRequestsQuery->get();

        $combinedData = $attendances->map(function ($item) {
            $item->event_type = 'attendance';
            $item->event_date = $item->check_in_time;
            $item->calculated_status = $item->status;
            return $item;
        });

        foreach ($leaveRequests as $leave) {
            $leaveStartCarbon = Carbon::parse($leave->start_date);
            $leaveEndCarbon = Carbon::parse($leave->end_date);
            $leaveStartDate = $startDate ? max($leaveStartCarbon, $startDate) : $leaveStartCarbon;
            $leaveEndDate = $endDate ? min($leaveEndCarbon, $endDate) : $leaveEndCarbon;

            if ($leaveStartDate->lte($leaveEndDate)) {
                $period = CarbonPeriod::create($leaveStartDate, $leaveEndDate);
                foreach ($period as $date) {
                     if (($startDate && $endDate && $date->between($startDate, $endDate)) || (!$startDate && !$endDate)) {
                        $leaveClone = clone $leave;
                        $leaveClone->event_type = 'leave';
                        $leaveClone->event_date = $date->copy()->startOfDay();
                        $leaveClone->calculated_status = ucfirst($leaveClone->type);
                        $combinedData->push($leaveClone);
                     }
                }
            }
        }

        $sortedData = $combinedData->sortByDesc('event_date');

        $perPage = 20;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage('page');
        $currentPageData = $sortedData->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $paginatedData = new \Illuminate\Pagination\LengthAwarePaginator($currentPageData, count($sortedData), $perPage, $currentPage, [
            'path' => \Illuminate\Pagination\Paginator::resolveCurrentPath(),
        ]);
        $paginatedData->appends($request->query());

        return view('admin.attendances.index', ['attendances' => $paginatedData]);
    }

    /**
     * Menangani permintaan untuk export data absensi ke Excel.
     */
    public function exportAttendances(Request $request)
    {
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
        $fileName = 'riwayat-absensi-' . Carbon::now()->format('d-m-Y') . '.xlsx';
        return Excel::download(new AttendancesExport($startDate, $endDate), $fileName);
    }

    /**
     * Menampilkan form untuk membuat absensi manual.
     */
    public function createAttendance()
    {
        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('admin.attendances.create', compact('users'));
    }

    /**
     * Menyimpan data absensi manual ke database.
     */
    public function storeAttendance(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'attendance_date' => 'required|date',
            'attendance_time' => 'required|date_format:H:i',
            'notes' => 'required|string|max:255',
        ]);

        $checkInTime = Carbon::parse($request->attendance_date . ' ' . $request->attendance_time);
        $user = User::find($request->user_id);

        if (!$user) return back()->withInput()->with('error', 'Pengguna tidak ditemukan.');

        $schedule = $user->schedules()->where('date', $checkInTime->toDateString())->first();
        $shiftId = $schedule ? $schedule->shift_id : null;

        $alreadyAttendedQuery = Attendance::where('user_id', $request->user_id)
                                    ->whereDate('check_in_time', $checkInTime->toDateString());

        if ($shiftId) $alreadyAttendedQuery->where('shift_id', $shiftId);
        $alreadyAttended = $alreadyAttendedQuery->exists();

        $isOnLeave = $user->leaveRequests()
            ->where('status', 'approved')
            ->where('start_date', '<=', $checkInTime->toDateString())
            ->where('end_date', '>=', $checkInTime->toDateString())
            ->exists();

        if ($isOnLeave) return back()->withInput()->with('error', 'Pengguna sedang izin/cuti.');
        if ($alreadyAttended && $shiftId) return back()->withInput()->with('error', 'Sudah ada absensi untuk shift ini.');

        $status = 'Manual';
        $startTimeString = null;

        if($schedule && $schedule->shift) {
            $startTimeString = $schedule->shift->start_time;
        } else {
             $workStartTimeSetting = Setting::where('key', 'work_start_time')->first();
             $startTimeString = $workStartTimeSetting ? $workStartTimeSetting->value : null;
        }

        if ($startTimeString) {
             try {
                $startTime = Carbon::parse($startTimeString);
                $deadline = $startTime->addMinutes(5);
                $status = $checkInTime->toTimeString() > $deadline->toTimeString() ? 'Terlambat' : 'Tepat Waktu';
             } catch (\Exception $e) {
                 $status = 'Manual (Error Waktu)';
             }
        }

        Attendance::create([
            'user_id' => $request->user_id,
            'shift_id' => $shiftId,
            'check_in_time' => $checkInTime,
            'notes' => $request->notes,
            'status' => $status,
        ]);

        return redirect()->route('admin.attendances.index')->with('success', 'Absensi manual berhasil.');
    }

    /**
     * Menampilkan halaman pengaturan jadwal kerja dan lokasi.
     */
    public function showSettings()
    {
        $settings = Setting::pluck('value', 'key');
        $workStartTime = $settings->get('work_start_time', '08:00');
        $officeLatitude = $settings->get('office_latitude');
        $officeLongitude = $settings->get('office_longitude');
        $officeRadius = $settings->get('office_radius', 50);

        return view('admin.settings.index', compact('workStartTime', 'officeLatitude', 'officeLongitude', 'officeRadius'));
    }

    /**
     * Menyimpan perubahan pengaturan jadwal kerja dan lokasi.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'work_start_time' => 'required|date_format:H:i',
            'office_latitude' => 'nullable|numeric',
            'office_longitude' => 'nullable|numeric',
            'office_radius' => 'nullable|numeric|min:10',
        ]);

        Setting::updateOrCreate(['key' => 'work_start_time'], ['value' => $request->work_start_time]);

        if ($request->filled('office_latitude')) 
            Setting::updateOrCreate(['key' => 'office_latitude'], ['value' => $request->office_latitude]);
        
        if ($request->filled('office_longitude')) 
            Setting::updateOrCreate(['key' => 'office_longitude'], ['value' => $request->office_longitude]);
            
        if ($request->filled('office_radius')) 
            Setting::updateOrCreate(['key' => 'office_radius'], ['value' => $request->office_radius]);

        return redirect()->route('admin.settings.show')->with('success', 'Pengaturan berhasil diperbarui.');
    }

    /**
     * Menampilkan daftar semua pengajuan izin dari pengguna.
     */
    public function listLeaveRequests()
    {
        $leaveRequests = LeaveRequest::with('user')->latest()->paginate(15);
        return view('admin.leave.index', compact('leaveRequests'));
    }

    /**
     * Memperbarui status pengajuan izin (Approve/Reject).
     */
    public function updateLeaveRequest(Request $request, LeaveRequest $leaveRequest)
    {
        $request->validate(['status' => 'required|in:approved,rejected']);
        $leaveRequest->update(['status' => $request->status, 'approved_by' => Auth::id()]);
        return redirect()->route('admin.leave.list')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
}
