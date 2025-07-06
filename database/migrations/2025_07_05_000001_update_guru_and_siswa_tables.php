<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Older MariaDB versions (<10.5) do not support the "RENAME COLUMN"
        // syntax used by Laravel's renameColumn method. Instead we manually
        // issue a "CHANGE" statement to ensure compatibility.
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE guru CHANGE nip nuptk VARCHAR(255)');
        } else {
            Schema::table('guru', function (Blueprint $table) {
                $table->renameColumn('nip', 'nuptk');
            });
        }

        Schema::table('guru', function (Blueprint $table) {
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
        if (DB::getDriverName() !== 'sqlite') {
            DB::statement('ALTER TABLE guru CHANGE nuptk nip VARCHAR(255)');
        } else {
            Schema::table('guru', function (Blueprint $table) {
                $table->renameColumn('nuptk', 'nip');
            });
        }
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn(['tempat_lahir', 'jenis_kelamin']);
        });
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn(['tempat_lahir', 'jenis_kelamin']);
        });
    }
};
