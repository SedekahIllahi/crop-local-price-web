<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'group' => 'site',
                'name' => 'app_name',
                'is_asset' => false,
                'value' => 'SIGAPAN',
            ],
            [
                'group' => 'site',
                'name' => 'title',
                'is_asset' => false,
                'value' => 'SIGAPAN',
            ],
            [
                'group' => 'site',
                'name' => 'copyright',
                'is_asset' => false,
                'value' => '&copy; Copyright <strong><span>SIGAPAN</span></strong>. All Rights Reserved',
            ],
            [
                'group' => 'site',
                'name' => 'credits',
                'is_asset' => false,
                'value' => 'Designed by <a href="https://my_project.com/">SIGAPAN</a>',
            ],
            [
                'group' => 'site',
                'name' => 'logo',
                'is_asset' => true,
                'value' => 'assets/all-pages/images/logo/logo-icon.svg',
            ],
            [
                'group' => 'site',
                'name' => 'favicon',
                'is_asset' => true,
                'value' => 'assets/all-pages/images/logo/favicon.ico',
            ]
        ];
        
        // Loop data untuk mengecek apakah sudah ada atau belum
        foreach ($data as $item) {
            DB::table('preferences')->updateOrInsert(
                ['name' => $item['name']], // Kolom yang dicek (unik)
                $item                      // Data yang dimasukkan/diperbarui
            );
        }
    }
}