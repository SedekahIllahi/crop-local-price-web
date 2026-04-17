<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefKalurahan extends Model
{
    protected $table = 'ref_kalurahan';

    protected $fillable = [
        'id_kecamatan',
        'kode_kemendagri',
        'kode_bps',
        'nama',
    ];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(RefKecamatan::class, 'id_kecamatan');
    }

    public function dusun(): HasMany
    {
        return $this->hasMany(RefDusun::class, 'id_kalurahan');
    }

    public function pasar(): HasMany
    {
        return $this->hasMany(Pasar::class, 'id_kalurahan');
    }
}
