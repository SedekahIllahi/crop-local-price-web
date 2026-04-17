<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komoditas;
use App\Models\Pasar;
use App\Models\StokBapokMingguan;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LurahStokController extends Controller
{
    /**
     * Update semua stok komoditas sekaligus
     */
    public function updateAllStok(Request $request)
    {
        $request->validate([
            'stok_list' => 'required|array',
            'stok_list.*.komoditas_id' => 'required|integer|exists:komoditas,id',
            'stok_list.*.stok_baru' => 'required|numeric|min:0',
            'tanggal' => 'nullable|date',
        ]);

        $user = Auth::user();
        $pasarId = $this->getPasarIdByRole($user);
        if (!$pasarId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pasar manapun',
            ], 403);
        }

        // Check 7-day restriction (hanya record verified)
        $latestStok = StokBapokMingguan::where('id_pasar', $pasarId)
            ->where('status', 'verified')
            ->orderBy('updated_at', 'desc')
            ->first();
        if ($latestStok) {
            $lastUpdate = Carbon::parse($latestStok->updated_at);
            $nextUpdateDate = $lastUpdate->copy()->addDays(7);
            if (now()->lt($nextUpdateDate)) {
                $daysRemaining = (int) ceil(now()->diffInDays($nextUpdateDate, false));
                return response()->json([
                    'success' => false,
                    'message' => "Data hanya dapat diupdate 7 hari setelah update terakhir. Dapat diupdate pada tanggal {$nextUpdateDate->format('d M Y')} ({$daysRemaining} hari lagi).",
                ], 400);
            }
        }

        $tanggalDipilih = $request->tanggal ? Carbon::parse($request->tanggal) : now();
        $mingguKe = ceil($tanggalDipilih->day / 7);
        $bulan = $tanggalDipilih->month;
        $tahun = $tanggalDipilih->year;

        // Cek apakah sudah ada record untuk minggu ini
        $stokMingguan = StokBapokMingguan::where('id_pasar', $pasarId)
            ->where('minggu_ke', $mingguKe)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->where('status', 'draft')
            ->first();

        // Jika belum ada, buat baru dari verified terakhir
        if (!$stokMingguan) {
            $lastVerified = StokBapokMingguan::where('id_pasar', $pasarId)
                ->where('status', 'verified')
                ->orderBy('tanggal_pendataan', 'desc')
                ->first();

            $stokMingguan = new StokBapokMingguan();
            $stokMingguan->id_pasar = $pasarId;
            $stokMingguan->tanggal_pendataan = $tanggalDipilih->toDateString();
            $stokMingguan->minggu_ke = $mingguKe;
            $stokMingguan->bulan = $bulan;
            $stokMingguan->tahun = $tahun;
            $stokMingguan->data_stok = $lastVerified ? $lastVerified->data_stok : [];
            $stokMingguan->status = 'draft';
            $stokMingguan->created_by = Auth::id();
        }

        // Update hanya komoditas yang diinput, sisanya biarkan status dan data sebelumnya
        $dataStok = $stokMingguan->data_stok ?? [];
        $updatedIds = [];
        foreach ($request->stok_list as $stokItem) {
            $dataStok[$stokItem['komoditas_id']] = [
                'jumlah_stok' => (float) $stokItem['stok_baru'],
                'tanggal' => $tanggalDipilih->toDateString(),
                'status' => 'draft',
            ];
            $updatedIds[] = $stokItem['komoditas_id'];
        }
        // Untuk komoditas yang tidak diupdate, pastikan status tetap seperti sebelumnya
        foreach ($dataStok as $komoditasId => $item) {
            if (!in_array($komoditasId, $updatedIds)) {
                // Jika $item bukan array (data lama), konversi ke array format baru
                if (!is_array($item)) {
                    $dataStok[$komoditasId] = [
                        'jumlah_stok' => (float) $item,
                        'tanggal' => $tanggalDipilih->toDateString(),
                        'status' => 'verified',
                    ];
                } else if (!isset($item['status'])) {
                    $dataStok[$komoditasId]['status'] = 'verified';
                }
            }
        }
        $stokMingguan->data_stok = $dataStok;
        $stokMingguan->status = 'draft';
        $stokMingguan->save();

        return response()->json([
            'success' => true,
            'message' => 'Semua stok komoditas berhasil diupdate (menunggu verifikasi admin)',
        ]);
    }
    /**
     * Mapping role lurah ke id_pasar
     */
    private function getPasarIdByRole($user): ?int
    {
        $roleToPasarMap = [
            'lurahbantul'    => 1,
            'lurahniten'     => 2,
            'lurahimogiri'   => 3,
            'lurahpiyungan'  => 4,
            'lurahpundong'   => 5,
        ];

        foreach ($roleToPasarMap as $role => $pasarId) {
            if ($user->hasRole($role)) {
                return $pasarId;
            }
        }

        return null;
    }

    /**
     * Get nama pasar berdasarkan role lurah
     */
    private function getPasarName($pasarId): string
    {
        $pasar = Pasar::find($pasarId);
        return $pasar ? $pasar->nama_pasar : 'Pasar';
    }

    /**
     * Display stock management page
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $komoditasList = collect();
        $pasarId = $this->getPasarIdByRole($user);
        $namaPasar = $pasarId ? $this->getPasarName($pasarId) : 'Pasar';

        if ($user && $pasarId) {
            // Ambil data stok terbaru berdasarkan updated_at (sama dengan admin)
            $stokMingguan = StokBapokMingguan::where('id_pasar', $pasarId)
                ->latest('updated_at')
                ->first();

            if ($stokMingguan && is_array($stokMingguan->data_stok)) {
                $komoditasMaster = Komoditas::whereIn(
                    'id',
                    array_keys($stokMingguan->data_stok)
                )->get()->keyBy('id');

                foreach ($stokMingguan->data_stok as $komoditasId => $data) {
                    if (!isset($komoditasMaster[$komoditasId])) {
                        continue;
                    }

                    $komoditas = $komoditasMaster[$komoditasId];
                    
                    // Handle both array format and simple value format
                    $stokValue = is_array($data) ? ($data['jumlah_stok'] ?? $data['stok'] ?? null) : $data;
                    $tanggalValue = is_array($data) ? ($data['tanggal'] ?? null) : null;
                    
                    // Get status from individual item first, fallback to main record status
                    $statusValue = 'pending';
                    if (is_array($data) && isset($data['status'])) {
                        $statusValue = $data['status'];
                    } elseif ($stokMingguan->status) {
                        $statusValue = $stokMingguan->status;
                    }

                    $komoditasList->push((object) [
                        'id'             => $komoditas->id,
                        'komoditas_id'   => $komoditas->id,
                        'nama_komoditas' => $komoditas->nama_komoditas,
                        'satuan'         => $komoditas->satuan,
                        'stok'           => $stokValue,
                        'tanggal'        => $tanggalValue ?? $stokMingguan->tanggal_pendataan,
                        'updated_at'     => $stokMingguan->updated_at,
                        'created_by'     => $stokMingguan->creator ?? null,
                        'status'         => $statusValue,
                        'minggu_ke'      => $stokMingguan->minggu_ke,
                        'bulan'          => $stokMingguan->bulan,
                        'tahun'          => $stokMingguan->tahun,
                    ]);
                }
            } else {
                // Jika belum ada data stok, tampilkan semua komoditas aktif dengan stok kosong
                $allKomoditas = Komoditas::active()->get();
                foreach ($allKomoditas as $komoditas) {
                    $komoditasList->push((object) [
                        'id'             => $komoditas->id,
                        'komoditas_id'   => $komoditas->id,
                        'nama_komoditas' => $komoditas->nama_komoditas,
                        'satuan'         => $komoditas->satuan,
                        'stok'           => null,
                        'tanggal'        => null,
                        'updated_at'     => null,
                        'created_by'     => null,
                        'status'         => 'pending',
                        'minggu_ke'      => null,
                        'bulan'          => null,
                        'tahun'          => null,
                    ]);
                }
            }
        }

        // Prepare komoditas data for JSON (for Update All modal)
        $komoditasJson = $komoditasList->map(function($item) {
            return [
                'id' => $item->id,
                'nama' => $item->nama_komoditas,
                'stok' => $item->stok,
                'satuan' => $item->satuan ?? '',
            ];
        })->values()->toArray();

        // Get latest updated_at for 7-day confirmation check
        $latestUpdatedAt = null;
        if ($user && $pasarId) {
            $latestStok = StokBapokMingguan::where('id_pasar', $pasarId)
                ->orderBy('updated_at', 'desc')
                ->first();
            $latestUpdatedAt = $latestStok?->updated_at;
        }

        return view('auth.lurah-stok', [
            'komoditasList' => $komoditasList,
            'komoditasJson' => $komoditasJson,
            'namaPasar' => $namaPasar,
            'pasarId' => $pasarId,
            'latestUpdatedAt' => $latestUpdatedAt,
        ]);
    }

    /**
     * Update stok komoditas
     */
    public function updateStok(Request $request)
    {
        $request->validate([
            'komoditas_id' => 'required|integer|exists:komoditas,id',
            'stok_baru'    => 'required|numeric|min:0',
            'tanggal'      => 'nullable|date',
        ]);

        $user = Auth::user();
        $pasarId = $this->getPasarIdByRole($user);

        if (!$pasarId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pasar manapun',
            ], 403);
        }

        // Check 7-day restriction
        $latestStok = StokBapokMingguan::where('id_pasar', $pasarId)
            ->orderBy('updated_at', 'desc')
            ->first();
        
        if ($latestStok) {
            $lastUpdate = Carbon::parse($latestStok->updated_at);
            $nextUpdateDate = $lastUpdate->copy()->addDays(7);
            
            if (now()->lt($nextUpdateDate)) {
                $daysRemaining = (int) ceil(now()->diffInDays($nextUpdateDate, false));
                return response()->json([
                    'success' => false,
                    'message' => "Data hanya dapat diupdate 7 hari setelah update terakhir. Dapat diupdate pada tanggal {$nextUpdateDate->format('d M Y')} ({$daysRemaining} hari lagi).",
                ], 400);
            }
        }

        $tanggalDipilih = $request->tanggal ? Carbon::parse($request->tanggal) : now();
        $mingguKe = ceil($tanggalDipilih->day / 7);
        $bulan = $tanggalDipilih->month;
        $tahun = $tanggalDipilih->year;

        // Cek apakah sudah ada record untuk minggu ini
        $stokMingguan = StokBapokMingguan::where('id_pasar', $pasarId)
            ->where('minggu_ke', $mingguKe)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        // Jika belum ada, buat baru
        if (!$stokMingguan) {
            // Coba copy dari data verified terakhir
            $lastVerified = StokBapokMingguan::where('id_pasar', $pasarId)
                ->where('status', 'verified')
                ->orderBy('tanggal_pendataan', 'desc')
                ->first();

            $stokMingguan = new StokBapokMingguan();
            $stokMingguan->id_pasar = $pasarId;
            $stokMingguan->tanggal_pendataan = $tanggalDipilih->toDateString();
            $stokMingguan->minggu_ke = $mingguKe;
            $stokMingguan->bulan = $bulan;
            $stokMingguan->tahun = $tahun;
            $stokMingguan->data_stok = $lastVerified ? $lastVerified->data_stok : [];
            $stokMingguan->status = 'draft';
            $stokMingguan->created_by = Auth::id();
        }

        // Update stok untuk komoditas tertentu
        $dataStok = $stokMingguan->data_stok ?? [];
        $dataStok[$request->komoditas_id] = [
            'jumlah_stok' => (float) $request->stok_baru,
            'tanggal' => $tanggalDipilih->toDateString(),
            'status' => 'draft',
        ];

        $stokMingguan->data_stok = $dataStok;
        
        // Ubah status menjadi draft jika sedang verified
        if ($stokMingguan->status === 'verified') {
            $stokMingguan->status = 'draft';
        }
        
        $stokMingguan->save();

        // Get satuan for display
        $komoditas = Komoditas::find($request->komoditas_id);
        $satuanDisplay = strtolower($komoditas->satuan) === 'liter' ? 'L' : $komoditas->satuan;

        return response()->json([
            'success' => true,
            'message' => 'Stok berhasil diperbarui (menunggu verifikasi admin)',
            'komoditas_id' => $request->komoditas_id,
            'stok_baru' => (float) $request->stok_baru,
            'stok_display' => number_format($request->stok_baru, 0, ',', '.') . ' ' . $satuanDisplay,
            'status' => 'draft',
            'tanggal' => $tanggalDipilih->format('d M Y'),
        ]);
    }
}
