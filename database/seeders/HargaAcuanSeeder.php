<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Komoditas;

class HargaAcuanSeeder extends Seeder
{
    public function run(): void
    {
        // Harga Eceran Tertinggi (HET) referensi
        $hetData = [
            'Beras Premium' => 15500,
            'Beras Medium' => 12500,
            'Tepung Terigu' => 13000,
            'Cabai Rawit' => 50000,
            'Cabai Merah Besar' => 45000,
            'Bawang Merah' => 38000,
            'Bawang Putih' => 40000,
            'Daging Ayam' => 36000,
            'Daging Sapi' => 135000,
            'Telur Ayam' => 30000,
            'Minyak Goreng' => 14000,
            'Gula Pasir' => 17500,
        ];

        foreach ($hetData as $nama => $het) {
            Komoditas::where('nama_komoditas', $nama)->update(['harga_acuan' => $het]);
        }
    }
}
