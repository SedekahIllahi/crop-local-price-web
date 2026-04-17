<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('komoditas', function (Blueprint $table) {
            // Check if 'images' column exists and make it nullable
            if (Schema::hasColumn('komoditas', 'images')) {
                $table->string('images')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('komoditas', function (Blueprint $table) {
            //
        });
    }
};
