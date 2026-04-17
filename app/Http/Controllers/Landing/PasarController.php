<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasar;
use App\Models\Komoditas;
use App\Models\HargaBapokHarian;
use App\Models\StokBapokMingguan;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PasarController extends Controller
{
    /**
     * Halaman Pasar - menampilkan informasi stok dan harga per pasar
     */
    public function pasar(Request $request)
    {
        $pasarList = Pasar::active()->monitored()->with('kecamatan')->get();
        $komoditasList = Komoditas::active()->get();

        // Pasar terpilih
        $selectedPasarId = $request->get('pasar', $pasarList->first()?->id);
        $selectedPasar   = Pasar::find($selectedPasarId);

        // Harga terbaru (verified)
        $hargaTerbaru = HargaBapokHarian::byPasar($selectedPasarId)
            ->verified()
            ->orderBy('tanggal', 'desc')
            ->first();

        // Ambil data harga 7 hari SEKALI (biar efisien)
        $endDate   = Carbon::today();
        $startDate = Carbon::today()->subDays(6);

        $hargaMingguan = HargaBapokHarian::byPasar($selectedPasarId)
            ->verified()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();

        // Ambil data stok mingguan terbaru berdasarkan updated_at dan status verified
        $stokMingguan = StokBapokMingguan::where('id_pasar', $selectedPasarId)
            ->verified()
            ->orderBy('updated_at', 'desc')
            ->first();

        // Proses komoditas
        $komoditas = $komoditasList->map(function ($item, $index) use (
            $hargaTerbaru,
            $hargaMingguan,
            $stokMingguan,
            $startDate,
            $endDate
        ) {

            // Harga terbaru
            $harga = $hargaTerbaru?->getHarga($item->id);

            // Stok dari database (jika ada)
            $stokValue = $stokMingguan?->getStok($item->id);
            // Format satuan: kg atau L (untuk liter)
            $satuanDisplay = strtolower($item->satuan) === 'liter' ? 'L' : $item->satuan;
            $stokDisplay = $stokValue 
                ? number_format($stokValue, 0, ',', '.') . ' ' . $satuanDisplay
                : 'Data tidak tersedia';

            // Chart 7 hari
            $chartLabels = [];
            $chartPrices = [];

            foreach (CarbonPeriod::create($startDate, $endDate) as $date) {
                $chartLabels[] = $date->format('d M');

                $dayData = $hargaMingguan->first(fn ($d) =>
                    $d->tanggal->toDateString() === $date->toDateString()
                );

                $chartPrices[] = $dayData?->getHarga($item->id) ?? 0;
            }

            // Min & Max
            $filteredPrices = array_filter($chartPrices, fn ($v) => $v > 0);
            $minHarga = $filteredPrices ? min($filteredPrices) : null;
            $maxHarga = $filteredPrices ? max($filteredPrices) : null;

            return [
                'no'           => $index + 1,
                'id'           => $item->id,
                'nama'         => $item->nama_komoditas,
                'emoji'        => $item->icon,
                'satuan'       => $item->satuan,
                'harga'        => is_numeric($harga)
                    ? 'Rp. ' . number_format($harga, 0, ',', '.') . '/' . $item->satuan
                    : '-',
                'harga_raw'    => $harga,
                'stok'         => $stokDisplay,
                'stok_raw'     => $stokValue,
                'status'       => $this->getStokStatus($harga, $item->harga_acuan),
                'chart_labels' => $chartLabels,
                'chart_prices' => $chartPrices,
                'min_harga'    => is_numeric($minHarga)
                    ? 'Rp. ' . number_format($minHarga, 0, ',', '.')
                    : '-',
                'max_harga'    => is_numeric($maxHarga)
                    ? 'Rp. ' . number_format($maxHarga, 0, ',', '.')
                    : '-',
                // Tambahkan kolom tanggal update stok dari updated_at stok mingguan
                'tanggal_update_stok' => $stokMingguan?->updated_at ? $stokMingguan->updated_at->format('d M Y H:i') : null,
            ];
        });

        // Chart trend global (default komoditas ID 1)
        $chartData = $this->getChartData($selectedPasarId, 7);

        return view('landing.pasar', compact(
            'pasarList',
            'komoditas',
            'selectedPasar',
            'chartData'
        ));
    }

    /**
     * Alias halaman pasar (dropdown Stock)
     */
    public function stockPasar(Request $request)
    {
        return $this->pasar($request);
    }

    // ================== HELPERS ==================

    private function getStokStatus($harga, $het)
    {
        if (!is_numeric($harga) || !is_numeric($het)) return 'aman';

        $ratio = $harga / $het;
        if ($ratio <= 1.05) return 'aman';
        if ($ratio <= 1.15) return 'sedang';

        return 'rendah';
    }

    private function getChartData($pasarId, $days, $komoditasId = 1)
    {
        $endDate   = Carbon::today();
        $startDate = Carbon::today()->subDays($days - 1);

        $hargaData = HargaBapokHarian::byPasar($pasarId)
            ->verified()
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal')
            ->get();

        $labels = [];
        $prices = [];

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $labels[] = $date->format('d M');

            $dayData = $hargaData->first(fn ($d) =>
                $d->tanggal->toDateString() === $date->toDateString()
            );

            $prices[] = $dayData?->getHarga($komoditasId) ?? 0;
        }

        return [
            'labels' => $labels,
            'prices' => $prices,
        ];
    }
}
