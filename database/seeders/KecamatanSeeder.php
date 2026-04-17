<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RefKecamatan;

class KecamatanSeeder extends Seeder
{
    public function run(): void
    {
        $kecamatan = [
            ['kode_kemendagri' => '3402010', 'kode_bps' => '3402010', 'nama' => 'Srandakan'],
            ['kode_kemendagri' => '3402020', 'kode_bps' => '3402020', 'nama' => 'Sanden'],
            ['kode_kemendagri' => '3402030', 'kode_bps' => '3402030', 'nama' => 'Kretek'],
            ['kode_kemendagri' => '3402040', 'kode_bps' => '3402040', 'nama' => 'Pundong'],
            ['kode_kemendagri' => '3402050', 'kode_bps' => '3402050', 'nama' => 'Bambanglipuro'],
            ['kode_kemendagri' => '3402060', 'kode_bps' => '3402060', 'nama' => 'Pandak'],
            ['kode_kemendagri' => '3402070', 'kode_bps' => '3402070', 'nama' => 'Bantul'],
            ['kode_kemendagri' => '3402080', 'kode_bps' => '3402080', 'nama' => 'Jetis'],
            ['kode_kemendagri' => '3402090', 'kode_bps' => '3402090', 'nama' => 'Imogiri'],
            ['kode_kemendagri' => '3402100', 'kode_bps' => '3402100', 'nama' => 'Dlingo'],
            ['kode_kemendagri' => '3402110', 'kode_bps' => '3402110', 'nama' => 'Pleret'],
            ['kode_kemendagri' => '3402120', 'kode_bps' => '3402120', 'nama' => 'Piyungan'],
            ['kode_kemendagri' => '3402130', 'kode_bps' => '3402130', 'nama' => 'Banguntapan'],
            ['kode_kemendagri' => '3402140', 'kode_bps' => '3402140', 'nama' => 'Sewon'],
            ['kode_kemendagri' => '3402150', 'kode_bps' => '3402150', 'nama' => 'Kasihan'],
            ['kode_kemendagri' => '3402160', 'kode_bps' => '3402160', 'nama' => 'Pajangan'],
            ['kode_kemendagri' => '3402170', 'kode_bps' => '3402170', 'nama' => 'Sedayu'],
        ];

        foreach ($kecamatan as $data) {
            RefKecamatan::create($data);
        }
    }
}
