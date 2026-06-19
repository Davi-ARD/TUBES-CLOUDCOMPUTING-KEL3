<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proposal extends Model
{
    protected $fillable = [
        'user_id', 'nama_kegiatan', 'tema_kegiatan', 'penyelenggara', 'afiliasi',
        'tanggal_mulai', 'tanggal_selesai', 'waktu_mulai', 'waktu_selesai',
        'tempat_kegiatan', 'kota', 'tahun', 'total_anggaran', 'pengajuan_dana',
        'latar_belakang', 'tujuan_kegiatan', 'sasaran_kegiatan', 'bentuk_kegiatan',
        'materi_kegiatan', 'narasumber_kegiatan', 'monitoring_evaluasi', 'penutup',
        'president_ukm_nama', 'president_ukm_nim', 'sekretaris_nama', 'sekretaris_nim',
        'ketua_pelaksana_nama', 'ketua_pelaksana_nim', 'pembina_nama', 'pembina_nip',
        'pembina_2_nama', 'pembina_2_nip',
        'direktur_nama', 'direktur_nip', 'logo_organisasi_path', 'status', 'generated_at',
    ];

    protected $casts = [
        'tujuan_kegiatan' => 'array',
        'materi_kegiatan' => 'array',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'generated_at'    => 'datetime',
        'total_anggaran'  => 'decimal:2',
        'pengajuan_dana'  => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rundowns(): HasMany
    {
        return $this->hasMany(ProposalRundown::class)->orderBy('urutan');
    }

    public function risks(): HasMany
    {
        return $this->hasMany(ProposalRisk::class)->orderBy('urutan');
    }

    public function committees(): HasMany
    {
        return $this->hasMany(ProposalCommittee::class)->orderBy('urutan');
    }

    public function budgets(): HasMany
    {
        return $this->hasMany(ProposalBudget::class)->orderBy('urutan');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ProposalAttachment::class)->orderBy('urutan');
    }

    public function lampiranAttachments(): HasMany
    {
        return $this->hasMany(ProposalAttachment::class)
            ->where('jenis', 'lampiran')->orderBy('urutan');
    }

    /** Logo UKM/organisasi kolaborator yang tampil di cover (item #4). */
    public function collabLogos(): HasMany
    {
        return $this->hasMany(ProposalAttachment::class)
            ->where('jenis', 'collab_logo')->orderBy('urutan');
    }

    public function getTanggalPelaksanaanAttribute(): string
    {
        $bulanId = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];
        $mulai   = $this->tanggal_mulai;
        $selesai = $this->tanggal_selesai;

        if ($mulai->format('m') === $selesai->format('m') && $mulai->format('Y') === $selesai->format('Y')) {
            return $mulai->format('d') . ' – ' . $selesai->format('d') . ' ' .
                   $bulanId[(int) $mulai->format('m')] . ' ' . $mulai->format('Y');
        }

        return $mulai->format('d') . ' ' . $bulanId[(int) $mulai->format('m')] . ' – ' .
               $selesai->format('d') . ' ' . $bulanId[(int) $selesai->format('m')] . ' ' . $selesai->format('Y');
    }
}
