<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'shift_id',
        'date',
    ];

    // Definisikan relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Definisikan relasi ke model Shift
    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
