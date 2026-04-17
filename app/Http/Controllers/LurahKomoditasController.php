<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Komoditas;
use App\Models\Pasar;
use Illuminate\Support\Facades\Auth;
use App\Models\HargaBapokHarian;

class LurahKomoditasController extends Controller
{
    /**
     * Mapping role lurah ke id_pasar
     */
    private function getPasarIdByRole($user): ?int
    {
        // Mapping role lurah ke nama pasar (lowercase tanpa 'lurah' prefix)
        $roleToPasarMap = [
            'lurahbantul'    => 1,  // Pasar Bantul
            'lurahniten'     => 2,  // Pasar Niten
            'lurahimogiri'   => 3,  // Pasar Imogiri
            'lurahpiyungan'  => 4,  // Pasar Piyungan
            'lurahpundong'   => 5,  // Pasar Pundong
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

    public function index(Request $request)
    {
        $this->setRule('komoditas.read');
        $user = Auth::user();

        $komoditasList = collect();
        $pasarId = $this->getPasarIdByRole($user);
        $namaPasar = $pasarId ? $this->getPasarName($pasarId) : 'Pasar';

        if ($user && $pasarId) {
            // ✅ Ambil data TERAKHIR berdasarkan tanggal dan updated_at (semua status)
            // Lurah perlu melihat data yang baru diupdate (draft/pending) juga
            $hargaHarian = HargaBapokHarian::with('creator')
                ->where('id_pasar', $pasarId)
                ->orderBy('tanggal', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($hargaHarian && is_array($hargaHarian->data_harga)) {
                $komoditasMaster = Komoditas::whereIn(
                    'id',
                    array_keys($hargaHarian->data_harga)
                )->get()->keyBy('id');

                foreach ($hargaHarian->data_harga as $komoditasId => $data) {
                    if (
                        !isset($komoditasMaster[$komoditasId]) ||
                        !is_array($data) ||
                        !isset($data['harga'], $data['tanggal'])
                    ) {
                        continue;
                    }

                    $komoditas = $komoditasMaster[$komoditasId];

                    $komoditasList->push((object) [
                        'id'             => $komoditas->id,
                        'komoditas_id'   => $komoditas->id,
                        'nama_komoditas' => $komoditas->nama_komoditas,
                        'satuan'         => $komoditas->satuan,
                        'harga'          => $data['harga'],
                        'harga_acuan'    => $komoditas->harga_acuan,
                        'kategori_id'    => $komoditas->kategori_id,
                        'tanggal'        => $data['tanggal'],
                        'updated_at'     => $hargaHarian->updated_at,
                        'created_by'     => $hargaHarian->creator,
                        'is_integrated'  => $hargaHarian->is_integrated,
                        'status'         => $data['status'] ?? 'verified', // 🔥 status per komoditas
                    ]);
                }
            }
        }

        // Prepare komoditas data for JSON (for Update All modal)
        $komoditasJson = $komoditasList->map(function($item) {
            $hargaVal = is_array($item->harga) ? ($item->harga['harga'] ?? null) : $item->harga;
            return [
                'id' => $item->id,
                'nama' => $item->nama_komoditas,
                'harga' => $hargaVal,
                'satuan' => $item->satuan ?? '',
            ];
        })->values()->toArray();

        return view('auth.lurah', [
            'komoditasList' => $komoditasList,
            'komoditasJson' => $komoditasJson,
            'namaPasar' => $namaPasar,
            'pasarId' => $pasarId,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_komoditas' => 'required',
            'satuan' => 'required',
            'harga_acuan' => 'required|numeric',
            'kategori_id' => 'required|integer',
        ]);
        Komoditas::create($request->all());
        return redirect()->route('lurah.komoditas.index')->with('success', 'Komoditas berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_komoditas' => 'required',
            'satuan' => 'required',
            'harga_acuan' => 'required|numeric',
            'kategori_id' => 'required|integer',
        ]);
        $komoditas = Komoditas::findOrFail($id);
        $komoditas->update($request->all());
        return redirect()->route('lurah.komoditas.index')->with('success', 'Komoditas berhasil diupdate');
    }

    public function destroy($id)
    {
        $komoditas = Komoditas::findOrFail($id);
        $komoditas->delete();
        return redirect()->route('lurah.komoditas.index')->with('success', 'Komoditas berhasil dihapus');
    }

    // ✅ UPDATE HARGA: AMAN + APPROVAL FLOW
    public function updateHargaKomoditas(Request $request)
    {
        $request->validate([
            'komoditas_id' => 'required|integer|exists:komoditas,id',
            'harga_baru'   => 'required|numeric|min:0',
            'tanggal'      => 'nullable|date', // tanggal yang dipilih lurah
        ]);

        $user = Auth::user();
        $pasarId = $this->getPasarIdByRole($user);

        if (!$pasarId) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses ke pasar manapun',
            ], 403);
        }

        // Tanggal yang dipilih lurah (default: hari ini)
        $tanggalDipilih = $request->tanggal ? \Carbon\Carbon::parse($request->tanggal)->toDateString() : now()->toDateString();

        // 🔥 1. Cek apakah sudah ada record untuk tanggal yang dipilih
        $hargaHarian = HargaBapokHarian::where('id_pasar', $pasarId)
            ->whereDate('tanggal', $tanggalDipilih)
            ->first();

        // 🔥 2. Jika belum ada record untuk tanggal tersebut, buat baru dengan copy dari verified terakhir
        if (!$hargaHarian) {
            // Ambil data verified terakhir sebagai template
            $verified = HargaBapokHarian::where('id_pasar', $pasarId)
                ->where('status', 'verified')
                ->orderBy('tanggal', 'desc')
                ->orderBy('updated_at', 'desc')
                ->first();

            if ($verified) {
                // Copy data dari verified terakhir
                $hargaHarian = $verified->replicate([
                    'verified_by',
                    'is_integrated',
                    'integrated_at',
                    'integration_notes',
                ]);
                $hargaHarian->tanggal = $tanggalDipilih;
                $hargaHarian->status = 'draft';
                $hargaHarian->created_by = Auth::id();
                $hargaHarian->verified_by = null;
                $hargaHarian->save();
            } else {
                // Jika tidak ada data sama sekali, buat record baru kosong
                $hargaHarian = HargaBapokHarian::create([
                    'id_pasar' => $pasarId,
                    'tanggal' => $tanggalDipilih,
                    'data_harga' => [],
                    'status' => 'draft',
                    'created_by' => Auth::id(),
                ]);
            }
        }

        // 🔥 3. Update harga komoditas dengan status draft
        $hargaHarian->setHarga(
            $request->komoditas_id,
            $request->harga_baru,
            $tanggalDipilih
        );

        // Ubah status record menjadi draft jika sedang verified
        if ($hargaHarian->status === 'verified') {
            $hargaHarian->status = 'draft';
        }
        $hargaHarian->save();

        return response()->json([
            'success' => true,
            'message' => 'Harga berhasil diperbarui (menunggu verifikasi admin)',
            'komoditas_id' => $request->komoditas_id,
            'harga_baru' => (int) $request->harga_baru,
            'status' => 'draft',
            'tanggal' => \Carbon\Carbon::parse($tanggalDipilih)->format('d M Y'),
        ]);

    }
}