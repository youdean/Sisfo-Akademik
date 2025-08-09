<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Jadwal extends Model
{
    use HasFactory;

    protected $table = 'jadwal';
    protected $fillable = ['kelas_id', 'mapel_id', 'guru_id', 'hari', 'jam_mulai', 'jam_selesai'];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class, 'mapel_id');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * Merge consecutive schedule entries belonging to the same class, subject,
     * and teacher. Entries must be ordered by start time.
     *
     * @param \Illuminate\Support\Collection|\Illuminate\Database\Eloquent\Collection $jadwal
     * @return \Illuminate\Support\Collection
     */
    public static function mergeConsecutive($jadwal)
    {
        $merged = collect();
        foreach ($jadwal as $item) {
            if ($merged->isNotEmpty()) {
                $last = $merged->last();
                if (
                    $last->kelas_id === $item->kelas_id &&
                    $last->mapel_id === $item->mapel_id &&
                    $last->guru_id === $item->guru_id &&
                    $last->jam_selesai === $item->jam_mulai
                ) {
                    $last->jam_selesai = $item->jam_selesai;
                    continue;
                }
            }
            $merged->push(clone $item);
        }

        return $merged;
    }
}
