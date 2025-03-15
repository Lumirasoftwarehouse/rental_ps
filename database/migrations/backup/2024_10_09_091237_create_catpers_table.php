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
        Schema::create('catpers', function (Blueprint $table) {
            $table->id();
            $table->text('foto_personil')->nullable();
            $table->text('nama_personil'); // Who
            $table->text('nrp_personil');
            $table->text('jabatan_personil');
            $table->text('kronologi_singkat'); // Result
            $table->date('tanggal'); // When
            $table->text('alasan_kejadian'); // Why
            $table->text('lokasi_kejadian'); // Where
            $table->text('cara_kejadian'); // How
            $table->text('sanksi_hukum')->nullable();
            // $table->text('foto_kejadian')->nullable();
            $table->foreignId('admin_lanud_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            // $table->foreignId('jenis_kasus_id')->constrained('jenis_kasuses')->onUpdate('cascade'); // What
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catpers');
    }
};
