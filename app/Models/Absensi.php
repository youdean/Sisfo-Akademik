<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $fillable = ['siswa_id', 'tanggal', 'status'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
