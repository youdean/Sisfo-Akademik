<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Add the new column and unique index first so the foreign key on
        // `penilaian_id` always has an index available. Afterwards the old
        // unique index can be safely removed along with the column.
        Schema::table('nilai_tugas', function (Blueprint $table) {
            $table->string('nama');
            $table->unique(['penilaian_id', 'nama']);
            $table->dropUnique(['penilaian_id', 'nomor']);
            $table->dropColumn('nomor');
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
