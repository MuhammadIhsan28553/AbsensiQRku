<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'nik', 'no_regis', 'qr_token', 'role', 'shift_id', // Tambahkan 'shift_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all of the attendance records for the User.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get all of the leave requests for the User.
     */
    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }

    public function schedules() 
    { 
        return $this->hasMany(UserSchedule::class); 
    }

    /**
     * Generate Dynamic QR Token (Valid for 15 minutes)
     * Digunakan agar QR Code berubah setiap 15 menit.
     */
    public function getDynamicQrToken()
    {
        // 15 menit = 900 detik
        // Kita gunakan floor(time() / 900) untuk mendapatkan "blok waktu" saat ini
        $timeBlock = floor(now()->timestamp / 900);
        
        // Buat string unik: ID_USER + NIK + TIME_BLOCK
        $data = "{$this->id}|{$this->nik}|{$timeBlock}";
        
        // Hash data tersebut dengan APP_KEY agar tidak bisa dipalsukan
        $signature = hash_hmac('sha256', $data, config('app.key'));
        
        // Gabungkan data asli dan signature, lalu encode agar bersih di QR
        return base64_encode("{$data}|{$signature}");
    }
}
