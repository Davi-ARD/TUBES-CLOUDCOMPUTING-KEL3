<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LpjMonitoringGroup extends Model
{
    protected $fillable = [
        'lpj_id', 'urutan', 'tanggal', 'fase',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function lpj(): BelongsTo
    {
        return $this->belongsTo(Lpj::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(LpjMonitoringItem::class, 'lpj_monitoring_group_id')->orderBy('urutan');
    }
}
