<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalCommittee extends Model
{
    protected $fillable = [
        'proposal_id', 'urutan', 'jabatan', 'nama', 'jurusan', 'tahun_angkatan', 'fakultas', 'nim',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
