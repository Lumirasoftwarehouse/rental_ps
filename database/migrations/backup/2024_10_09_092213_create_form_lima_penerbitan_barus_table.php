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
        Schema::create('form_lima_penerbitan_barus', function (Blueprint $table) {
            $table->id();
            $table->string('skhpp')->nullable();
            $table->date('tanggal_awal_berlaku');
            $table->date('tanggal_akhir_berlaku');
            $table->enum('status', [
                '0', // proses
                '1', // disetujui
                '2' // ditolak
            ]);
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('form_empat_penerbitan_baru_id')->constrained('form_empat_penerbitan_barus')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_lima_penerbitan_barus');
    }
};
