<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasar;
use App\Models\Komoditas;
use App\Models\HargaBapokHarian;
use Carbon\Carbon;

class PerbandinganHargaController extends Controller
{
    /**
     * Halaman Perbandingan Harga - chart dan matriks perbandingan antar pasar
     */
    public function perbandinganHarga(Request $request)
    {
        $period = $request->get('period', 14);
        $pasarList = Pasar::active()->monitored()->get();
        $komoditasList = Komoditas::active()->get();

        // Data untuk bar chart - rata-rata semua harga komoditas per pasar (data terbaru verified)
        $barChartData = $this->getBarChartDataRataRataAllKomoditas($pasarList, $komoditasList);

        // Cari pasar termurah/termahal berdasarkan harga rata-rata
        $minIdx = null; $maxIdx = null;
        $nonZeroPrices = array_filter($barChartData['prices'], fn($p) => $p > 0);
        if (count($nonZeroPrices)) {
            $minPrice = min($nonZeroPrices);
            $maxPrice = max($nonZeroPrices);
            $minIdx = array_search($minPrice, $barChartData['prices']);
            $maxIdx = array_search($maxPrice, $barChartData['prices']);
        }
        $pasarTermurah = $barChartData['labels'][$minIdx] ?? '-';
        $pasarTermahal = $barChartData['labels'][$maxIdx] ?? '-';
        $hargaTermurah = $barChartData['prices'][$minIdx] ?? 0;
        $hargaTermahal = $barChartData['prices'][$maxIdx] ?? 0;

        // Data untuk matriks harga
        $matriksData = $this->getMatriksHarga($pasarList, $komoditasList);

        // Data untuk line chart trend
        $komoditasUtama = $komoditasList->first();
        $lineChartData = $this->getLineChartData($pasarList, $komoditasUtama?->id, $period);

        // Statistik
        $statistik = $this->getStatistik($komoditasUtama?->id);

        return view('landing.perbandingan-harga', compact(
            'pasarList',
            'komoditasList',
            'barChartData',
            'matriksData',
            'lineChartData',
            'statistik',
            'period',
            'pasarTermurah',
            'pasarTermahal',
            'hargaTermurah',
            'hargaTermahal'
        ));
    }

    // ========== HELPER METHODS ==========

    /**
     * Hitung rata-rata semua harga komoditas dalam 1 pasar
     * Data diambil dari record dengan updated_at terbaru dan status verified
     */
    private function getBarChartDataRataRataAllKomoditas($pasarList, $komoditasList)
    {
        $labels = [];
        $prices = [];

        foreach ($pasarList as $pasar) {
            $labels[] = $pasar->nama_pasar;
            
            // Ambil data terbaru berdasarkan updated_at dengan status verified
            $harga = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->where('status', 'verified')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            if ($harga && is_array($harga->data_harga)) {
                $totalHarga = 0;
                $totalCount = 0;
                
                foreach ($komoditasList as $komoditas) {
                    $entry = $harga->data_harga[$komoditas->id] ?? null;
                    $h = is_array($entry) ? ($entry['harga'] ?? 0) : ($entry ?? 0);
                    
                    if ($h > 0) {
                        $totalHarga += $h;
                        $totalCount++;
                    }
                }
                
                $prices[] = $totalCount > 0 ? round($totalHarga / $totalCount) : 0;
            } else {
                $prices[] = 0;
            }
        }

        return [
            'labels' => $labels,
            'prices' => $prices,
        ];
    }

