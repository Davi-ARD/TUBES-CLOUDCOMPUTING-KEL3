<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpj_dana_keluar_divisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lpj_id')->constrained('lpjs')->cascadeOnDelete();
            $table->string('nama_divisi');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('lpj_dana_keluar_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('division_id')
                  ->constrained('lpj_dana_keluar_divisions')
                  ->cascadeOnDelete();
            $table->string('nama_kategori');
            $table->unsignedInteger('nomor');
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });

        Schema::create('lpj_dana_keluar_subitems', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')
                  ->constrained('lpj_dana_keluar_categories')
                  ->cascadeOnDelete();
            $table->string('rincian_kebutuhan');
            $table->decimal('jumlah', 15, 2)->default(0);
            $table->string('satuan')->nullable();
            $table->decimal('harga_satuan', 15, 2)->default(0);
            $table->unsignedInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpj_dana_keluar_subitems');
        Schema::dropIfExists('lpj_dana_keluar_categories');
        Schema::dropIfExists('lpj_dana_keluar_divisions');
    }
};
