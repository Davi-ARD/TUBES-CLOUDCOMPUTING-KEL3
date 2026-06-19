<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Mengubah kolom `jenis` pada tabel lampiran dari ENUM menjadi VARCHAR.
 *
 * Revisi-v0.1 menambah dua jenis lampiran baru:
 *   - 'collab_logo' : logo UKM/organisasi kolaborator (cover, item #4).
 *   - 'lampiran'    : satu berkas PDF lampiran gabungan (item #7).
 * ENUM lama tidak memuat nilai ini, dan mengubah ENUM rawan; string lebih luwes.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('proposal_attachments', function (Blueprint $table) {
            $table->string('jenis')->default('lampiran')->change();
        });

        Schema::table('lpj_attachments', function (Blueprint $table) {
            $table->string('jenis')->default('lampiran')->change();
        });
    }

    public function down(): void
    {
        Schema::table('proposal_attachments', function (Blueprint $table) {
            $table->enum('jenis', ['cover_logo', 'lampiran'])->default('lampiran')->change();
        });

        Schema::table('lpj_attachments', function (Blueprint $table) {
            $table->enum('jenis', ['cover_logo', 'nota', 'bukti_transfer', 'dokumentasi', 'poster', 'lainnya'])->change();
        });
    }
};
