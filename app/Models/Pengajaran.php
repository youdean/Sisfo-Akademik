<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengajaran extends Model
{
    use HasFactory;

    protected $table = 'pengajaran';
    protected $fillable = ['guru_id', 'mapel_id', 'kelas'];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }
}
