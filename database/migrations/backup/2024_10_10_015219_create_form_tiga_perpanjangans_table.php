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
        Schema::create('form_tiga_perpanjangans', function (Blueprint $table) {
            $table->id();
            $table->date('jadwal_survei');
            $table->enum('status', [
                '0', // proses
                '1', // disetujui
                '2' // ditolak
            ]);
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('form_dua_perpanjangan_id')->constrained('form_dua_perpanjangans')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_tiga_perpanjangans');
    }
};
