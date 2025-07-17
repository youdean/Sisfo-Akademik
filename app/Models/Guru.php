<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $fillable = [
        'nuptk',
        'nama',
        'email',
        'tempat_lahir',
        'jenis_kelamin',
        'tanggal_lahir',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
