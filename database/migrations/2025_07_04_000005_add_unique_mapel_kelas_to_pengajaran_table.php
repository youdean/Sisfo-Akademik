<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pengajaran', function (Blueprint $table) {
            $table->unique(['mapel_id', 'kelas'], 'pengajaran_mapel_kelas_unique');
        });
    }

    public function down(): void
    {
        Schema::table('pengajaran', function (Blueprint $table) {
            $table->dropUnique('pengajaran_mapel_kelas_unique');
        });
    }
};
