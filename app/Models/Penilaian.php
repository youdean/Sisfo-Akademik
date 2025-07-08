<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\MataPelajaran;
use App\Models\NilaiTugas;

class Penilaian extends Model
{
    use HasFactory;

    protected $table = 'penilaian';
    protected $fillable = [
        'siswa_id', 'mapel_id', 'semester',
        'hadir', 'sakit', 'izin', 'alpha',
        'pts', 'pat'
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function tugas()
    {
        return $this->hasMany(NilaiTugas::class);
    }

    public function getNilaiAbsensiAttribute(): float
    {
        $total = $this->hadir + $this->sakit + $this->izin + $this->alpha;
        return $total ? ($this->hadir / $total) * 100 : 0;
    }

    public function getNilaiTugasAttribute(): float
    {
        $nilai = $this->tugas->pluck('nilai')->filter();
        return $nilai->count() ? $nilai->avg() : 0;
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
