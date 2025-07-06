<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->foreignId('mapel_id')->nullable()->after('siswa_id')->constrained('mata_pelajaran');
        });
    }

    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropConstrainedForeignId('mapel_id');
        });
    }
};
