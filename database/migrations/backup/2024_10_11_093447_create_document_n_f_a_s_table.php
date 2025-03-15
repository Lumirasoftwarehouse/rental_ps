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
        Schema::create('document_nfas', function (Blueprint $table) {
            $table->id();
            $table->string('sertifikat_operator');
            $table->string('sertifikat_kelaludaraan');
            $table->string('sertifikat_pendaftaran');
            $table->string('izin_usaha');
            $table->string('permohonan_lanud_khusus');
            $table->string('sc_spam');
            $table->string('lain_lain');
            $table->string('rapid_antigen');
            $table->enum('status', [
                '0', // proses
                '1', // disetujui
                '2' // ditolak
            ])->default('0');
            $table->string('catatan_revisi')->nullable();
            $table->foreignId('pengajuan_nfa_id')->constrained('pengajuan_nfas')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_nfas');
    }
};
