<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LpjDanaKeluarDivision extends Model
{
    protected $table = 'lpj_dana_keluar_divisions';

    protected $fillable = ['lpj_id', 'nama_divisi', 'urutan'];

    public function lpj(): BelongsTo
    {
        return $this->belongsTo(Lpj::class);
    }

    public function categories(): HasMany
    {
        return $this->hasMany(LpjDanaKeluarCategory::class, 'division_id')->orderBy('urutan');
    }

    public function getTotalAttribute(): float
    {
        return $this->categories->sum(fn($cat) => $cat->total);
    }
}
