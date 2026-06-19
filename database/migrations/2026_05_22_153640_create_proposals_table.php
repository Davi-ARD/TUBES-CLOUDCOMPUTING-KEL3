<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('nama_kegiatan');
            $table->string('tema_kegiatan')->nullable();
            $table->string('penyelenggara')->nullable();
            $table->string('afiliasi')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('waktu_mulai')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->string('tempat_kegiatan')->nullable();
            $table->string('kota')->default('BANDUNG');
            $table->year('tahun');

            $table->decimal('total_anggaran', 15, 2)->default(0);
            $table->decimal('pengajuan_dana', 15, 2)->default(0);

            $table->text('latar_belakang')->nullable();
            $table->json('tujuan_kegiatan')->nullable();
            $table->text('sasaran_kegiatan')->nullable();
            $table->text('bentuk_kegiatan')->nullable();
            $table->json('materi_kegiatan')->nullable();
            $table->text('narasumber_kegiatan')->nullable();
            $table->text('monitoring_evaluasi')->nullable();
            $table->text('penutup')->nullable();

            $table->string('president_ukm_nama')->nullable();
            $table->string('president_ukm_nim')->nullable();
            $table->string('sekretaris_nama')->nullable();
            $table->string('sekretaris_nim')->nullable();
            $table->string('ketua_pelaksana_nama')->nullable();
            $table->string('ketua_pelaksana_nim')->nullable();
            $table->string('pembina_nama')->nullable();
            $table->string('pembina_nip')->nullable();
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
        Schema::dropIfExists('proposals');
    }
};
