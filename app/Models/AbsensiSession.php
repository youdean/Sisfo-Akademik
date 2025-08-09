<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiSession extends Model
{
    use HasFactory;

    protected $fillable = ['jadwal_id', 'tanggal', 'opened_by', 'status_sesi'];
}
