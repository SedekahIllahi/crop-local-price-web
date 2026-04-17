<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KategoriKomoditas extends Model
{
    protected $table = 'kategori_komoditas';

    protected $fillable = [
        'nama_kategori',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function komoditas(): HasMany
    {
        return $this->hasMany(Komoditas::class, 'kategori_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
