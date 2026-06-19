<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProposalAttachment extends Model
{
    protected $fillable = [
        'proposal_id', 'jenis', 'caption', 'file_path', 'file_type', 'urutan',
    ];

    public function proposal(): BelongsTo
    {
        return $this->belongsTo(Proposal::class);
    }
}
