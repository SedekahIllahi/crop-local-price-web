<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasar extends Model
{
    protected $table = 'pasar';

    protected $fillable = [
        'nama_pasar',
        'id_kecamatan',
        'id_kalurahan',
        'id_dusun',
        'alamat_lengkap',
        'tipe',
        'jml_pedagang',
        'jml_kios',
        'jml_los',
        'jml_bango',
        'jml_kantor',
        'jml_mck',
        'jml_tps',
        'latitude',
        'longitude',
        'jam_buka',
        'jam_tutup',
        'is_active',
        'is_monitored',
        'wajib_pantau',
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'jam_buka' => 'string',
        'jam_tutup' => 'string',
        'is_active' => 'boolean',
        'is_monitored' => 'boolean',
        'wajib_pantau' => 'boolean',
    ];

    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(RefKecamatan::class, 'id_kecamatan');
    }

    public function kalurahan(): BelongsTo
    {
        return $this->belongsTo(RefKalurahan::class, 'id_kalurahan');
    }

    public function dusun(): BelongsTo
    {
        return $this->belongsTo(RefDusun::class, 'id_dusun');
    }

    public function hargaHarian(): HasMany
    {
        return $this->hasMany(HargaBapokHarian::class, 'id_pasar');
    }

    public function stokMingguan(): HasMany
    {
        return $this->hasMany(StokBapokMingguan::class, 'id_pasar');
    }

    public function hargaTerbaru()
    {
        return $this->hasOne(HargaBapokHarian::class, 'id_pasar')->latestOfMany('tanggal');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMonitored($query)
    {
        return $query->where('is_monitored', true);
    }
}
