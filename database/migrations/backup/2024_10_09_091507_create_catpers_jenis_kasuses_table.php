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
        Schema::create('catpers_jenis_kasuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catpers_id')->constrained('catpers')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('jenis_kasus_id')->constrained('jenis_kasuses')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catpers_jenis_kasuses');
    }
};
