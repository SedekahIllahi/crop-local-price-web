<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ref_kecamatan', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kemendagri', 20)->nullable();
            $table->string('kode_bps', 20)->nullable();
            $table->string('nama', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ref_kecamatan');
    }
};
