<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentFscsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_fscs', function (Blueprint $table) {
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
            $table->enum('status', [
                '0', // proses
                '1', // disetujui
                '2' // ditolak
            ])->default('0');
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('pengajuan_fsc_id')->constrained('pengajuan_fscs')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('document_fscs');
    }
}
