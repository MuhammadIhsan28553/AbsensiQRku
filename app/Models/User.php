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
     * TAMBAHKAN DUA METHOD DI BAWAH INI
     */

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

    public function schedules() { return $this->hasMany(UserSchedule::class); }
}
