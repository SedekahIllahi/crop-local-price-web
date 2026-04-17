<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(NavigationSeeder::class);
        $this->call(PreferenceSeeder::class);
        $this->call(UserSeeder::class);
        
        // SIGAPAN Seeders
        $this->call(KecamatanSeeder::class);
        $this->call(KategoriKomoditasSeeder::class);
        $this->call(KomoditasSeeder::class);
        $this->call(PasarSeeder::class);
        $this->call(HargaSampleSeeder::class);
    }
}
