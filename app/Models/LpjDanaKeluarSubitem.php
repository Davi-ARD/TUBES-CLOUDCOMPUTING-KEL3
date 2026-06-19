<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LpjDanaKeluarSubitem extends Model
{
    protected $table = 'lpj_dana_keluar_subitems';

    protected $fillable = [
        'category_id',
        'rincian_kebutuhan',
        'jumlah',
        'satuan',
        'harga_satuan',
        'urutan',
    ];

    protected $casts = [
        'jumlah'       => 'float',
        'harga_satuan' => 'float',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(LpjDanaKeluarCategory::class);
    }

    public function getJumlahHargaAttribute(): float
    {
        return $this->jumlah * $this->harga_satuan;
    }
}
