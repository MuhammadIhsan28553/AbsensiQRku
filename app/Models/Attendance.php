<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;
use App\Models\Setting;

class Attendance extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'check_in_time',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'check_in_time' => 'datetime',
    ];

    /**
     * Get the user that owns the attendance record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Accessor untuk menentukan status absensi (Tepat Waktu atau Telat).
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                // Ambil jam masuk dari database, default '08:00' jika tidak ada
                $workStartTimeSetting = Setting::where('key', 'work_start_time')->first();
                $workStartTime = $workStartTimeSetting ? $workStartTimeSetting->value : '08:00';

                // Pisahkan jam dan menit
                list($hour, $minute) = explode(':', $workStartTime);

                // Ubah tipe data dari string ke integer
                $hour = (int) $hour;
                $minute = (int) $minute;

                // Ambil waktu absen
                $checkInTime = Carbon::parse($attributes['check_in_time']);

                // Tentukan batas waktu dari pengaturan
                $deadline = $checkInTime->copy()->setHour($hour)->setMinute($minute)->setSecond(0);

                // Bandingkan dan kembalikan statusnya
                return $checkInTime->isAfter($deadline) ? 'Telat' : 'Tepat Waktu';
            },
        );
    }
    public function shift() { return $this->belongsTo(Shift::class); }
    
}
