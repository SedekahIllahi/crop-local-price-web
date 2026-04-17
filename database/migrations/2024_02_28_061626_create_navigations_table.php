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
        Schema::create('navigations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->comment('digunakan sebagai identifier pada permisson');
            $table->enum('page', ['admin', 'landing'])->default('admin');
            $table->string('url');
            $table->string('icon')->nullable();
            $table->integer('order')->nullable();
            $table->integer('parent_id')->nullable();
            $table->integer('active')->nullable();
            $table->integer('display')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('navigations');
    }
};
