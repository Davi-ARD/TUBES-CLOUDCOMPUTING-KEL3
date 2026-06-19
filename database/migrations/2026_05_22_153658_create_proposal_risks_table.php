<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposal_risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('proposal_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);
            $table->string('uraian_kegiatan');
            $table->string('identifikasi_bahaya')->nullable();
            $table->string('peluang')->nullable();
            $table->string('akibat')->nullable();
            $table->string('tingkat_risiko')->nullable();
            $table->text('pengendalian_risiko')->nullable();
            $table->string('penanggung_jawab')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('proposal_risks');
    }
};
