<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpjBudget extends Model
{
    protected $fillable = [
        'lpj_id', 'jenis', 'urutan',
        'sumber_dana', 'target',
        'divisi', 'is_kategori', 'nama_kategori',
        'rincian_kebutuhan', 'kuantitas', 'satuan', 'harga_satuan',
        'jumlah_total',
    ];

    protected $casts = [
        'is_kategori'  => 'boolean',
        'target'       => 'float',
        'kuantitas'    => 'float',
        'harga_satuan' => 'float',
        'jumlah_total' => 'float',
    ];

    public function lpj()
    {
        return $this->belongsTo(Lpj::class);
    }
}
