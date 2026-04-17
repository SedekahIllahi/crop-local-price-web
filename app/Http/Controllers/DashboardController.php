<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HargaBapokHarian;
use App\Models\Pasar;

class DashboardController extends Controller
{
    /**
     * Mapping user ID ke pasar ID untuk lurah
     */
    private function getPasarIdByUserId($userId): ?int
    {
        $userToPasarMap = [
            3 => 1,  // Pasar Bantul
            4 => 2,  // Pasar Niten
            5 => 3,  // Pasar Imogiri
            6 => 4,  // Pasar Piyungan
            7 => 5,  // Pasar Pundong
        ];

        return $userToPasarMap[$userId] ?? null;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->setRule('dashboard.read');
        
        $user = Auth::user();
        $isLurahUser = $user && in_array($user->id, [3, 4, 5, 6, 7]);
        
        if ($isLurahUser) {
            // Data untuk user lurah (ID 3-7)
            $pasarId = $this->getPasarIdByUserId($user->id);
            $pasar = Pasar::find($pasarId);
            $namaPasar = $pasar ? $pasar->nama_pasar : 'Pasar';
            
            // Hitung statistik komoditas berdasarkan status
            $komoditasStats = $this->getKomoditasStatsByPasar($pasarId);
            
            // Cek apakah sudah update harga hari ini
            $updateStatus = $this->checkTodayUpdate($pasarId);
            
            return view('dashboard.index', [
                'isLurahUser' => true,
                'namaPasar' => $namaPasar,
                'totalKomoditas' => $komoditasStats['total'],
                'verifiedCount' => $komoditasStats['verified'],
                'pendingCount' => $komoditasStats['pending'],
                'gagalCount' => $komoditasStats['gagal'],
                'hasUpdatedToday' => $updateStatus['hasUpdatedToday'],
                'lastUpdateDate' => $updateStatus['lastUpdateDate'],
            ]);
        }
        
        // Data untuk admin/user biasa
        $userCount = \App\Models\User::count();
        $pasarCount = \App\Models\Pasar::count();
        $komoditasCount = \App\Models\Komoditas::count();
        $roleCount = \Spatie\Permission\Models\Role::count();

        return view('dashboard.index', [
            'isLurahUser' => false,
            'userCount' => $userCount,
            'pasarCount' => $pasarCount,
            'komoditasCount' => $komoditasCount,
            'roleCount' => $roleCount,
        ]);
    }

    /**
     * Get komoditas statistics by pasar
     */
    private function getKomoditasStatsByPasar($pasarId): array
    {
        $stats = [
            'total' => 0,
            'verified' => 0,
            'pending' => 0,
            'gagal' => 0,
        ];

        if (!$pasarId) {
            return $stats;
        }

        // Ambil data harga harian terbaru untuk pasar ini
        $hargaHarian = HargaBapokHarian::where('id_pasar', $pasarId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('updated_at', 'desc')
            ->first();

        if ($hargaHarian && is_array($hargaHarian->data_harga)) {
            foreach ($hargaHarian->data_harga as $komoditasId => $data) {
                if (!is_array($data)) continue;
                
                $stats['total']++;
                $status = $data['status'] ?? 'verified';
                
                if ($status === 'verified' || $status === 'sukses') {
                    $stats['verified']++;
                } elseif ($status === 'draft' || $status === 'pending') {
                    $stats['pending']++;
                } elseif ($status === 'gagal' || $status === 'failed') {
                    $stats['gagal']++;
                }
            }
        }

        return $stats;
    }

    /**
     * Check if user has updated harga today
     */
    private function checkTodayUpdate($pasarId): array
    {
        $result = [
            'hasUpdatedToday' => false,
            'lastUpdateDate' => null,
        ];

        if (!$pasarId) {
            return $result;
        }

        $today = now()->toDateString();

        // Cek apakah ada update hari ini
        $todayUpdate = HargaBapokHarian::where('id_pasar', $pasarId)
            ->whereDate('tanggal', $today)
            ->first();

        if ($todayUpdate) {
            $result['hasUpdatedToday'] = true;
            $result['lastUpdateDate'] = $todayUpdate->updated_at;
        } else {
            // Ambil tanggal update terakhir
            $lastUpdate = HargaBapokHarian::where('id_pasar', $pasarId)
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($lastUpdate) {
                $result['lastUpdateDate'] = $lastUpdate->updated_at;
            }
        }

        return $result;
    }
}