    private function getBarChartData($komoditasId)
    {
        $pasarList = Pasar::active()->monitored()->get();
        $labels = [];
        $prices = [];

        foreach ($pasarList as $pasar) {
            $labels[] = $pasar->nama_pasar;
            
            // Ambil data terbaru berdasarkan tanggal dan updated_at dengan status verified
            $harga = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->where('status', 'verified')
                ->orderBy('tanggal', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            if ($harga) {
                $entry = $harga->data_harga[$komoditasId] ?? null;
                $prices[] = is_array($entry) ? ($entry['harga'] ?? 0) : ($entry ?? 0);
            } else {
                $prices[] = 0;
            }
        }

        return [
            'labels' => $labels,
            'prices' => $prices,
        ];
    }

    private function getMatriksHarga($pasarList, $komoditasList)
    {
        $matriks = [];

        foreach ($komoditasList as $komoditas) {
            $row = ['nama' => $komoditas->nama_komoditas, 'prices' => []];
            
            foreach ($pasarList as $pasar) {
                // Ambil data terbaru berdasarkan tanggal dan updated_at dengan status verified
                $harga = HargaBapokHarian::where('id_pasar', $pasar->id)
                    ->where('status', 'verified')
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('updated_at', 'desc')
                    ->first();
                
                if ($harga) {
                    $entry = $harga->data_harga[$komoditas->id] ?? null;
                    $price = is_array($entry) ? ($entry['harga'] ?? 0) : ($entry ?? 0);
                } else {
                    $price = 0;
                }
                $row['prices'][$pasar->id] = $price;
            }
            
            $matriks[] = $row;
        }

        return $matriks;
    }

    private function getLineChartData($pasarList, $komoditasId, $days)
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($days - 1);

        $labels = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $labels[] = $date->format('d');
        }

        $datasets = [];
        $colors = ['#059669', '#2563eb', '#dc2626', '#9333ea', '#ca8a04'];

        foreach ($pasarList as $index => $pasar) {
            $hargaData = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->where('status', 'verified')
                ->whereBetween('tanggal', [$startDate, $endDate])
                ->orderBy('tanggal')
                ->get();

            $prices = [];
            for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
                $dayData = $hargaData->firstWhere('tanggal', $date->format('Y-m-d'));
                if ($dayData) {
                    $entry = $dayData->data_harga[$komoditasId] ?? null;
                    $prices[] = is_array($entry) ? ($entry['harga'] ?? 0) : ($entry ?? 0);
                } else {
                    $prices[] = 0;
                }
            }

            $datasets[] = [
                'label' => $pasar->nama_pasar,
                'data' => $prices,
                'color' => $colors[$index] ?? '#6b7280',
            ];
        }

        return [
            'labels' => $labels,
            'datasets' => $datasets,
        ];
    }

    private function getStatistik($komoditasId)
    {
        $pasarList = Pasar::active()->monitored()->get();
        $hargaList = [];

        foreach ($pasarList as $pasar) {
            // Ambil data terbaru berdasarkan tanggal dan updated_at dengan status verified
            $harga = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->where('status', 'verified')
                ->orderBy('tanggal', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            if ($harga) {
                $entry = $harga->data_harga[$komoditasId] ?? null;
                $h = is_array($entry) ? ($entry['harga'] ?? 0) : ($entry ?? 0);
                
                if ($h > 0) {
                    $hargaList[] = [
                        'pasar' => $pasar->nama_pasar,
                        'harga' => $h,
                    ];
                }
            }
        }

        if (empty($hargaList)) {
            return [
                'tertinggi' => ['harga' => 0, 'pasar' => '-'],
                'terendah' => ['harga' => 0, 'pasar' => '-'],
                'rata_rata' => 0,
                'selisih' => 0,
            ];
        }
        
        $hargaValues = array_column($hargaList, 'harga');
        $rataRata = count($hargaValues) > 0 ? array_sum($hargaValues) / count($hargaValues) : 0;
        $sorted = collect($hargaList)->sortBy('harga')->values();
        $terendah = $sorted->first();
        $tertinggi = $sorted->last();

        return [
            'tertinggi' => $tertinggi,
            'terendah' => $terendah,
            'rata_rata' => round($rataRata),
            'selisih' => $tertinggi['harga'] - $terendah['harga'],
        ];
    }
}