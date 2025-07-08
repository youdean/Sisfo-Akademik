<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->foreignId('mapel_id')->after('siswa_id')->constrained('mata_pelajaran');
            $table->dropColumn(['tugas1', 'tugas2', 'tugas3']);
        });

        Schema::create('nilai_tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penilaian_id')->constrained('penilaian')->onDelete('cascade');
            $table->unsignedSmallInteger('nomor');
            $table->unsignedTinyInteger('nilai')->nullable();
            $table->timestamps();
            $table->unique(['penilaian_id', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_tugas');

        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropConstrainedForeignId('mapel_id');
            $table->unsignedTinyInteger('tugas1')->nullable();
            $table->unsignedTinyInteger('tugas2')->nullable();
            $table->unsignedTinyInteger('tugas3')->nullable();
        });
    }
};
