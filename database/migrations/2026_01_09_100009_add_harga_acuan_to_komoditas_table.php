<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('komoditas', function (Blueprint $table) {
            $table->decimal('harga_acuan', 12, 2)->nullable()->after('satuan')
                  ->comment('Harga Eceran Tertinggi (HET) referensi pemerintah');
        });
    }

    public function down(): void
    {
        Schema::table('komoditas', function (Blueprint $table) {
            $table->dropColumn('harga_acuan');
        });
    }
};
