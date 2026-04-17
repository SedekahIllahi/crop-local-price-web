<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Komoditas extends Model
{
    protected $table = 'komoditas';

    protected $fillable = [
        'nama_komoditas',
        'satuan',
        'harga_acuan',
        'kategori_id',
        'icon',
        'images',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'harga_acuan' => 'decimal:2',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriKomoditas::class, 'kategori_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
