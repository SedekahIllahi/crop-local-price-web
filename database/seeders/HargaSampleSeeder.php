<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HargaBapokHarian;
use App\Models\Pasar;
use Carbon\Carbon;

class HargaSampleSeeder extends Seeder
{
    public function run(): void
    {
        $pasarList = Pasar::all();
        
        // Base prices for each komoditas (komoditas_id => base_price)
        $basePrices = [
            1 => 14500,  // Beras Premium
            2 => 12000,  // Beras Medium
            3 => 13000,  // Tepung Terigu
            4 => 45000,  // Cabai Rawit
            5 => 40000,  // Cabai Merah Besar
            6 => 35000,  // Bawang Merah
            7 => 38000,  // Bawang Putih
            8 => 35000,  // Daging Ayam
            9 => 130000, // Daging Sapi
            10 => 28000, // Telur Ayam
            11 => 18000, // Minyak Goreng
            12 => 17000, // Gula Pasir
        ];

        // Price variations per pasar (multiplier)
        $pasarVariations = [
            1 => 1.00,  // Pasar Bantul (baseline)
            2 => 1.08,  // Pasar Niten (8% higher)
            3 => 1.04,  // Pasar Imogiri (4% higher)
            4 => 1.02,  // Pasar Piyungan (2% higher)
            5 => 1.01,  // Pasar Pundong (1% higher)
        ];

        // Generate data for last 14 days
        for ($day = 13; $day >= 0; $day--) {
            $tanggal = Carbon::now()->subDays($day)->format('Y-m-d');
            
            foreach ($pasarList as $pasar) {
                $variation = $pasarVariations[$pasar->id] ?? 1.0;
                $dataHarga = [];
                
                foreach ($basePrices as $komoditasId => $basePrice) {
                    // Add daily random variation (-3% to +3%)
                    $dailyVariation = 1 + (rand(-30, 30) / 1000);
                    $price = round($basePrice * $variation * $dailyVariation, -2); // Round to nearest 100
                    $dataHarga[$komoditasId] = $price;
                }
                
                HargaBapokHarian::create([
                    'id_pasar' => $pasar->id,
                    'tanggal' => $tanggal,
                    'data_harga' => $dataHarga,
                    'status' => 'verified',
                ]);
            }
        }
    }
}
