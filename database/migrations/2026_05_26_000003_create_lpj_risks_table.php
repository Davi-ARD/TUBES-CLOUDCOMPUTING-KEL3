<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_risks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('urutan')->default(0);
            $table->string('uraian_kegiatan');
            $table->text('identifikasi_bahaya');
            $table->string('peluang');
            $table->text('akibat');
            $table->string('tingkat_risiko');
            $table->text('pengendalian_risiko');
            $table->string('penanggung_jawab');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_risks');
    }
};
