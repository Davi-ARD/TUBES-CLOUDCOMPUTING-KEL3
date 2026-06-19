<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LpjAttachment extends Model
{
    protected $fillable = [
        'lpj_id', 'jenis', 'caption', 'file_path', 'file_type', 'urutan',
    ];

    public function lpj()
    {
        return $this->belongsTo(Lpj::class);
    }

    public function getAbsolutePathAttribute(): string
    {
        return storage_path('app/public/' . $this->file_path);
    }
}
