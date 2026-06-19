<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis', ['cover_logo', 'nota', 'bukti_transfer', 'dokumentasi', 'poster', 'lainnya']);
            $table->string('caption')->nullable();
            $table->string('file_path');
            $table->string('file_type')->nullable();
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_attachments');
    }
};
