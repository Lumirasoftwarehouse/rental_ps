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
        Schema::create('admin_lanuds', function (Blueprint $table) {
            $table->id();
            $table->string('nama_satuan');
            $table->string('lokasi_satuan');
            $table->string('nama_kepala_satuan');
            $table->string('nama_admin');
            $table->string('jabatan_admin');
            $table->string('hp_admin');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_lanuds');
    }
};
