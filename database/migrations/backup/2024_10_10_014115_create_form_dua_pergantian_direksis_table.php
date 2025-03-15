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
        Schema::create('form_dua_pergantian_direksis', function (Blueprint $table) {
            $table->id();
            $table->date('jadwal_dip');
            $table->enum('status', [
                '0', // proses
                '1', // disetujui
                '2' // ditolak
            ]);
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('form_satu_pergantian_id')->constrained('form_satu_pergantian_direksis')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_dua_pergantian_direksis');
    }
};
