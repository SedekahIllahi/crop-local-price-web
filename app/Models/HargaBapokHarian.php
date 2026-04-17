<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HargaBapokHarian extends Model
{
    protected $table = 'harga_bapok_harian';

    protected $fillable = [
        'id_pasar',
        'tanggal',
        'data_harga',
        'status',
        'created_by',
        'verified_by',
        'is_integrated',
        'integrated_at',
        'integration_notes',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'data_harga' => 'array',
        'is_integrated' => 'boolean',
        'integrated_at' => 'datetime',
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

    public function getHarga(int $komoditasId): ?float
    {
        return $this->data_harga[$komoditasId]['harga'] ?? null;
    }

    public function getTanggalHarga(int $komoditasId): ?string
    {
        return $this->data_harga[$komoditasId]['tanggal'] ?? null;
    }

    public function setHarga(int $komoditasId, float $harga, $tanggal = null): void
    {
        $data = $this->data_harga ?? [];
        $now = $tanggal ?? now()->toDateString();

        // 🔥 NORMALISASI SEMUA DATA (backward compatible)
        foreach ($data as $key => $value) {
            if (!is_array($value)) {
                $data[$key] = [
                    'harga'   => $value,
                    'tanggal' => $this->tanggal?->toDateString() ?? $now,
                    'status'  => 'verified', // data lama dianggap verified
                ];
            } elseif (!isset($value['status'])) {
                $data[$key]['status'] = 'verified';
            }
        }

        // 🔥 UPDATE KOMODITAS YANG DIUBAH dengan status draft
        $data[$komoditasId] = [
            'harga'   => $harga,
            'tanggal' => $now,
            'status'  => 'draft',  // status per komoditas
        ];

        $this->data_harga = $data;
    }

    /**
     * Get status for specific komoditas
     */
    public function getStatusKomoditas(int $komoditasId): string
    {
        $data = $this->data_harga[$komoditasId] ?? null;
        if (is_array($data)) {
            return $data['status'] ?? 'verified';
        }
        return 'verified';
    }

    /**
     * Set all komoditas status to verified (for publish/confirm)
     */
    public function setAllStatusVerified(): void
    {
        $data = $this->data_harga ?? [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key]['status'] = 'verified';
            } else {
                // normalize old format
                $data[$key] = [
                    'harga'   => $value,
                    'tanggal' => $this->tanggal?->toDateString() ?? now()->toDateString(),
                    'status'  => 'verified',
                ];
            }
        }
        $this->data_harga = $data;
    }



    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function scopeByPasar($query, int $pasarId)
    {
        return $query->where('id_pasar', $pasarId);
    }

    public function scopeByTanggal($query, $tanggal)
    {
        return $query->whereDate('tanggal', $tanggal);
    }
}