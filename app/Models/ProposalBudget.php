<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalBudget extends Model
{
    protected $fillable = [
        'proposal_id', 'jenis', 'keterangan', 'kuantitas', 'satuan', 'harga_satuan', 'total', 'urutan',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'total'        => 'decimal:2',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
