<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\KategoriKomoditas;

class KategoriKomoditasSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            ['nama_kategori' => 'Beras & Karbohidrat', 'is_active' => true],
            ['nama_kategori' => 'Sayuran', 'is_active' => true],
            ['nama_kategori' => 'Bumbu Dapur', 'is_active' => true],
            ['nama_kategori' => 'Protein Hewani', 'is_active' => true],
            ['nama_kategori' => 'Minyak & Gula', 'is_active' => true],
        ];

        foreach ($kategori as $data) {
            KategoriKomoditas::create($data);
        }
    }
}
