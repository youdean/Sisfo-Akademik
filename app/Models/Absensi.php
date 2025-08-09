<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MataPelajaran;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $fillable = ['siswa_id', 'mapel_id', 'tanggal', 'status', 'check_in_at', 'check_out_at'];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}
