<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_budgets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_id')->constrained()->cascadeOnDelete();
            $table->enum('jenis', ['dana_masuk', 'dana_keluar']);
            $table->unsignedInteger('urutan')->default(0);

            // Dana Masuk
            $table->string('sumber_dana')->nullable();
            $table->decimal('target', 15, 2)->nullable();

            // Dana Keluar
            $table->string('divisi')->nullable();
            $table->boolean('is_kategori')->default(false);
            $table->string('nama_kategori')->nullable();
            $table->string('rincian_kebutuhan')->nullable();
            $table->decimal('kuantitas', 10, 2)->nullable();
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->nullable();

            $table->decimal('jumlah_total', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_budgets');
    }
};
