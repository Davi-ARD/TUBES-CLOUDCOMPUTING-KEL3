<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom identifikasi_bahaya: ubah semantik dari teks bebas ke skala "X/5".
        // Kolom peluang: sudah string "X/5", biarkan.
        // Kolom tingkat_risiko: tetap string, sekarang diisi "X/5" hasil hitung otomatis.
        //
        // Reset data lama agar konsisten dengan format baru "X/5".
        // (development only — jangan dijalankan di production dengan data real)
        DB::table('proposal_risks')->update([
            'identifikasi_bahaya' => '1/5',
            'tingkat_risiko'      => '1/5',
        ]);

        // Tidak ada perubahan tipe kolom: semuanya sudah string,
        // hanya semantik yang berubah lewat form & controller.
    }

    public function down(): void
    {
        // Tidak ada perubahan DDL, rollback tidak diperlukan.
    }
};
