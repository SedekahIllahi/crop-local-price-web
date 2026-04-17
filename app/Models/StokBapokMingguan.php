<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StokBapokMingguan extends Model
{
    protected $table = 'stok_bapok_mingguan';

    protected $fillable = [
        'id_pasar',
        'tanggal_pendataan',
        'minggu_ke',
        'bulan',
        'tahun',
        'data_stok',
        'status',
        'created_by',
        'verified_by',
    ];

    protected $casts = [
        'tanggal_pendataan' => 'date',
        'data_stok' => 'array',
    ];

    public function pasar(): BelongsTo
    {
        return $this->belongsTo(Pasar::class, 'id_pasar');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Get stok for specific komoditas
     */
    public function getStok(int $komoditasId): ?float
    {
        $value = $this->data_stok[$komoditasId] ?? $this->data_stok[(string)$komoditasId] ?? null;
        
        if (is_null($value)) {
            return null;
        }
        
        // Handle array format (dengan key jumlah_stok) atau scalar value
        if (is_array($value)) {
            return isset($value['jumlah_stok']) ? (float)$value['jumlah_stok'] : null;
        }
        
        return (float)$value;
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeByMinggu($query, int $minggu, int $bulan, int $tahun)
    {
        return $query->where('minggu_ke', $minggu)
                     ->where('bulan', $bulan)
                     ->where('tahun', $tahun);
    }
}
