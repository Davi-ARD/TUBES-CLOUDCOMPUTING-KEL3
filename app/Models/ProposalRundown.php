<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalRundown extends Model
{
    protected $fillable = [
        'proposal_id', 'urutan', 'waktu_mulai', 'waktu_selesai', 'durasi_menit', 'aktivitas',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
