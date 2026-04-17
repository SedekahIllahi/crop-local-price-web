<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_bapok_mingguan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasar')->constrained('pasar')->onDelete('cascade');
            $table->date('tanggal_pendataan');
            $table->tinyInteger('minggu_ke')->unsigned();
            $table->tinyInteger('bulan')->unsigned();
            $table->smallInteger('tahun')->unsigned();
            $table->json('data_stok')->comment('Format: {"komoditas_id": jumlah_stok}');
            $table->enum('status', ['draft', 'submitted', 'verified'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            
            // Index for weekly queries
            $table->index(['tahun', 'bulan', 'minggu_ke']);
            $table->index(['id_pasar', 'tahun', 'minggu_ke']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_bapok_mingguan');
    }
};
