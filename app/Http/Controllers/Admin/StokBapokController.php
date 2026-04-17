<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StokBapokMingguan;
use App\Models\Komoditas;
use App\Models\Pasar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Carbon\Carbon;

class StokBapokController extends Controller
{
    /**
     * Get pasar by slug dynamically from database.
     * Slug is generated from nama_pasar: "Pasar Bantul" => "bantul"
     */
    protected function getPasarBySlug(string $slug): ?Pasar
    {
        return Pasar::monitored()
            ->get()
            ->first(function ($pasar) use ($slug) {
                $generatedSlug = Str::slug(str_replace('Pasar ', '', $pasar->nama_pasar));
                return $generatedSlug === $slug;
            });     
    }

    /**
     * Generate slug from pasar name.
     */
    protected function generateSlug(string $namaPasar): string
    {
        return Str::slug(str_replace('Pasar ', '', $namaPasar));
    }

    /**
     * Display a listing of stok bapok for a specific pasar.
     */
    public function index(Request $request, string $pasarSlug)
    {
        $pasar = $this->getPasarBySlug($pasarSlug);
        
        if (!$pasar) {
            abort(404, 'Pasar tidak ditemukan');
        }

        // Server-side date filter
        if ($request->filled('tanggal')) {
            $latestStokBapok = StokBapokMingguan::where('id_pasar', $pasar->id)
                ->whereDate('tanggal_pendataan', $request->tanggal)
                ->first();
        } else {
            // Default: ambil data terbaru berdasarkan updated_at
            $latestStokBapok = StokBapokMingguan::where('id_pasar', $pasar->id)
                ->latest('updated_at')
                ->first();
        }

        $komoditasList = Komoditas::active()->get();

        $komoditasWithStok = $komoditasList->map(function ($komoditas) use ($latestStokBapok) {
            $stok = null;
            $tanggal = null;
            $createdBy = null;
            $updatedAt = null;
            $status = 'pending';

            if ($latestStokBapok && isset($latestStokBapok->data_stok[$komoditas->id])) {
                $dataStok = $latestStokBapok->data_stok[$komoditas->id];

                if (is_array($dataStok)) {
                    $stok = $dataStok['jumlah_stok'] ?? $dataStok['stok'] ?? null;
                    $tanggal = $dataStok['tanggal'] ?? $latestStokBapok->tanggal_pendataan;
                    $status = $dataStok['status'] ?? $latestStokBapok->status ?? 'pending';
                } else {
                    $stok = $dataStok;
                    $tanggal = $latestStokBapok->tanggal_pendataan;
                    $status = $latestStokBapok->status ?? 'pending';
                }

                // Get creator name if relation exists
                $createdBy = $latestStokBapok->creator ? $latestStokBapok->creator->name : null;
                $updatedAt = $latestStokBapok->updated_at;
            }

            return (object)[
                'id' => $komoditas->id,
                'nama_komoditas' => $komoditas->nama_komoditas,
                'satuan' => $komoditas->satuan,
                'stok' => $stok,
                'tanggal' => $tanggal,
                'created_by' => $createdBy,
                'updated_at' => $updatedAt,
                'status' => $status,
            ];
        });

        // Filter by status if provided
        if ($request->filled('status')) {
            $statusFilter = $request->status;

            $komoditasWithStok = $komoditasWithStok->filter(function ($item) use ($statusFilter) {
                if ($statusFilter === 'sukses') {
                    return $item->status === 'verified';
                }
                if ($statusFilter === 'draft') {
                    return $item->status === 'draft';
                }
                if ($statusFilter === 'pending') {
                    return $item->status === 'pending';
                }
                if ($statusFilter === 'gagal') {
                    return $item->status === 'gagal';
                }
                return true;
            });
        }

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $total = $komoditasWithStok->count();
        $items = $komoditasWithStok->forPage($page, $perPage)->values();

        $komoditasPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get monitored pasar for filter dropdown
        $pasarList = Pasar::monitored()->get();

        // Prepare komoditas data for JSON (for Update All modal)
        $komoditasJson = $komoditasWithStok->map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama_komoditas,
                'stok' => $item->stok,
                'satuan' => $item->satuan ?? '',
            ];
        })->values()->toArray();

        return view('admin.stok-bapok.index', [
            'pasar' => $pasar,
            'pasarList' => $pasarList,
            'komoditasList' => $komoditasPaginated,
            'komoditasJson' => $komoditasJson,
            'pasarSlug' => $pasarSlug,
            'latestDate' => $latestStokBapok?->tanggal_pendataan,
            'selectedDate' => $request->tanggal,
            'allKomoditas' => Komoditas::active()->orderBy('nama_komoditas')->get(),
        ]);
    }

    /**
     * Update stok for a komoditas.
     */
    public function updateStok(Request $request, string $pasarSlug)
    {
        $request->validate([
            'komoditas_id' => 'required|integer|exists:komoditas,id',
            'stok_baru'    => 'required|numeric|min:0',
            'tanggal'      => 'nullable|date',
        ]);

        $pasar = $this->getPasarBySlug($pasarSlug);
        
        if (!$pasar) {
            return response()->json([
                'success' => false,
                'message' => 'Pasar tidak ditemukan',
            ], 404);
        }

        $tanggalDipilih = $request->tanggal ? Carbon::parse($request->tanggal) : now();
        $mingguKe = ceil($tanggalDipilih->day / 7);
        $bulan = $tanggalDipilih->month;
        $tahun = $tanggalDipilih->year;

        // Cek apakah sudah ada record untuk minggu ini
        $stokMingguan = StokBapokMingguan::where('id_pasar', $pasar->id)
            ->where('minggu_ke', $mingguKe)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        // Jika belum ada, buat baru
        if (!$stokMingguan) {
            $lastVerified = StokBapokMingguan::where('id_pasar', $pasar->id)
                ->where('status', 'verified')
                ->orderBy('tanggal_pendataan', 'desc')
                ->first();

            $stokMingguan = new StokBapokMingguan();
            $stokMingguan->id_pasar = $pasar->id;
            $stokMingguan->tanggal_pendataan = $tanggalDipilih->toDateString();
            $stokMingguan->minggu_ke = $mingguKe;
            $stokMingguan->bulan = $bulan;
            $stokMingguan->tahun = $tahun;
            $stokMingguan->data_stok = $lastVerified ? $lastVerified->data_stok : [];
            $stokMingguan->status = 'draft';
            $stokMingguan->created_by = auth()->id();
        }

        // Update stok untuk komoditas tertentu
        $dataStok = $stokMingguan->data_stok ?? [];
        $dataStok[$request->komoditas_id] = [
            'jumlah_stok' => (float) $request->stok_baru,
            'tanggal' => $tanggalDipilih->toDateString(),
            'status' => 'draft',
        ];

        $stokMingguan->data_stok = $dataStok;
        
        if ($stokMingguan->status === 'verified') {
            $stokMingguan->status = 'draft';
        }
        
        $stokMingguan->save();

        // Get satuan for display
        $komoditas = Komoditas::find($request->komoditas_id);
        $satuanDisplay = strtolower($komoditas->satuan) === 'liter' ? 'L' : $komoditas->satuan;

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui',
            'komoditas_id' => $request->komoditas_id,
            'stok_baru' => (float) $request->stok_baru,
            'stok_display' => number_format($request->stok_baru, 0, ',', '.') . ' ' . $satuanDisplay,
            'status' => 'draft',
            'tanggal' => $tanggalDipilih->format('d M Y'),
        ]);
    }

    /**
     * Publish (verify) stok data.
     */
    public function publish(Request $request, string $pasarSlug)
    {
        $pasar = $this->getPasarBySlug($pasarSlug);
        
        if (!$pasar) {
            return response()->json([
                'success' => false,
                'message' => 'Pasar tidak ditemukan',
            ], 404);
        }

        $latestStokBapok = StokBapokMingguan::where('id_pasar', $pasar->id)
            ->latest('updated_at')
            ->first();

        if (!$latestStokBapok) {
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data stok untuk diverifikasi',
            ], 404);
        }

        // Get komoditas IDs from request (if partial publish)
        $komoditasIds = $request->input('komoditas_ids', []);
        
        if (!empty($komoditasIds)) {
            // Partial publish - only verify selected komoditas
            $dataStok = $latestStokBapok->data_stok ?? [];
            foreach ($komoditasIds as $komoditasId) {
                if (isset($dataStok[$komoditasId])) {
                    if (is_array($dataStok[$komoditasId])) {
                        $dataStok[$komoditasId]['status'] = 'verified';
                    } else {
                        $dataStok[$komoditasId] = [
                            'jumlah_stok' => $dataStok[$komoditasId],
                            'status' => 'verified',
                        ];
                    }
                }
            }
            $latestStokBapok->data_stok = $dataStok;
            
            // Check if all items are verified
            $allVerified = true;
            foreach ($dataStok as $data) {
                if (is_array($data) && ($data['status'] ?? 'draft') !== 'verified') {
                    $allVerified = false;
                    break;
                }
            }
            if ($allVerified) {
                $latestStokBapok->status = 'verified';
                $latestStokBapok->verified_by = Auth::id();
            }
        } else {
            // Full publish - verify all
            $latestStokBapok->status = 'verified';
            $latestStokBapok->verified_by = Auth::id();
            
            $dataStok = $latestStokBapok->data_stok ?? [];
            foreach ($dataStok as $komoditasId => $data) {
                if (is_array($data)) {
                    $dataStok[$komoditasId]['status'] = 'verified';
                } else {
                    $dataStok[$komoditasId] = [
                        'jumlah_stok' => $data,
                        'status' => 'verified',
                    ];
                }
            }
            $latestStokBapok->data_stok = $dataStok;
        }

        $latestStokBapok->save();

        return response()->json([
            'success' => true,
            'message' => 'Data stok berhasil diverifikasi',
        ]);
    }

    /**
     * Get pending items for verification.
     */
    public function getPendingItems(Request $request, string $pasarSlug)
    {
        $pasar = $this->getPasarBySlug($pasarSlug);
        
        if (!$pasar) {
            return response()->json([
                'success' => false,
                'message' => 'Pasar tidak ditemukan',
            ], 404);
        }

        $latestStokBapok = StokBapokMingguan::where('id_pasar', $pasar->id)
            ->latest('updated_at')
            ->first();

        if (!$latestStokBapok) {
            return response()->json([
                'success' => true,
                'items' => [],
            ]);
        }

        $pendingItems = [];
        $komoditasList = Komoditas::active()->get()->keyBy('id');

        foreach ($latestStokBapok->data_stok ?? [] as $komoditasId => $data) {
            $status = is_array($data) ? ($data['status'] ?? 'draft') : 'draft';
            
            if (in_array($status, ['pending', 'draft'])) {
                $komoditas = $komoditasList[$komoditasId] ?? null;
                if ($komoditas) {
                    $stok = is_array($data) ? ($data['jumlah_stok'] ?? $data['stok'] ?? null) : $data;
                    $satuanDisplay = strtolower($komoditas->satuan) === 'liter' ? 'L' : $komoditas->satuan;
                    
                    $pendingItems[] = [
                        'komoditas_id' => $komoditasId,
                        'nama_komoditas' => $komoditas->nama_komoditas,
                        'stok' => $stok,
                        'stok_display' => $stok ? number_format($stok, 0, ',', '.') . ' ' . $satuanDisplay : '-',
                        'satuan' => $komoditas->satuan,
                        'status' => $status,
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'items' => $pendingItems,
        ]);
    }

    /**
     * Export Excel using Blade integration
     */
    public function exportExcel(Request $request, string $pasarSlug)
    {
        $pasar = $this->getPasarBySlug($pasarSlug);
        if (!$pasar) {
            abort(404, 'Pasar tidak ditemukan');
        }

        // Server-side date filter
        if ($request->filled('tanggal')) {
            $latestStokBapok = StokBapokMingguan::where('id_pasar', $pasar->id)
                ->whereDate('tanggal_pendataan', $request->tanggal)
                ->first();
        } else {
            // Default: ambil data terbaru berdasarkan updated_at
            $latestStokBapok = StokBapokMingguan::where('id_pasar', $pasar->id)
                ->latest('updated_at')
                ->first();
        }

        $komoditasList = Komoditas::active()->orderBy('nama_komoditas')->get();

        $komoditasData = $komoditasList->map(function ($komoditas) use ($latestStokBapok) {
            $stok = null;
            $tanggal = null;
            $status = 'pending';
            $createdBy = '-';

            if ($latestStokBapok && isset($latestStokBapok->data_stok[$komoditas->id])) {
                $dataStok = $latestStokBapok->data_stok[$komoditas->id];

                if (is_array($dataStok)) {
                    $stok = $dataStok['jumlah_stok'] ?? $dataStok['stok'] ?? null;
                    $tanggal = $dataStok['tanggal'] ?? $latestStokBapok->tanggal_pendataan;
                    $status = $dataStok['status'] ?? $latestStokBapok->status ?? 'pending';
                } else {
                    $stok = $dataStok;
                    $tanggal = $latestStokBapok->tanggal_pendataan;
                    $status = $latestStokBapok->status ?? 'pending';
                }
                // Get creator name if relation exists
                $createdBy = $latestStokBapok->creator ? $latestStokBapok->creator->name : '-';
            }

            $satuanDisplay = strtolower($komoditas->satuan) === 'liter' ? 'L' : ($komoditas->satuan ?? 'kg');
            $stokFormatted = !is_null($stok) ? number_format($stok, 0, ',', '.') . ' ' . $satuanDisplay : '-';
            $tanggalFormatted = $tanggal ? \Carbon\Carbon::parse($tanggal)->format('d M Y') : '-';

            return [
                'nama_komoditas' => $komoditas->nama_komoditas,
                'stok_formatted' => $stokFormatted,
                'tanggal' => $tanggalFormatted,
                'status' => $status,
                'created_by' => $createdBy,
            ];
        });

        // Generate filename
        $filename = 'Stok_Bapok_' . Str::slug($pasar->nama_pasar) . '_' . ($latestStokBapok ? $latestStokBapok->tanggal_pendataan : now()->format('Y-m-d')) . '.xlsx';

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // --- Logo ---
        $logoPath = public_path('images/logo_kabBantul.png');
        if (file_exists($logoPath)) {
            $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo Kabupaten Bantul');
            $drawing->setPath($logoPath);
            $drawing->setHeight(80);
            $drawing->setCoordinates('A1');
            $drawing->setWorksheet($sheet);
        }

        // --- Header Text ---
        // Merge rows for title
        $sheet->mergeCells('B1:F1');
        $sheet->mergeCells('B2:F2');
        $sheet->mergeCells('B3:F3');
        $sheet->mergeCells('B4:F4');
        $sheet->mergeCells('B5:F5');

        $sheet->setCellValue('B1', 'PEMERINTAH KABUPATEN BANTUL');
        $sheet->setCellValue('B2', 'DINAS KOPERASI, UMKM, PERINDUSTRIAN, DAN PERDAGANGAN');
        $sheet->setCellValue('B3', 'Komplek Pemda II Manding Bantul, Jl. Lingkar Timur Manding Trirenggo Bantul');
        $sheet->setCellValue('B4', 'Telepon: (0274-2810422 / 0812 2566 5517) Email: diskukmpp@bantulkab.go.id');
        $sheet->setCellValue('B5', 'Website: https://dkukmpp.bantulkab.go.id');

        $sheet->getStyle('B1:B2')->applyFromArray(['font' => ['size' => 14, 'bold' => true]]);
        $sheet->getStyle('B1:F5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Report Title
        $sheet->mergeCells('A7:F7');
        $sheet->setCellValue('A7', 'Laporan Stok Bapok Mingguan');
        $sheet->getStyle('A7')->applyFromArray(['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]);

        $sheet->mergeCells('A8:F8');
        $sheet->setCellValue('A8', 'Pasar: ' . $pasar->nama_pasar);
        $sheet->getStyle('A8')->applyFromArray(['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]);

        // --- Table Header ---
        $headers = ['No', 'Nama Komoditas', 'Tanggal', 'Stok', 'Status', 'Created By'];
        $headerRow = 10;
        $sheet->fromArray($headers, NULL, 'A' . $headerRow);

        // Style Table Header (Green Background)
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '059669'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ];
        $sheet->getStyle('A' . $headerRow . ':F' . $headerRow)->applyFromArray($headerStyle);

        // --- Data ---
        $row = $headerRow + 1;
        foreach ($komoditasData as $index => $item) {
            $sheet->setCellValue('A' . $row, $index + 1);
            $sheet->setCellValue('B' . $row, $item['nama_komoditas']);
            $sheet->setCellValue('C' . $row, $item['tanggal']);
            $sheet->setCellValue('D' . $row, $item['stok_formatted']);
            $sheet->setCellValue('E' . $row, ucfirst($item['status']));
            $sheet->setCellValue('F' . $row, $item['created_by']);
            $row++;
        }

        // Style Data Table
        $lastRow = $row - 1;
        $sheet->getStyle('A' . ($headerRow + 1) . ':F' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $sheet->getStyle('A' . ($headerRow + 1) . ':A' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('C' . ($headerRow + 1) . ':F' . $lastRow)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // AutoSize Columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // --- Footer ---
        $sheet->mergeCells('A' . ($row + 1) . ':F' . ($row + 1));
        $sheet->setCellValue('A' . ($row + 1), 'Dicetak : ' . now()->format('d M Y H:i:s'));
        $sheet->getStyle('A' . ($row + 1))->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // Output
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }
}
