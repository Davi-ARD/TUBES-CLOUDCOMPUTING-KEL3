<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_rundowns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);
            $table->time('waktu_mulai');
            $table->time('waktu_selesai');
            $table->unsignedInteger('durasi_menit')->default(0);
            $table->string('aktivitas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_rundowns');
    }
};
