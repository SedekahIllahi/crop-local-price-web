<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasar;
use App\Models\Komoditas;
use App\Models\HargaBapokHarian;
use Carbon\Carbon;

class TrendHargaController extends Controller
{
    
    /**
     * Halaman Trend Harga - chart trend harga komoditas
     */
    public function trendHarga()
    {
        // Ambil 5 pasar yang dipantau
        $pasarList = Pasar::active()->monitored()->limit(5)->get();
        $komoditasList = Komoditas::active()->get();
        
        return view('landing.trend-harga', compact('pasarList', 'komoditasList'));
    }

    /**
     * API: Data chart untuk AJAX requests
     * Menghitung rata-rata SEMUA komoditas per hari dalam satu pasar
     */
    public function chartData(Request $request)
    {
        $pasarId = $request->get('pasar_id');
        $period = $request->get('period', 7);

        $data = $this->getChartDataRataRataAllKomoditas($pasarId, $period);

        // Statistik rata-rata semua komoditas
        $statistik = $this->getStatistikRataRataAllKomoditas();

        return response()->json(array_merge($data, [
            'statistik' => $statistik
        ]));
    }

    // ========== HELPER METHODS ==========

    /**
     * Get chart data - rata-rata SEMUA komoditas per hari dalam satu pasar
     * Menampilkan data untuk X hari terakhir dari hari ini
     */
    private function getChartDataRataRataAllKomoditas($pasarId, $days)
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->subDays($days - 1);
        
        // Ambil semua data verified dalam rentang tanggal
        $hargaList = HargaBapokHarian::where('id_pasar', $pasarId)
            ->where('status', 'verified')
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->keyBy(function($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });

        // Ambil juga data terakhir sebelum startDate untuk mengisi gap di awal
        $lastDataBeforeStart = HargaBapokHarian::where('id_pasar', $pasarId)
            ->where('status', 'verified')
            ->where('tanggal', '<', $startDate)
            ->orderBy('tanggal', 'desc')
            ->orderBy('updated_at', 'desc')
            ->first();

        $labels = [];
        $prices = [];
        $lastKnownPrice = null;

        // Hitung harga dari data sebelum periode (untuk mengisi gap di awal)
        if ($lastDataBeforeStart && is_array($lastDataBeforeStart->data_harga)) {
            $totalHarga = 0;
            $totalCount = 0;
            foreach ($lastDataBeforeStart->data_harga as $entry) {
                $h = is_array($entry) ? ($entry['harga'] ?? 0) : ($entry ?? 0);
                if ($h > 0) {
                    $totalHarga += $h;
                    $totalCount++;
                }
            }
            $lastKnownPrice = $totalCount > 0 ? round($totalHarga / $totalCount) : null;
        }

        // Loop setiap hari dalam periode
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');
            $labels[] = $date->format('d M');
            
            $harga = $hargaList->get($dateKey);
            
            if ($harga && is_array($harga->data_harga)) {
                // Hitung rata-rata SEMUA komoditas dalam record ini
                $totalHarga = 0;
                $totalCount = 0;
                
                foreach ($harga->data_harga as $komoditasId => $entry) {
                    $h = is_array($entry) ? ($entry['harga'] ?? 0) : ($entry ?? 0);
                    if ($h > 0) {
                        $totalHarga += $h;
                        $totalCount++;
                    }
                }
                
                $currentPrice = $totalCount > 0 ? round($totalHarga / $totalCount) : $lastKnownPrice;
                $prices[] = $currentPrice;
                $lastKnownPrice = $currentPrice; // Update last known price
            } else {
                // Gunakan harga terakhir yang diketahui untuk mengisi gap
                $prices[] = $lastKnownPrice;
            }
        }

        return [
            'labels' => $labels,
            'prices' => $prices,
        ];
    }

    /**
     * Get statistik rata-rata SEMUA komoditas per pasar
     */
    private function getStatistikRataRataAllKomoditas()
    {
        $pasarList = Pasar::active()->monitored()->limit(5)->get();
        $komoditasList = Komoditas::active()->get();
        $rataRataPasar = [];
        
        foreach ($pasarList as $pasar) {
            // Ambil data terbaru berdasarkan tanggal dan updated_at dengan status verified
            $harga = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->where('status', 'verified')
                ->orderBy('tanggal', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first();
            
            if ($harga && is_array($harga->data_harga)) {
                // Hitung rata-rata SEMUA komoditas
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
                
                $rataRata = $totalCount > 0 ? round($totalHarga / $totalCount) : 0;
                
                if ($rataRata > 0) {
                    $rataRataPasar[] = [
                        'pasar' => $pasar->nama_pasar,
                        'rata' => $rataRata
                    ];
                }
            }
        }
        
        if (empty($rataRataPasar)) {
            return [
                'terendah' => ['pasar' => '-', 'rata' => 0],
                'tertinggi' => ['pasar' => '-', 'rata' => 0],
                'rata_rata' => 0,
                'selisih' => 0,
            ];
        }
        
        $sorted = collect($rataRataPasar)->sortBy('rata')->values();
        $terendah = $sorted->first();
        $tertinggi = $sorted->last();
        $rataSemua = $sorted->avg('rata');
        $selisih = ($tertinggi['rata'] ?? 0) - ($terendah['rata'] ?? 0);
        
        return [
            'terendah' => $terendah,
            'tertinggi' => $tertinggi,
            'rata_rata' => round($rataSemua),
            'selisih' => round($selisih),
        ];
    }
}