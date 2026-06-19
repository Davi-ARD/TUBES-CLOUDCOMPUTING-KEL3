<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LpjDanaKeluarCategory extends Model
{
    protected $table = 'lpj_dana_keluar_categories';

    protected $fillable = ['division_id', 'nama_kategori', 'nomor', 'urutan'];

    public function division(): BelongsTo
    {
        return $this->belongsTo(LpjDanaKeluarDivision::class);
    }

    public function subitems(): HasMany
    {
        return $this->hasMany(LpjDanaKeluarSubitem::class, 'category_id')->orderBy('urutan');
    }

    public function getTotalAttribute(): float
    {
        return $this->subitems->sum(fn($s) => $s->jumlah * $s->harga_satuan);
    }
}
