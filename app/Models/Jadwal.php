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

    /**
     * Determine the end time of a block of consecutive schedules that belong to
     * the same class, subject and teacher.
     */
    public function extendedEndTime(): string
    {
        $end = $this->jam_selesai;
        $current = $this;

        while (true) {
            $next = self::where('kelas_id', $current->kelas_id)
                ->where('mapel_id', $current->mapel_id)
                ->where('guru_id', $current->guru_id)
                ->where('hari', $current->hari)
                ->where('jam_mulai', $end)
                ->first();

            if (! $next) {
                break;
            }

            $end = $next->jam_selesai;
            $current = $next;
        }

        return $end;
    }

    /**
     * Retrieve the first schedule in a consecutive block.
     */
    public function baseSlot(): self
    {
        $current = $this;
        $start = $this->jam_mulai;

        while (true) {
            $prev = self::where('kelas_id', $current->kelas_id)
                ->where('mapel_id', $current->mapel_id)
                ->where('guru_id', $current->guru_id)
                ->where('hari', $current->hari)
                ->where('jam_selesai', $start)
                ->first();

            if (! $prev) {
                break;
            }

            $start = $prev->jam_mulai;
            $current = $prev;
        }

        return $current;
    }
}
