<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pasar;
use App\Models\RefKecamatan;

class PasarSeeder extends Seeder
{
    public function run(): void
    {
        // Get kecamatan IDs
        $bantul = RefKecamatan::where('nama', 'Bantul')->first();
        $kasihan = RefKecamatan::where('nama', 'Kasihan')->first();
        $imogiri = RefKecamatan::where('nama', 'Imogiri')->first();
        $piyungan = RefKecamatan::where('nama', 'Piyungan')->first();
        $pundong = RefKecamatan::where('nama', 'Pundong')->first();

        $pasarList = [
            [
                'nama_pasar' => 'Pasar Bantul',
                'id_kecamatan' => $bantul?->id ?? 7,
                'alamat_lengkap' => 'Jl. Jend. Sudirman, Bantul',
                'tipe' => 'tradisional',
                'jml_pedagang' => 450,
                'jml_kios' => 120,
                'jml_los' => 200,
                'latitude' => -7.8894,
                'longitude' => 110.3314,
                'is_active' => true,
                'is_monitored' => true,
                'wajib_pantau' => true,
            ],
            [
                'nama_pasar' => 'Pasar Niten',
                'id_kecamatan' => $kasihan?->id ?? 15,
                'alamat_lengkap' => 'Jl. Niten, Kasihan',
                'tipe' => 'tradisional',
                'jml_pedagang' => 320,
                'jml_kios' => 80,
                'jml_los' => 150,
                'latitude' => -7.8234,
                'longitude' => 110.3456,
                'is_active' => true,
                'is_monitored' => true,
                'wajib_pantau' => true,
            ],
            [
                'nama_pasar' => 'Pasar Imogiri',
                'id_kecamatan' => $imogiri?->id ?? 9,
                'alamat_lengkap' => 'Jl. Imogiri Timur',
                'tipe' => 'tradisional',
                'jml_pedagang' => 280,
                'jml_kios' => 70,
                'jml_los' => 130,
                'latitude' => -7.9345,
                'longitude' => 110.3789,
                'is_active' => true,
                'is_monitored' => true,
                'wajib_pantau' => true,
            ],
            [
                'nama_pasar' => 'Pasar Piyungan',
                'id_kecamatan' => $piyungan?->id ?? 12,
                'alamat_lengkap' => 'Jl. Wonosari, Piyungan',
                'tipe' => 'tradisional',
                'jml_pedagang' => 200,
                'jml_kios' => 50,
                'jml_los' => 100,
                'latitude' => -7.8567,
                'longitude' => 110.4123,
                'is_active' => true,
                'is_monitored' => true,
                'wajib_pantau' => true,
            ],
            [
                'nama_pasar' => 'Pasar Pundong',
                'id_kecamatan' => $pundong?->id ?? 4,
                'alamat_lengkap' => 'Jl. Parangtritis, Pundong',
                'tipe' => 'tradisional',
                'jml_pedagang' => 180,
                'jml_kios' => 40,
                'jml_los' => 90,
                'latitude' => -7.9456,
                'longitude' => 110.3567,
                'is_active' => true,
                'is_monitored' => true,
                'wajib_pantau' => true,
            ],
        ];

        foreach ($pasarList as $data) {
            Pasar::create($data);
        }
    }
}
