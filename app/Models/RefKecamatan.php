<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RefKecamatan extends Model
{
    protected $table = 'ref_kecamatan';

    protected $fillable = [
        'kode_kemendagri',
        'kode_bps',
        'nama',
    ];

    public function kalurahan(): HasMany
    {
        return $this->hasMany(RefKalurahan::class, 'id_kecamatan');
    }

    public function pasar(): HasMany
    {
        return $this->hasMany(Pasar::class, 'id_kecamatan');
    }
}
