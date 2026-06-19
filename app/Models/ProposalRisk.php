<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalRisk extends Model
{
    protected $fillable = [
        'proposal_id', 'urutan', 'uraian_kegiatan', 'identifikasi_bahaya',
        'peluang', 'akibat', 'tingkat_risiko', 'pengendalian_risiko', 'penanggung_jawab',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
