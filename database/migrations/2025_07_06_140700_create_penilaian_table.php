<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->unsignedTinyInteger('semester')->default(1);
            $table->unsignedSmallInteger('hadir')->default(0);
            $table->unsignedSmallInteger('sakit')->default(0);
            $table->unsignedSmallInteger('izin')->default(0);
            $table->unsignedSmallInteger('alpha')->default(0);
            $table->unsignedTinyInteger('tugas1')->nullable();
            $table->unsignedTinyInteger('tugas2')->nullable();
            $table->unsignedTinyInteger('tugas3')->nullable();
            $table->unsignedTinyInteger('pts')->nullable();
            $table->unsignedTinyInteger('pat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
