<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Siswa extends Model
{
    use HasFactory;

    protected $table = 'siswa';
    protected $fillable = [
        'nama',
        'nisn',
        'nama_ortu',
        'kelas',
        'tempat_lahir',
        'jenis_kelamin',
        'tanggal_lahir',
        'user_id',
        'tahun_ajaran_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke tabel absensi.
     */
    public function absensi()
    {
        return $this->hasMany(Absensi::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
