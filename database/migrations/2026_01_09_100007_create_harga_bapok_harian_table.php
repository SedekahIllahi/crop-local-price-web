<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('harga_bapok_harian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_pasar')->constrained('pasar')->onDelete('cascade');
            $table->date('tanggal');
            $table->json('data_harga')->comment('Format: {"komoditas_id": harga}');
            $table->enum('status', ['draft', 'submitted', 'verified'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Integration fields
            $table->boolean('is_integrated')->default(false);
            $table->timestamp('integrated_at')->nullable();
            $table->text('integration_notes')->nullable();
            
            $table->timestamps();
            
            // Unique constraint: satu pasar hanya bisa punya satu data per tanggal
            $table->unique(['id_pasar', 'tanggal']);
            $table->index('tanggal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('harga_bapok_harian');
    }
};
