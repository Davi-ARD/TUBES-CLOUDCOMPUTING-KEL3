<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpjRundown extends Model
{
    protected $fillable = [
        'lpj_id', 'urutan', 'waktu_mulai', 'waktu_selesai',
        'durasi', 'detail_kegiatan', 'pic',
    ];

    public function lpj()
    {
        return $this->belongsTo(Lpj::class);
    }
}
