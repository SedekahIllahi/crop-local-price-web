<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasar;
use App\Models\RefKecamatan;

class DaftarPasarController extends Controller
{
    /**
     * Halaman Daftar Pasar - menampilkan grid semua pasar
     */
    public function daftarPasar()
    {
        $pasarList = Pasar::with('kecamatan')
            ->orderBy('nama_pasar')
            ->get();

        $kecamatanList = RefKecamatan::orderBy('nama')->get();

        return view('landing.daftar-pasar', compact('pasarList', 'kecamatanList'));
    }
}