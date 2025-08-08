<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'jadwal_id',
        'tanggal',
        'password',
        'opened_at',
        'closed_at',
    ];

    public function jadwal()
    {
        return $this->belongsTo(Jadwal::class);
    }
}
