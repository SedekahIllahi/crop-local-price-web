<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('komoditas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komoditas', 100);
            $table->string('satuan', 20)->default('kg');
            $table->foreignId('kategori_id')->constrained('kategori_komoditas')->onDelete('cascade');
            $table->string('icon', 10)->nullable()->comment('Emoji icon');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('komoditas');
    }
};
