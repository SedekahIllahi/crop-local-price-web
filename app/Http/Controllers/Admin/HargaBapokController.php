<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HargaBapokHarian;
use App\Models\Pasar;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class HargaBapokController extends Controller
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
     * Display a listing of harga bapok for a specific pasar.
     */
    public function index(Request $request, string $pasarSlug)
    {
        $pasar = $this->getPasarBySlug($pasarSlug);
        
        if (!$pasar) {
            abort(404, 'Pasar tidak ditemukan');
        }

        // Server-side date filter
        if ($request->filled('tanggal')) {
            $latestHargaBapok = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->whereDate('tanggal', $request->tanggal)
                ->first();
        } else {
            // Default: ambil data terbaru berdasarkan updated_at
            $latestHargaBapok = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->latest('updated_at')
                ->first();
        }

        $komoditasList = \App\Models\Komoditas::active()->get();

        $komoditasWithHarga = $komoditasList->map(function ($komoditas) use ($latestHargaBapok) {
            $harga = null;
            $tanggal = null;
            $isIntegrated = false;
            $createdBy = null;
            $updatedAt = null;

            if ($latestHargaBapok && isset($latestHargaBapok->data_harga[$komoditas->id])) {
                $dataHarga = $latestHargaBapok->data_harga[$komoditas->id];

                if (is_array($dataHarga)) {
                    $harga = $dataHarga['harga'] ?? null;
                    $tanggal = $dataHarga['tanggal'] ?? $latestHargaBapok->tanggal;
                } else {
                    $harga = $dataHarga;
                    $tanggal = $latestHargaBapok->tanggal;
                }

                $isIntegrated = $latestHargaBapok->is_integrated;
                $createdBy = $latestHargaBapok->creator;
                $updatedAt = $latestHargaBapok->updated_at;
            }

            // Get status per komoditas
            $status = 'verified';
            if ($latestHargaBapok && isset($latestHargaBapok->data_harga[$komoditas->id])) {
                $dataHarga = $latestHargaBapok->data_harga[$komoditas->id];
                if (is_array($dataHarga)) {
                    $status = $dataHarga['status'] ?? 'verified';
                }
            }

            return (object)[
                'id' => $komoditas->id,
                'nama_komoditas' => $komoditas->nama_komoditas,
                'satuan' => $komoditas->satuan,
                'harga' => $harga,
                'harga_acuan' => $komoditas->harga_acuan,
                'tanggal' => $tanggal,
                'is_integrated' => $isIntegrated,
                'created_by' => $createdBy,
                'updated_at' => $updatedAt,
                'status' => $status,  // 🔥 status per komoditas
                'image_path' => $komoditas->image_path,  // 🖼️ gambar komoditas
                'icon' => $komoditas->icon,  // 🔷 icon komoditas
            ];
        });

        if ($request->filled('status')) {
            $statusFilter = $request->status; // 'sukses', 'pending', 'gagal'

            $komoditasWithHarga = $komoditasWithHarga->filter(function ($item) use ($statusFilter) {
                // $item->status is derived in the map function above
                if ($statusFilter === 'sukses') {
                    return $item->status === 'verified';
                }
                if ($statusFilter === 'pending') {
                    return in_array($item->status, ['pending', 'draft']);
                }
                if ($statusFilter === 'gagal') {
                    return $item->status === 'gagal'; // Assuming 'gagal' is a valid status enum/string
                }
                return true;
            });
        }

        $perPage = $request->input('per_page', 10);
        $page = $request->input('page', 1);
        $total = $komoditasWithHarga->count();
        $items = $komoditasWithHarga->forPage($page, $perPage)->values();

        $komoditasPaginated = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get monitored pasar that have data in database for filter dropdown
        $pasarList = Pasar::monitored()
            ->whereHas('hargaHarian')
            ->get();

        return view('admin.harga-bapok.index', [
            'pasar' => $pasar,
            'pasarList' => $pasarList,
            'komoditasList' => $komoditasPaginated,
            'pasarSlug' => $pasarSlug,
            'latestDate' => $latestHargaBapok?->tanggal,
            'selectedDate' => $request->tanggal, // For form persistence
            'allKomoditas' => \App\Models\Komoditas::active()->orderBy('nama_komoditas')->get(),
            'allKategori' => \App\Models\KategoriKomoditas::active()->get(),
        ]);
    }

    /**
     * Store new master commodity
     */
    public function storeKomoditas(Request $request)
    {
        $request->validate([
            'nama_komoditas' => 'required|string|max:255',
            'satuan' => 'required|string|max:50',
            'harga_acuan' => 'required|numeric|min:0',
            'kategori_id' => 'required|exists:kategori_komoditas,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'icon' => 'nullable|string|max:10',
        ]);

        // Handle image/photo upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_img_' . $file->getClientOriginalName();
            $file->move(public_path('images/komoditas'), $filename);
            $imagePath = 'images/komoditas/' . $filename;
        }

        $komoditas = \App\Models\Komoditas::create([
            'nama_komoditas' => $request->nama_komoditas,
            'satuan' => $request->satuan,
            'harga_acuan' => $request->harga_acuan,
            'kategori_id' => $request->kategori_id,
            'images' => $imagePath ?? '',
            'icon' => $request->icon,
            'status' => 1, // Active by default
            'is_active' => true,
        ]);

        // Auto-inject harga acuan ke semua pasar yang dipantau
        // Buat/update record HARI INI agar komoditas baru tampil di tanggal yang benar
        $monitoredPasars = Pasar::monitored()->get();
        $injectedCount = 0;
        $today = now()->toDateString();

        foreach ($monitoredPasars as $pasarItem) {
            // 1. Cek apakah sudah ada record hari ini
            $todayRecord = HargaBapokHarian::where('id_pasar', $pasarItem->id)
                ->whereDate('tanggal', $today)
                ->first();

            if ($todayRecord) {
                // Record hari ini sudah ada — tambahkan komoditas baru ke dalamnya
                $dataHarga = $todayRecord->data_harga ?? [];
                $dataHarga[$komoditas->id] = [
                    'harga'   => (int) $request->harga_acuan,
                    'tanggal' => $today,
                    'status'  => 'verified',
                ];
                $todayRecord->data_harga = $dataHarga;
                $todayRecord->save();
                $injectedCount++;
            } else {
                // Belum ada record hari ini — buat baru, copy data dari record verified terakhir
                $latestVerified = HargaBapokHarian::where('id_pasar', $pasarItem->id)
                    ->where('status', 'verified')
                    ->latest('updated_at')
                    ->first();

                $dataHarga = $latestVerified ? ($latestVerified->data_harga ?? []) : [];
                $dataHarga[$komoditas->id] = [
                    'harga'   => (int) $request->harga_acuan,
                    'tanggal' => $today,
                    'status'  => 'verified',
                ];

                HargaBapokHarian::create([
                    'id_pasar'    => $pasarItem->id,
                    'tanggal'     => $today,
                    'data_harga'  => $dataHarga,
                    'status'      => 'verified',
                    'created_by'  => auth()->id(),
                    'verified_by' => auth()->id(),
                ]);
                $injectedCount++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Komoditas berhasil ditambahkan' . ($injectedCount > 0 ? " dan harga acuan diterapkan ke {$injectedCount} pasar" : ''),
            'data' => $komoditas
        ]);
    }

    /**
     * Create - redirect to index (inline add available)
     */
    public function create(string $pasarSlug)
    {
        $pasar = $this->getPasarBySlug($pasarSlug);
        if (!$pasar) {
            abort(404, 'Pasar tidak ditemukan');
        }

        // Redirect to index - inline editing available there
        return redirect()->route('pasar.harga-bapok.index', $pasarSlug)
            ->with('info', 'Gunakan tombol Tambah Data pada halaman utama');
    }

    public function store(Request $request, string $pasarSlug)
    {
        return redirect()->route('pasar.harga-bapok.index', $pasarSlug)
            ->with('success', 'Data berhasil disimpan');
    }

    /**
     * Show - redirect to index (detail modal available)
     */
    public function show(string $pasarSlug, string $id)
    {
        $pasar = $this->getPasarBySlug($pasarSlug);
        if (!$pasar) {
            abort(404, 'Pasar tidak ditemukan');
        }

        // Redirect to index - detail modal available there
        return redirect()->route('pasar.harga-bapok.index', $pasarSlug);
    }

    /**
     * Edit - redirect to index (inline edit modal available)
     */
    public function edit(string $pasarSlug, string $id)
    {
        $pasar = $this->getPasarBySlug($pasarSlug);
        if (!$pasar) {
            abort(404, 'Pasar tidak ditemukan');
        }

        // Redirect to index - edit modal available there
        return redirect()->route('pasar.harga-bapok.index', $pasarSlug);
    }

    public function update(Request $request, string $pasarSlug, string $id)
    {
        return redirect()->route('pasar.harga-bapok.index', $pasarSlug)
            ->with('success', 'Data berhasil diperbarui');
    }

    public function destroy(string $pasarSlug, string $id)
    {
        return redirect()->route('pasar.harga-bapok.index', $pasarSlug)
            ->with('success', 'Data berhasil dihapus');
    }

    /**
     * Get data by pasar ID for AJAX filter
     */
    public function getDataByPasar(Request $request)
    {
        $pasarId = $request->input('pasar_id');
        
        $pasar = Pasar::find($pasarId);
        if (!$pasar) {
            return response()->json(['success' => false, 'message' => 'Pasar tidak ditemukan'], 404);
        }

        $latestHargaBapok = HargaBapokHarian::where('id_pasar', $pasar->id)
            ->latest('updated_at')
            ->first();

        $komoditasList = \App\Models\Komoditas::active()->get();

        $data = $komoditasList->map(function ($komoditas, $index) use ($latestHargaBapok, $pasar) {
            $harga = null;
            $tanggal = null;
            $status = 'verified';
            $createdBy = null;

            if ($latestHargaBapok && isset($latestHargaBapok->data_harga[$komoditas->id])) {
                $dataHarga = $latestHargaBapok->data_harga[$komoditas->id];
                if (is_array($dataHarga)) {
                    $harga = $dataHarga['harga'] ?? null;
                    $tanggal = $dataHarga['tanggal'] ?? $latestHargaBapok->tanggal;
                    $status = $dataHarga['status'] ?? 'verified';
                } else {
                    $harga = $dataHarga;
                    $tanggal = $latestHargaBapok->tanggal;
                }
                $createdBy = $latestHargaBapok->creator?->name ?? '-';
            }

            return [
                'no' => $index + 1,
                'id' => $komoditas->id,
                'nama_komoditas' => $komoditas->nama_komoditas,
                'satuan' => $komoditas->satuan,
                'harga' => $harga,
                'harga_formatted' => $harga ? 'Rp' . number_format($harga, 0, ',', '.') . ($komoditas->satuan ? '/' . $komoditas->satuan : '') : '-',
                'tanggal' => $tanggal ? \Carbon\Carbon::parse($tanggal)->format('d M Y H:i') : '-',
                'status' => $status,
                'created_by' => $createdBy ?? '-',
            ];
        });

        return response()->json([
            'success' => true,
            'pasar' => [
                'id' => $pasar->id,
                'nama' => $pasar->nama_pasar,
            ],
            'data' => $data->values(),
        ]);
    }

    public function updateHarga(Request $request)
    {
        $request->validate([
            'komoditas_id' => 'required|integer',
            'harga_baru' => 'required|numeric|min:1',
            'pasar_id' => 'required|integer',
        ]);

        // 🔥 ambil data harga SESUAI PASAR & yang terakhir
        $hargaBapok = HargaBapokHarian::where('id_pasar', $request->pasar_id)
            ->latest('updated_at')
            ->first();

        if (!$hargaBapok) {
            return response()->json([
                'success' => false,
                'message' => 'Data harga tidak ditemukan'
            ], 404);
        }

        // pastikan array
        $dataHarga = is_array($hargaBapok->data_harga)
            ? $hargaBapok->data_harga
            : [];

        $dataHarga[$request->komoditas_id] = [
            'harga' => (int) $request->harga_baru,
            'tanggal' => now()->format('Y-m-d'),
            'status' => 'draft',  // 🔥 status per komoditas
        ];

        $hargaBapok->data_harga = $dataHarga;
        $hargaBapok->status = 'draft';  // 🔥 Changed from 'pending' to 'draft' (valid enum)
        
        // Set created_by if not already set
        if (!$hargaBapok->created_by) {
            $hargaBapok->created_by = auth()->id();
        }
        
        $hargaBapok->save();

        return response()->json([
            'success' => true,
            'komoditas_id' => $request->komoditas_id,
            'harga_baru' => $request->harga_baru,
            'updated_at' => now()->format('d M Y'),
        ]);
    }


    /**
     * Publish harga bapok ke view pasar
     * Membuat record baru dengan tanggal hari ini agar tidak menimpa data lama
     * Mendukung partial publish berdasarkan komoditas_ids yang dipilih
     */
    public function publish(Request $request, string $pasarSlug)
    {
        try {
            $pasar = $this->getPasarBySlug($pasarSlug);
            if (!$pasar) {
                return response()->json(['success' => false, 'message' => 'Pasar tidak ditemukan'], 404);
            }

            // Get selected komoditas IDs from request
            $komoditasIds = $request->input('komoditas_ids', []);
            
            // Ambil data draft/pending terbaru
            $draftHarga = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->whereIn('status', ['pending', 'draft'])
                ->latest('updated_at')
                ->first();

            if (!$draftHarga) {
                return response()->json(['success' => false, 'message' => 'Tidak ada data untuk di-publish'], 404);
            }

            $today = now()->toDateString();

            // Cek apakah sudah ada record untuk hari ini
            $existingToday = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->where('tanggal', $today)
                ->first();

            // If specific komoditas_ids are provided, only verify those
            if (!empty($komoditasIds)) {
                $dataHarga = $draftHarga->data_harga;
                
                foreach ($komoditasIds as $kId) {
                    if (isset($dataHarga[$kId])) {
                        if (is_array($dataHarga[$kId])) {
                            $dataHarga[$kId]['status'] = 'verified';
                        }
                    }
                }
                
                // Update draft with partial verified status
                $draftHarga->data_harga = $dataHarga;
                
                // Check if all items are now verified
                $allVerified = true;
                foreach ($dataHarga as $val) {
                    if (is_array($val) && isset($val['status']) && $val['status'] !== 'verified') {
                        $allVerified = false;
                        break;
                    }
                }
                
                if ($allVerified) {
                    $draftHarga->status = 'verified';
                    $draftHarga->verified_by = auth()->id();
                }
                
                $draftHarga->save();
                
                // Also update today's record if exists
                if ($existingToday) {
                    $existingDataHarga = $existingToday->data_harga;
                    foreach ($komoditasIds as $kId) {
                        if (isset($dataHarga[$kId])) {
                            $existingDataHarga[$kId] = $dataHarga[$kId];
                        }
                    }
                    $existingToday->data_harga = $existingDataHarga;
                    $existingToday->save();
                }
                
                $confirmedCount = count($komoditasIds);
                return response()->json([
                    'success' => true, 
                    'message' => $confirmedCount . ' komoditas berhasil dikonfirmasi'
                ]);
            }

            // Original logic for confirm all (when no specific IDs provided)
            if ($existingToday) {
                // Update record hari ini dengan data draft
                $existingToday->data_harga = $draftHarga->data_harga;
                $existingToday->setAllStatusVerified();
                $existingToday->status = 'verified';
                $existingToday->verified_by = auth()->id();
                $existingToday->save();
            } else {
                // Buat record baru untuk hari ini
                $newHarga = new HargaBapokHarian();
                $newHarga->id_pasar = $pasar->id;
                $newHarga->tanggal = $today;
                $newHarga->data_harga = $draftHarga->data_harga;
                $newHarga->status = 'verified';
                $newHarga->created_by = $draftHarga->created_by ?? auth()->id();
                $newHarga->verified_by = auth()->id();
                $newHarga->setAllStatusVerified();
                $newHarga->save();
            }

            // Jika draft dari tanggal lain, tandai sebagai sudah diproses
            // Use optional chaining to safely handle null tanggal
            if ($draftHarga->tanggal && $draftHarga->tanggal?->toDateString() !== $today) {
                $draftHarga->status = 'verified';
                $draftHarga->setAllStatusVerified();
                $draftHarga->save();
            }

            return response()->json(['success' => true, 'message' => 'Data berhasil di-publish']);
        } catch (\Exception $e) {
            \Log::error('Publish harga error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get pending items for review modal
     */
    public function getPendingItems(Request $request)
    {
        $pasarId = $request->input('pasar_id');
        $komoditasIds = $request->input('komoditas_ids');
        $pasar = Pasar::find($pasarId);

        if (!$pasar) {
            return response()->json(['success' => false, 'message' => 'Pasar tidak ditemukan'], 404);
        }

        // Parse komoditas_ids if provided as comma-separated string
        $selectedIds = [];
        if ($komoditasIds) {
            $selectedIds = is_array($komoditasIds) ? $komoditasIds : explode(',', $komoditasIds);
            $selectedIds = array_map('intval', $selectedIds);
        }

        $draftHarga = HargaBapokHarian::where('id_pasar', $pasar->id)
            ->whereIn('status', ['pending', 'draft'])
            ->latest('updated_at')
            ->first();

        if (!$draftHarga || empty($draftHarga->data_harga)) {
             return response()->json(['success' => true, 'data' => []]);
        }
        
        $komoditasList = \App\Models\Komoditas::active()->get()->keyBy('id');
        $pendingItems = [];

        foreach ($draftHarga->data_harga as $kId => $val) {
             // Filter by selected komoditas IDs if provided
             if (!empty($selectedIds) && !in_array((int)$kId, $selectedIds)) {
                 continue;
             }

             $status = is_array($val) ? ($val['status'] ?? 'verified') : 'verified';
             
             if (in_array($status, ['draft', 'pending'])) {
                 $komoditas = $komoditasList[$kId] ?? null;
                 if ($komoditas) {
                     $harga = is_array($val) ? $val['harga'] : $val;
                     $pendingItems[] = [
                         'komoditas_id' => $kId,
                         'nama_komoditas' => $komoditas->nama_komoditas,
                         'harga' => $harga,
                         'harga_formatted' => 'Rp' . number_format($harga, 0, ',', '.') . ($komoditas->satuan ? '/' . $komoditas->satuan : ''),
                     ];
                 }
             }
        }

        return response()->json(['success' => true, 'data' => $pendingItems]);
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

        // Determine date filter
        $date = $request->input('tanggal');
        if ($date) {
            $latestHargaBapok = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->whereDate('tanggal', $date)
                ->first();
        } else {
            $latestHargaBapok = HargaBapokHarian::where('id_pasar', $pasar->id)
                ->latest('updated_at')
                ->first();
        }

        $tanggalDisplay = $latestHargaBapok 
            ? \Carbon\Carbon::parse($latestHargaBapok->tanggal)->format('d M Y') 
            : ($date ? \Carbon\Carbon::parse($date)->format('d M Y') : now()->format('d M Y'));

        $komoditasList = \App\Models\Komoditas::active()->orderBy('nama_komoditas')->get();

        $komoditasData = $komoditasList->map(function ($komoditas) use ($latestHargaBapok) {
            $harga = null;
            $status = 'verified';
            $createdBy = '-';
            
            if ($latestHargaBapok && isset($latestHargaBapok->data_harga[$komoditas->id])) {
                $dataHarga = $latestHargaBapok->data_harga[$komoditas->id];
                if (is_array($dataHarga)) {
                    $harga = $dataHarga['harga'] ?? null;
                    $status = $dataHarga['status'] ?? 'verified';
                } else {
                    $harga = $dataHarga;
                }
                $createdBy = $latestHargaBapok->creator?->name ?? '-';
            }

            $tanggal = $latestHargaBapok ? $latestHargaBapok->updated_at : null;
            $tanggalFormatted = $tanggal ? \Carbon\Carbon::parse($tanggal)->format('d M Y') : '-';

            return [
                'nama_komoditas' => $komoditas->nama_komoditas,
                'satuan' => $komoditas->satuan,
                'harga_formatted' => $harga ? 'Rp ' . number_format($harga, 0, ',', '.') : '-',
                'tanggal' => $tanggalFormatted,
                'status' => $status,
                'created_by' => $createdBy,
            ];
        });

        // Generate filename
        // Generate filename
        $filename = 'Harga_Bapok_' . Str::slug($pasar->nama_pasar) . '_' . ($latestHargaBapok ? $latestHargaBapok->tanggal : now()->format('Y-m-d')) . '.xlsx';

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
        $sheet->setCellValue('A7', 'Laporan Harga Bapok Harian');
        $sheet->getStyle('A7')->applyFromArray(['font' => ['bold' => true, 'size' => 14], 'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]);

        $sheet->mergeCells('A8:F8');
        $sheet->setCellValue('A8', 'Pasar: ' . $pasar->nama_pasar);
        $sheet->getStyle('A8')->applyFromArray(['alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]]);

        // --- Table Header ---
        $headers = ['No', 'Nama Komoditas', 'Tanggal', 'Harga', 'Status', 'Created By'];
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
            $sheet->setCellValue('D' . $row, $item['harga_formatted']);
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