<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';
    protected $fillable = [
        'siswa_id', 'semester',
        'hadir', 'sakit', 'izin', 'alpha',
        'tugas1', 'tugas2', 'tugas3',
        'pts', 'pat'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function getNilaiAbsensiAttribute(): float
    {
        $total = $this->hadir + $this->sakit + $this->izin + $this->alpha;
        return $total ? ($this->hadir / $total) * 100 : 0;
    }

    public function getNilaiTugasAttribute(): float
    {
        $tugas = array_filter([$this->tugas1, $this->tugas2, $this->tugas3], fn($v) => $v !== null);
        return count($tugas) ? array_sum($tugas) / count($tugas) : 0;
    }

    public function getNilaiHarianAttribute(): float
    {
        return ($this->nilai_absensi * 0.5) + ($this->nilai_tugas * 0.5);
    }

    public function getNilaiRaportAttribute(): float
    {
        return ($this->nilai_harian * 0.3) + (($this->pts ?? 0) * 0.3) + (($this->pat ?? 0) * 0.4);
    }
}
