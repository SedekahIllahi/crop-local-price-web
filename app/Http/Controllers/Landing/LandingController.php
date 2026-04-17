<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Komoditas;
use App\Models\Pasar;
use App\Models\HargaBapokHarian;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    /**
     * Halaman utama SIGAPAN - menampilkan harga harian komoditas
     */
    public function index()
    {
        // Default: Pasar Bantul
        $selectedPasar = Pasar::where('nama_pasar', 'Pasar Bantul')->first();

        // Ambil daftar komoditas aktif
        $komoditasList = Komoditas::active()->with('kategori')->get();

        // Ambil data harga untuk pasar terpilih
        $hargaHariIni = collect();
        $hargaKemarin = collect();

        if ($selectedPasar) {
            // Ambil data terbaru berdasarkan updated_at dengan status verified
            $hargaHariIni = HargaBapokHarian::where('id_pasar', $selectedPasar->id)
                ->where('status', 'verified')
                ->orderBy('updated_at', 'desc')
                ->first();

            // Ambil data sebelumnya (data verified kedua terbaru) untuk perbandingan
            $hargaKemarin = HargaBapokHarian::where('id_pasar', $selectedPasar->id)
                ->where('status', 'verified')
                ->when($hargaHariIni, function($query) use ($hargaHariIni) {
                    return $query->where('id', '!=', $hargaHariIni->id);
                })
                ->orderBy('updated_at', 'desc')
                ->first();

            // Wrap dalam collection untuk konsistensi
            $hargaHariIni = $hargaHariIni ? collect([$hargaHariIni]) : collect();
            $hargaKemarin = $hargaKemarin ? collect([$hargaKemarin]) : collect();
        }

        // Proses data untuk view: berikan angka untuk kalkulasi dan string untuk tampilan
        $komoditas = $komoditasList->map(function ($item) use ($hargaHariIni, $hargaKemarin) {
            $hargaSekarang = $this->getAveragePrice($hargaHariIni, $item->id);
            $hargaSebelum = $this->getAveragePrice($hargaKemarin, $item->id);

            $trend = 'stable';
            $persenValue = 0.0;
            if ($hargaSekarang && $hargaSebelum && $hargaSebelum > 0) {
                $perubahan = (($hargaSekarang - $hargaSebelum) / $hargaSebelum) * 100;
                if ($perubahan > 0.5) {
                    $trend = 'up';
                } elseif ($perubahan < -0.5) {
                    $trend = 'down';
                }
                $persenValue = round(abs($perubahan), 1);
            }

            $hetStatus = $this->getHetStatus($hargaSekarang, $item->harga_acuan);
            
            // Hitung selisih harga
            $selisihHarga = 0;
            if ($hargaSekarang && $hargaSebelum) {
                $selisihHarga = $hargaSekarang - $hargaSebelum;
            }

            // Handle image path - support both old format (filename only) and new format (full path)
            $imagePath = null;
            if ($item->images) {
                // If already contains path prefix, use as is
                if (str_contains($item->images, '/')) {
                    $imagePath = asset($item->images);
                } else {
                    // Old format: just filename, prepend path
                    $imagePath = asset('images/komoditas/' . $item->images);
                }
            }

            return [
                'id' => $item->id,
                'nama' => $item->nama_komoditas,
                'image' => $imagePath,
                'icon' => $item->icon,
                'satuan' => '/ ' . ucfirst($item->satuan),
                // numeric harga for calculations
                'harga' => $hargaSekarang ? round($hargaSekarang) : 0,
                'harga_raw' => $hargaSekarang,
                'harga_display' => $hargaSekarang ? 'Rp ' . number_format($hargaSekarang, 0, ',', '.') : '-',
                // Format singkat: Rp14K, Rp1.5K, Rp150
                'harga_singkat' => $this->formatHargaSingkat($hargaSekarang),
                'harga_acuan' => $item->harga_acuan,
                'trend' => $trend,
                // Selisih harga (untuk tooltip)
                'selisih_harga' => $selisihHarga,
                'selisih_display' => $this->formatSelisihHarga($selisihHarga),
                // numeric percent and display string
                'persen' => $persenValue,
                'persen_display' => $persenValue ? $persenValue . '%' : '0%',
                'het_status' => $hetStatus,
                'bgClass' => $this->getBgClass($item->kategori_id),
            ];
        })->filter(function ($item) {
            // Sembunyikan komoditas tanpa harga dari halaman publik
            return $item['harga'] > 0;
        })->values();

        // Ambil SEMUA pasar aktif untuk peta sebaran
        $semuaPasar = Pasar::where('is_active', true)
            ->select('id', 'nama_pasar', 'alamat_lengkap', 'latitude', 'longitude', 'is_monitored', 'wajib_pantau', 'tipe')
            ->get();

        // Hitung pasar pantauan untuk statistik
        $jumlahPasarPantauan = $semuaPasar->filter(function($p) {
            return $p->is_monitored || $p->wajib_pantau;
        })->count();

        return view('landing.home', compact('komoditas', 'selectedPasar', 'semuaPasar', 'jumlahPasarPantauan'));
    }



    /**
     * Halaman detail profil pasar
     */
    public function pasarDetail($id)
    {
        $pasar = Pasar::with(['kecamatan', 'kalurahan', 'dusun', 'hargaTerbaru'])->findOrFail($id);
        $komoditasList = Komoditas::active()->pluck('nama_komoditas', 'id');

        return view('landing.pasar-detail', compact('pasar', 'komoditasList'));
    }

    // ========== HELPER METHODS ==========

    private function getAveragePrice($hargaCollection, $komoditasId)
    {
        $prices = $hargaCollection->map(function ($h) use ($komoditasId) {
            $entry = $h->data_harga[$komoditasId] ?? null;
            if (is_array($entry)) {
                return $entry['harga'] ?? null;
            }
            return $entry;
        })->filter()->values();

        return $prices->isNotEmpty() ? $prices->avg() : null;
    }

    private function getHetStatus($harga, $het)
    {
        if (!$harga || !$het) return 'unknown';
        
        $ratio = $harga / $het;
        if ($ratio <= 1) return 'normal';
        if ($ratio <= 1.1) return 'tinggi';
        return 'sangat_tinggi';
    }

    private function getBgClass($kategoriId)
    {
        $classes = [
            1 => 'bg-emerald-50',
            2 => 'bg-green-50',
            3 => 'bg-red-50',
            4 => 'bg-orange-50',
            5 => 'bg-yellow-50',
        ];
        return $classes[$kategoriId] ?? 'bg-gray-50';
    }

    /**
     * Format harga menjadi format singkat: Rp14K, Rp1.5K, Rp150
     */
    private function formatHargaSingkat($harga)
    {
        if (!$harga || $harga == 0) return '-';
        
        $harga = round($harga);
        
        if ($harga >= 1000000) {
            // Jutaan: Rp1.5M
            $formatted = $harga / 1000000;
            if ($formatted == floor($formatted)) {
                return 'Rp' . number_format($formatted, 0) . 'M';
            }
            return 'Rp' . number_format($formatted, 1, '.', '') . 'M';
        } elseif ($harga >= 1000) {
            // Ribuan: Rp14K, Rp1.5K
            $formatted = $harga / 1000;
            if ($formatted == floor($formatted)) {
                return 'Rp' . number_format($formatted, 0) . 'K';
            }
            return 'Rp' . number_format($formatted, 1, '.', '') . 'K';
        } else {
            // Di bawah 1000: Rp150
            return 'Rp' . number_format($harga, 0);
        }
    }

    /**
     * Format selisih harga untuk tooltip
     */
    private function formatSelisihHarga($selisih)
    {
        if ($selisih == 0) return 'Rp0';
        
        $prefix = $selisih > 0 ? '+' : '';
        $absSelisih = abs($selisih);
        
        if ($absSelisih >= 1000) {
            return $prefix . 'Rp' . number_format($selisih, 0, ',', '.');
        }
        return $prefix . 'Rp' . number_format($selisih, 0, ',', '.');
    }
}