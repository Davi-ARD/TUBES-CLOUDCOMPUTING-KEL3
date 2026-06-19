<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpjCommittee extends Model
{
    protected $fillable = [
        'lpj_id', 'urutan', 'jabatan', 'nama',
        'nim', 'jurusan', 'fakultas', 'angkatan',
    ];

    public function lpj()
    {
        return $this->belongsTo(Lpj::class);
    }
}
