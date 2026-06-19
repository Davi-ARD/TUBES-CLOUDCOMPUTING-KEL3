<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LpjMonitoringItem extends Model
{
    protected $fillable = [
        'lpj_monitoring_group_id', 'urutan', 'detail_kegiatan', 'pic', 'keterangan',
    ];

    public function group(): BelongsTo
    {
        return $this->belongsTo(LpjMonitoringGroup::class, 'lpj_monitoring_group_id');
    }
}
