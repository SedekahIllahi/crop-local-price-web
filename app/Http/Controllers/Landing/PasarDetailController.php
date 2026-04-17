<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pasar;
use App\Models\Komoditas;

class PasarDetailController extends Controller
{
    /**
     * Halaman detail profil pasar
     */
    public function pasarDetail($id)
    {
        $pasar = Pasar::with(['kecamatan', 'kalurahan', 'dusun', 'hargaTerbaru'])->findOrFail($id);
        $komoditasList = Komoditas::active()->pluck('nama_komoditas', 'id');

        return view('landing.pasar-detail', compact('pasar', 'komoditasList'));
    }
}