<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpjRisk extends Model
{
    protected $fillable = [
        'lpj_id', 'urutan', 'uraian_kegiatan', 'identifikasi_bahaya',
        'peluang', 'akibat', 'tingkat_risiko', 'pengendalian_risiko', 'penanggung_jawab',
    ];

    public function lpj()
    {
        return $this->belongsTo(Lpj::class);
    }
}
