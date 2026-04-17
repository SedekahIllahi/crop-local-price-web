<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RefDusun extends Model
{
    protected $table = 'ref_dusun';

    protected $fillable = [
        'id_kalurahan',
        'kode_kemendagri',
        'kode_bps',
        'nama',
    ];

    public function kalurahan(): BelongsTo
    {
        return $this->belongsTo(RefKalurahan::class, 'id_kalurahan');
    }
}
