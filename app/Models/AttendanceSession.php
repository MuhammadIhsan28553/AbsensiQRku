<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'created_by',
        'expires_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    // TAMBAHKAN PROPERTI INI
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
