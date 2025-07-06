<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Guru;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    protected $fillable = ['nama', 'guru_id'];

    public function waliKelas()
    {
        return $this->belongsTo(Guru::class, 'guru_id');
    }
}
