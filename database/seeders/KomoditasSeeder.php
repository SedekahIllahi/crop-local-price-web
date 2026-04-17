<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Komoditas;

class KomoditasSeeder extends Seeder
{
    public function run(): void
    {
        $komoditas = [
            // Beras & Karbohidrat (kategori_id: 1)
            ['nama_komoditas' => 'Beras Premium', 'satuan' => 'kg', 'kategori_id' => 1, 'icon' => '🍚', 'image_path' => 'beras-premium.png', 'harga_acuan' => 15000],
            ['nama_komoditas' => 'Beras Medium', 'satuan' => 'kg', 'kategori_id' => 1, 'icon' => '🍚', 'image_path' => 'beras-medium.png', 'harga_acuan' => 13000],
            ['nama_komoditas' => 'Tepung Terigu', 'satuan' => 'kg', 'kategori_id' => 1, 'icon' => '🌾', 'image_path' => 'tepung-terigu.png', 'harga_acuan' => 12000],
            
            // Bumbu Dapur (kategori_id: 3)
            ['nama_komoditas' => 'Cabai Rawit', 'satuan' => 'kg', 'kategori_id' => 3, 'icon' => '🌶️', 'image_path' => 'cabai-rawit-merah.png', 'harga_acuan' => 45000],
            ['nama_komoditas' => 'Cabai Merah Besar', 'satuan' => 'kg', 'kategori_id' => 3, 'icon' => '🌶️', 'image_path' => 'cabai-merah-besar.png', 'harga_acuan' => 40000],
            ['nama_komoditas' => 'Bawang Merah', 'satuan' => 'kg', 'kategori_id' => 3, 'icon' => '🧅', 'image_path' => 'bawang-merah.png', 'harga_acuan' => 35000],
            ['nama_komoditas' => 'Bawang Putih', 'satuan' => 'kg', 'kategori_id' => 3, 'icon' => '🧄', 'image_path' => 'bawang-putih.png', 'harga_acuan' => 40000],
            
            // Protein Hewani (kategori_id: 4)
            ['nama_komoditas' => 'Daging Ayam', 'satuan' => 'kg', 'kategori_id' => 4, 'icon' => '🍗', 'image_path' => 'daging-ayam.png', 'harga_acuan' => 35000],
            ['nama_komoditas' => 'Daging Sapi', 'satuan' => 'kg', 'kategori_id' => 4, 'icon' => '🥩', 'image_path' => 'daging-sapi.png', 'harga_acuan' => 130000],
            ['nama_komoditas' => 'Telur Ayam', 'satuan' => 'kg', 'kategori_id' => 4, 'icon' => '🥚', 'image_path' => 'telur-ayam.png', 'harga_acuan' => 28000],
            
            // Minyak & Gula (kategori_id: 5)
            ['nama_komoditas' => 'Minyak Goreng', 'satuan' => 'liter', 'kategori_id' => 5, 'icon' => '🧴', 'image_path' => 'minyak-goreng.png', 'harga_acuan' => 16500],
            ['nama_komoditas' => 'Gula Pasir', 'satuan' => 'kg', 'kategori_id' => 5, 'icon' => '🧂', 'image_path' => 'gula-pasir.png', 'harga_acuan' => 18000],
        ];

        foreach ($komoditas as $data) {
            Komoditas::updateOrCreate(
                ['nama_komoditas' => $data['nama_komoditas']],
                $data
            );
        }
    }
}
