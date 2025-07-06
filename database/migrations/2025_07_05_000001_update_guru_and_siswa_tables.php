<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->renameColumn('nip', 'nuptk');
            $table->string('tempat_lahir')->after('nama');
            $table->string('jenis_kelamin')->after('tempat_lahir');
        });
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('tempat_lahir')->after('kelas');
            $table->string('jenis_kelamin')->after('tempat_lahir');
        });
    }

    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->renameColumn('nuptk', 'nip');
            $table->dropColumn(['tempat_lahir', 'jenis_kelamin']);
        });
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['tempat_lahir', 'jenis_kelamin']);
        });
    }
};
