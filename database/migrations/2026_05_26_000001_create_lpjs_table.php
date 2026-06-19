<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lpjs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('nama_kegiatan');
            $table->string('akronim')->nullable();
            $table->string('tema_kegiatan')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('tempat_kegiatan');
            $table->string('kota')->default('BANDUNG');
            $table->year('tahun');
            $table->string('penyelenggara');
            $table->string('afiliasi')->nullable();

            $table->text('latar_belakang');
            $table->json('tujuan_kegiatan');
            $table->text('sasaran_kegiatan');
            $table->text('bentuk_kegiatan');
            $table->text('deskripsi_pelaksanaan');
            $table->text('simpulan_rekomendasi');
            $table->text('penutup');

            $table->string('ketua_pelaksana_nama');
            $table->string('ketua_pelaksana_nim');
            $table->string('ketua_ukm_nama');
            $table->string('ketua_ukm_nim');
            $table->string('pembina_1_nama');
            $table->string('pembina_1_nip');
            $table->string('pembina_2_nama')->nullable();
            $table->string('pembina_2_nip')->nullable();
            $table->string('direktur_nama')->default('Dr. Maulana Rezi Ramadhana, S.Psi., M.Psi., Psikolog');
            $table->string('direktur_nip')->default('20820005');

            $table->string('logo_organisasi_path')->nullable();
            $table->enum('status', ['draft', 'generated'])->default('draft');
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lpjs');
    }
};
