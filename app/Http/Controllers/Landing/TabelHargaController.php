<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\Pasar;
use App\Models\HargaBapokHarian;
use App\Models\Komoditas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;

class TabelHargaController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->prepareData($request);

        return view('landing.tabel-harga', [
            'pasarList'          => $data['pasarList'],
            'pasarAktifId'       => $data['pasarAktifId'],
            'komoditasList'      => $data['komoditasList'],
            'komoditasFilterId'  => $data['komoditasFilterId'],
            'start'              => $data['start'],
            'end'                => $data['end'],
            'tanggalList'        => $data['hargaPerKomoditas']['tanggalList'],
            'komoditasData'      => $data['hargaPerKomoditas']['komoditasData'],
        ]);
    }

    public function preview(Request $request)
    {
        return $this->generatePdf($request)->stream('Tabel-Harga.pdf');
    }

    public function download(Request $request)
    {
        if ($request->has('inline')) {
            return $this->generatePdf($request)->stream('Tabel-Harga.pdf');
        }

        return $this->generatePdf($request)->download('Tabel-Harga.pdf');
    }

    public function previewPdfStream(Request $request)
    {
        return $this->generatePdf($request)->stream('Tabel-Harga.pdf');
    }

    // ========== PRIVATE HELPERS ==========

    /**
     * Generate PDF dari data tabel harga
     */
    private function generatePdf(Request $request)
    {
        $data = $this->prepareData($request);

        // Ambil nama pasar
        $pasar = Pasar::find($data['pasarAktifId']);
        $pasarNama = $pasar?->nama_pasar;

        // Ambil nama komoditas
        if ($data['komoditasFilterId'] && $data['komoditasFilterId'] !== 'all') {
            $komoditasNama = Komoditas::find($data['komoditasFilterId'])?->nama_komoditas;
        } else {
            $komoditasNama = 'Semua Komoditas';
        }

        return Pdf::loadView('landing.pdf', [
            'komoditasData'  => $data['hargaPerKomoditas']['komoditasData'],
            'tanggalList'    => $data['hargaPerKomoditas']['tanggalList'],
            'start'          => $data['start'],
            'end'            => $data['end'],
            'pasarId'        => $data['pasarAktifId'],
            'pasarNama'      => $pasarNama,
            'komoditasId'    => $data['komoditasFilterId'],
            'komoditasNama'  => $komoditasNama,
        ]);
    }

    /**
     * Siapkan data agar index dan PDF laporannya konsisten
     */
    private function prepareData(Request $request)
    {
        // Fix: gunakan pasar monitored, bukan hardcoded IDs
        $pasarList = Pasar::active()->monitored()->get();
        if ($pasarList->isEmpty()) {
            abort(404, 'Pasar tidak ditemukan');
        }

        $komoditasList = Komoditas::active()->orderBy('nama_komoditas')->get();

        $pasarAktifId = $request->input('pasar', $pasarList->first()->id);
        $komoditasFilterId = $request->input('komoditas', 'all');

        // Fix: hanya gunakan data verified untuk menentukan tanggal default
        $latestHarga = HargaBapokHarian::where('id_pasar', $pasarAktifId)
            ->where('status', 'verified')
            ->orderBy('tanggal', 'desc')
            ->first();

        // Jika tidak ada request tanggal, gunakan tanggal data terakhir yang tersedia
        if (!$request->input('end') && $latestHarga) {
            $end = $latestHarga->tanggal->copy();
        } else {
            $end = $request->input('end') ? Carbon::parse($request->input('end')) : Carbon::today();
        }

        if (!$request->input('start')) {
            $start = $end->copy()->subDays(6);
        } else {
            $start = Carbon::parse($request->input('start'));
        }

        // Batasi range maksimal 7 hari
        if ($end->diffInDays($start) > 6) {
            $start = $end->copy()->subDays(6);
        }

        $hargaPerKomoditas = $this->getHargaKomoditasMingguan(
            $pasarAktifId,
            $komoditasList,
            $komoditasFilterId,
            $start,
            $end
        );

        return [
            'pasarList'          => $pasarList,
            'pasarAktifId'       => $pasarAktifId,
            'komoditasList'      => $komoditasList,
            'komoditasFilterId'  => $komoditasFilterId,
            'start'              => $start,
            'end'                => $end,
            'hargaPerKomoditas'  => $hargaPerKomoditas,
        ];
    }

    /**
     * Ambil data harga per komoditas dalam range mingguan
     */
    private function getHargaKomoditasMingguan(
        int $pasarId,
        $komoditasList,
        $komoditasFilterId,
        Carbon $start,
        Carbon $end
    ) {
        $tanggalList = collect(CarbonPeriod::create($start, $end))->toArray();

        // Ambil semua data harga verified dalam range tanggal
        $allHarga = HargaBapokHarian::where('id_pasar', $pasarId)
            ->where('status', 'verified')
            ->whereBetween('tanggal', [$start->format('Y-m-d'), $end->format('Y-m-d')])
            ->orderBy('updated_at', 'desc')
            ->get();

        // Kelompokkan per tanggal (ambil yang terbaru per tanggal)
        $hargaPerTanggal = [];
        foreach ($allHarga as $harga) {
            $key = $harga->tanggal->format('Y-m-d');
            if (!isset($hargaPerTanggal[$key])) {
                $hargaPerTanggal[$key] = $harga;
            }
        }

        // Filter komoditas jika dipilih
        $komoditasToShow = ($komoditasFilterId && $komoditasFilterId !== 'all')
            ? $komoditasList->where('id', $komoditasFilterId)
            : $komoditasList;

        $komoditasData = [];
        foreach ($komoditasToShow as $index => $komoditas) {
            $hargaMap      = [];
            $hargaAngka    = [];
            $trendMap      = [];
            $hargaSebelum  = null;

            foreach ($tanggalList as $tanggal) {
                $key = $tanggal->format('Y-m-d');
                $record = $hargaPerTanggal[$key] ?? null;

                // Ekstraksi harga dari JSON data_harga
                $harga = $this->extractHarga($record, $komoditas->id);

                // Format harga untuk tampilan
                $hargaMap[$key] = ($harga !== null && $harga > 0)
                    ? 'Rp ' . number_format($harga, 0, ',', '.')
                    : '-';

                // Hitung trend
                $trend   = 'stabil';
                $selisih = 0;
                if ($harga !== null && $hargaSebelum !== null) {
                    $selisih = $harga - $hargaSebelum;
                    if ($harga > $hargaSebelum) {
                        $trend = 'naik';
                    } elseif ($harga < $hargaSebelum) {
                        $trend = 'turun';
                    }
                }
                $trendMap[$key] = ['trend' => $trend, 'selisih' => $selisih];

                if ($harga !== null) {
                    $hargaAngka[$key] = $harga;
                    $hargaSebelum = $harga;
                }
            }

            $komoditasData[] = [
                'no'     => $index + 1,
                'id'     => $komoditas->id,
                'nama'   => $komoditas->nama_komoditas,
                'satuan' => $komoditas->satuan,
                'harga'  => $hargaMap,
                'trend'  => $trendMap,
                'min'    => $hargaAngka ? min($hargaAngka) : null,
                'max'    => $hargaAngka ? max($hargaAngka) : null,
            ];
        }

        return [
            'tanggalList'   => $tanggalList,
            'komoditasData' => $komoditasData,
        ];
    }

    /**
     * Ekstraksi harga komoditas dari record harga_bapok_harian
     * Hanya tampilkan jika komoditas benar-benar diupdate pada tanggal record tersebut
     */
    private function extractHarga(?HargaBapokHarian $record, int $komoditasId): ?float
    {
        if (!$record || !$record->data_harga) {
            return null;
        }

        $entry = $record->data_harga[$komoditasId]
              ?? $record->data_harga[(string) $komoditasId]
              ?? null;

        if ($entry === null) {
            return null;
        }

        // Jika entry punya tanggal sendiri, cek apakah cocok dengan tanggal record
        // Hanya tampilkan harga jika komoditas benar-benar diupdate pada tanggal tersebut
        if (is_array($entry) && isset($entry['tanggal'])) {
            $entryDate = $entry['tanggal'];
            $recordDate = $record->tanggal->format('Y-m-d');
            if ($entryDate !== $recordDate) {
                return null; // Komoditas ini tidak diupdate pada tanggal ini
            }
        }

        $harga = is_array($entry) ? ($entry['harga'] ?? null) : $entry;

        return $harga !== null ? (float) $harga : null;
    }
}