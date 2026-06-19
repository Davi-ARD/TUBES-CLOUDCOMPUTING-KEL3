<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_rundowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);
            $table->string('waktu_mulai');
            $table->string('waktu_selesai');
            $table->string('durasi');
            $table->string('detail_kegiatan');
            $table->string('pic');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_rundowns');
    }
};
