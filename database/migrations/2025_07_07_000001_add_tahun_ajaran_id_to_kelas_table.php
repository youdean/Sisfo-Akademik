<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran');
        });
    }

    public function down(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tahun_ajaran_id');
        });
    }
};
