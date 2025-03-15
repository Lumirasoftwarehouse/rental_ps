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
        Schema::create('document_users', function (Blueprint $table) {
            $table->id();
            $table->string('sertifikat_operator');
            $table->string('flight_approval');
            $table->string('sertifikat_kelaludaraan');
            $table->string('sertifikat_pendaftaran');
            $table->string('izin_usaha');
            $table->string('permohonan_lanud_khusus');
            $table->string('fc_crew');
            $table->string('sertifikat_vaksin');
            $table->string('rapid_antigen');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_users');
    }
};
