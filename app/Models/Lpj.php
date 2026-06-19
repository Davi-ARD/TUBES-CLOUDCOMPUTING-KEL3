<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lpj extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nama_kegiatan', 'akronim', 'tema_kegiatan',
        'tanggal_mulai', 'tanggal_selesai', 'waktu_mulai', 'waktu_selesai',
        'tempat_kegiatan', 'kota', 'tahun', 'penyelenggara', 'afiliasi',
        'latar_belakang', 'tujuan_kegiatan', 'sasaran_kegiatan',
        'bentuk_kegiatan', 'deskripsi_pelaksanaan', 'simpulan_rekomendasi', 'penutup',
        'ketua_pelaksana_nama', 'ketua_pelaksana_nim',
        'ketua_ukm_nama', 'ketua_ukm_nim',
        'pembina_1_nama', 'pembina_1_nip',
        'pembina_2_nama', 'pembina_2_nip',
        'direktur_nama', 'direktur_nip',
        'logo_organisasi_path', 'status', 'generated_at',
    ];

    protected $casts = [
        'tujuan_kegiatan' => 'array',
        'tanggal_mulai'   => 'date',
        'tanggal_selesai' => 'date',
        'generated_at'    => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function rundowns()
    {
        return $this->hasMany(LpjRundown::class)->orderBy('urutan');
    }

    public function risks()
    {
        return $this->hasMany(LpjRisk::class)->orderBy('urutan');
    }

    public function monitoringGroups()
    {
        return $this->hasMany(LpjMonitoringGroup::class)->orderBy('urutan');
    }

    public function committees()
    {
        return $this->hasMany(LpjCommittee::class)->orderBy('urutan');
    }

    public function budgets()
    {
        return $this->hasMany(LpjBudget::class)->orderBy('urutan');
    }

    public function attachments()
    {
        return $this->hasMany(LpjAttachment::class)->orderBy('urutan');
    }

    public function logoAttachment()
    {
        return $this->attachments()->where('jenis', 'cover_logo')->first();
    }

    public function lampiranAttachments()
    {
        // 'lampiran' = berkas PDF gabungan baru (item #7); sisanya data lama.
        return $this->attachments()->whereIn('jenis', ['lampiran', 'nota', 'bukti_transfer', 'dokumentasi', 'poster', 'lainnya']);
    }

    /** Logo UKM/organisasi kolaborator yang tampil di cover (item #4). */
    public function collabLogos()
    {
        return $this->hasMany(LpjAttachment::class)
            ->where('jenis', 'collab_logo')->orderBy('urutan');
    }

    public function danaMasuk()
    {
        return $this->budgets()->where('jenis', 'dana_masuk');
    }

    public function danaKeluar()
    {
        return $this->budgets()->where('jenis', 'dana_keluar');
    }

    public function danaKeluarDivisions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LpjDanaKeluarDivision::class)->orderBy('urutan');
    }

    public function getTanggalPelaksanaanAttribute(): string
    {
        $mulai = $this->tanggal_mulai;
        $selesai = $this->tanggal_selesai;

        if (!$mulai) return '-';

        $fmt = fn ($d) => $d->locale('id')->translatedFormat('j F Y');

        if (!$selesai || $mulai->equalTo($selesai)) {
            return $fmt($mulai);
        }

        if ($mulai->month === $selesai->month && $mulai->year === $selesai->year) {
            return $mulai->format('j') . '–' . $selesai->locale('id')->translatedFormat('j F Y');
        }

        return $fmt($mulai) . ' – ' . $fmt($selesai);
    }
}
