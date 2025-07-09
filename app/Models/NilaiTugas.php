<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NilaiTugas extends Model
{
    use HasFactory;

    protected $table = 'nilai_tugas';
    protected $fillable = ['penilaian_id', 'nama', 'nilai'];

    public function penilaian()
    {
        return $this->belongsTo(Penilaian::class);
    }
}
