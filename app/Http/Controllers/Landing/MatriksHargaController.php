<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasar;
use App\Models\Komoditas;
use App\Models\HargaBapokHarian;

class MatriksHargaController extends Controller
{
    /**
     * Halaman Matriks Harga - tabel heatmap harga per komoditas
     */
    public function matriksHarga()
    {
        // Ambil 5 pasar yang dipantau
        $pasarList = Pasar::active()->monitored()->orderBy('id')->limit(5)->get();
        $komoditasList = Komoditas::active()->get();
        $matriksData = $this->getMatriksHarga($pasarList, $komoditasList);
        
        // Hitung rata-rata semua komoditas per pasar
        $rataRataPerPasar = $this->getRataRataPerPasar($pasarList, $komoditasList);
        
        // Identifikasi statistik
        $statistik = $this->getStatistik($rataRataPerPasar);
        
        return view('landing.matriks-harga', compact(
            'pasarList', 
            'komoditasList', 
            'matriksData',
            'rataRataPerPasar',
            'statistik'
        ));
    }

    // ========== HELPER METHODS ==========

    /**
     * Hitung rata-rata semua harga komoditas dalam satu pasar
     */
    private function getRataRataPerPasar($pasarList, $komoditasList)
    {
        $rataRata = [];

        foreach ($pasarList as $pasar) {
            // Ambil data terbaru berdasarkan tanggal dan updated_at dengan status verified
            $hargaTerbaru = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->where('status', 'verified')
                ->orderBy('tanggal', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            // Ambil data sebelumnya untuk perbandingan trend
            $hargaSebelumnya = null;
            if ($hargaTerbaru) {
                $hargaSebelumnya = HargaBapokHarian::where('id_pasar', $pasar->id)
                    ->where('status', 'verified')
                    ->where('id', '!=', $hargaTerbaru->id)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->first();
            }
            
            $totalHarga = 0;
            $totalCount = 0;
            $totalHargaLama = 0;
            $totalCountLama = 0;
            
            foreach ($komoditasList as $komoditas) {
                // Harga sekarang
                if ($hargaTerbaru && is_array($hargaTerbaru->data_harga)) {
                    $entry = $hargaTerbaru->data_harga[$komoditas->id] 
                          ?? $hargaTerbaru->data_harga[(string)$komoditas->id] 
                          ?? null;
                    $h = (float)(is_array($entry) ? ($entry['harga'] ?? 0) : ($entry ?? 0));
                    if ($h > 0) {
                        $totalHarga += $h;
                        $totalCount++;
                    }
                }
                
                // Harga sebelumnya
                if ($hargaSebelumnya && is_array($hargaSebelumnya->data_harga)) {
                    $entryLama = $hargaSebelumnya->data_harga[$komoditas->id] 
                              ?? $hargaSebelumnya->data_harga[(string)$komoditas->id] 
                              ?? null;
                    $hLama = (float)(is_array($entryLama) ? ($entryLama['harga'] ?? 0) : ($entryLama ?? 0));
                    if ($hLama > 0) {
                        $totalHargaLama += $hLama;
                        $totalCountLama++;
                    }
                }
            }
            
            $avgSekarang = $totalCount > 0 ? round($totalHarga / $totalCount) : 0;
            $avgLama = $totalCountLama > 0 ? round($totalHargaLama / $totalCountLama) : 0;
            
            // Tentukan trend
            $trend = 'stabil';
            $selisih = 0;
            if ($avgSekarang > 0 && $avgLama > 0) {
                $selisih = $avgSekarang - $avgLama;
                $persentase = (($avgSekarang - $avgLama) / $avgLama) * 100;
                if ($persentase > 0.5) {
                    $trend = 'naik';
                } elseif ($persentase < -0.5) {
                    $trend = 'turun';
                }
            }
            
            $rataRata[$pasar->id] = [
                'nama_pasar' => $pasar->nama_pasar,
                'rata_rata' => $avgSekarang,
                'rata_rata_lama' => $avgLama,
                'trend' => $trend,
                'selisih' => $selisih,
            ];
        }

        return $rataRata;
    }

    /**
     * Identifikasi statistik: tertinggi, terendah, rata-rata, selisih
     */
    private function getStatistik($rataRataPerPasar)
    {
        $data = collect($rataRataPerPasar)->filter(fn($item) => $item['rata_rata'] > 0);
        
        if ($data->isEmpty()) {
            return [
                'tertinggi' => ['nama_pasar' => '-', 'rata_rata' => 0],
                'terendah' => ['nama_pasar' => '-', 'rata_rata' => 0],
                'rata_rata_semua' => 0,
                'selisih' => 0,
            ];
        }
        
        $sorted = $data->sortBy('rata_rata')->values();
        $terendah = $sorted->first();
        $tertinggi = $sorted->last();
        $rataSemua = round($data->avg('rata_rata'));
        $selisih = $tertinggi['rata_rata'] - $terendah['rata_rata'];
        
        return [
            'tertinggi' => $tertinggi,
            'terendah' => $terendah,
            'rata_rata_semua' => $rataSemua,
            'selisih' => $selisih,
        ];
    }

    private function getMatriksHarga($pasarList, $komoditasList)
    {
        $matriks = [];

        // Cache harga terbaru dan sebelumnya untuk setiap pasar
        $hargaTerbaruPerPasar = [];
        $hargaSebelumnyaPerPasar = [];

        foreach ($pasarList as $pasar) {
            // Ambil data terbaru berdasarkan tanggal dan updated_at dengan status verified
            $hargaTerbaru = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->where('status', 'verified')
                ->orderBy('tanggal', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            $hargaTerbaruPerPasar[$pasar->id] = $hargaTerbaru;

            // Ambil data sebelumnya (verified kedua terbaru) untuk perbandingan
            if ($hargaTerbaru) {
                $hargaSebelumnya = HargaBapokHarian::where('id_pasar', $pasar->id)
                    ->where('status', 'verified')
                    ->where('id', '!=', $hargaTerbaru->id)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->first();
                $hargaSebelumnyaPerPasar[$pasar->id] = $hargaSebelumnya;
            } else {
                $hargaSebelumnyaPerPasar[$pasar->id] = null;
            }
        }

        foreach ($komoditasList as $komoditas) {
            $row = [
                'id' => $komoditas->id,
                'nama' => $komoditas->nama_komoditas,
                'icon' => $komoditas->icon,
                'satuan' => $komoditas->satuan,
                'prices' => []
            ];
            
            foreach ($pasarList as $pasar) {
                $hargaTerbaru = $hargaTerbaruPerPasar[$pasar->id];
                $hargaSebelumnya = $hargaSebelumnyaPerPasar[$pasar->id];
                
                // Ambil harga saat ini
                $hargaSekarang = 0;
                if ($hargaTerbaru && is_array($hargaTerbaru->data_harga)) {
                    $entry = $hargaTerbaru->data_harga[$komoditas->id] 
                          ?? $hargaTerbaru->data_harga[(string)$komoditas->id] 
                          ?? null;
                    $hargaSekarang = (float)(is_array($entry) ? ($entry['harga'] ?? 0) : ($entry ?? 0));
                }
                
                // Ambil harga sebelumnya
                $hargaLama = 0;
                if ($hargaSebelumnya && is_array($hargaSebelumnya->data_harga)) {
                    $entryLama = $hargaSebelumnya->data_harga[$komoditas->id] 
                              ?? $hargaSebelumnya->data_harga[(string)$komoditas->id] 
                              ?? null;
                    $hargaLama = (float)(is_array($entryLama) ? ($entryLama['harga'] ?? 0) : ($entryLama ?? 0));
                }
                
                // Tentukan trend
                $trend = 'stabil';
                $selisih = 0;
                if ($hargaSekarang > 0 && $hargaLama > 0) {
                    $selisih = $hargaSekarang - $hargaLama;
                    $persentase = (($hargaSekarang - $hargaLama) / $hargaLama) * 100;
                    if ($persentase > 0.5) {
                        $trend = 'naik';
                    } elseif ($persentase < -0.5) {
                        $trend = 'turun';
                    }
                }
                
                $row['prices'][$pasar->id] = [
                    'harga' => $hargaSekarang,
                    'harga_lama' => $hargaLama,
                    'trend' => $trend,
                    'selisih' => $selisih,
                ];
            }
            
            $matriks[] = $row;
        }

        return $matriks;
    }
}