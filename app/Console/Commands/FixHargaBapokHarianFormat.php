<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\HargaBapokHarian;
use Illuminate\Support\Facades\DB;

class FixHargaBapokHarianFormat extends Command
{
    protected $signature = 'fix:harga-format';
    protected $description = 'Migrasi data_harga di harga_bapok_harian agar semua value jadi array dengan harga dan tanggal';

    public function handle()
    {
        $bar = $this->output->createProgressBar(HargaBapokHarian::count());
        $bar->start();

        HargaBapokHarian::chunk(100, function ($rows) use ($bar) {
            foreach ($rows as $row) {
                $data = $row->data_harga;
                $changed = false;
                foreach ($data as $komoditasId => $value) {
                    if (!is_array($value)) {
                        $data[$komoditasId] = [
                            'harga' => $value,
                            'tanggal' => $row->tanggal instanceof \Carbon\Carbon ? $row->tanggal->toDateString() : $row->tanggal,
                        ];
                        $changed = true;
                    }
                }
                if ($changed) {
                    $row->data_harga = $data;
                    $row->save();
                }
                $bar->advance();
            }
        });
        $bar->finish();
        $this->info("\nMigrasi selesai. Semua data_harga sudah konsisten.");
    }
}
