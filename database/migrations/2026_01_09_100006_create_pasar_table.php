<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pasar', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pasar', 100);
            $table->foreignId('id_kecamatan')->constrained('ref_kecamatan')->onDelete('cascade');
            $table->foreignId('id_kalurahan')->nullable()->constrained('ref_kalurahan')->onDelete('set null');
            $table->foreignId('id_dusun')->nullable()->constrained('ref_dusun')->onDelete('set null');
            $table->text('alamat_lengkap')->nullable();
            $table->enum('tipe', ['tradisional', 'modern', 'campuran'])->default('tradisional');
            
            // Fasilitas Fisik
            $table->integer('jml_pedagang')->default(0);
            $table->integer('jml_kios')->default(0);
            $table->integer('jml_los')->default(0);
            $table->integer('jml_bango')->default(0);
            $table->integer('jml_kantor')->default(0);
            $table->integer('jml_mck')->default(0);
            $table->integer('jml_tps')->default(0);
            
            // Lokasi
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            
            // Kontrol
            $table->boolean('is_active')->default(true);
            $table->boolean('is_monitored')->default(true);
            $table->boolean('wajib_pantau')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pasar');
    }
};
