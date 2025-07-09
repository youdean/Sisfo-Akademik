<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('nilai_tugas', function (Blueprint $table) {
            $table->dropUnique(['penilaian_id', 'nomor']);
            $table->string('nama');
            $table->dropColumn('nomor');
            $table->unique(['penilaian_id', 'nama']);
        });
    }

    public function down(): void
    {
        Schema::table('nilai_tugas', function (Blueprint $table) {
            $table->dropUnique(['penilaian_id', 'nama']);
            $table->unsignedSmallInteger('nomor');
            $table->unique(['penilaian_id', 'nomor']);
            $table->dropColumn('nama');
        });
    }
};
