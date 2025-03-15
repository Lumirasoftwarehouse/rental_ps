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
        Schema::create('form_empat_perpanjangans', function (Blueprint $table) {
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
            $table->foreignId('form_tiga_perpanjangan_id')->constrained('form_tiga_perpanjangans')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_empat_perpanjangans');
    }
};
