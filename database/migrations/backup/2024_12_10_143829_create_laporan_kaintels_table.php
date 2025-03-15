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
        Schema::create('laporan_kaintels', function (Blueprint $table) {
            $table->id();
            $table->text('jenis_pelanggaran');
            $table->text('pelanggaran_lainnya');
            $table->text('pelapor');
            $table->text('kontak_pelapor');
            $table->text('tanggal_pelanggaran');
            $table->text('lokasi_pelanggaran');
            $table->text('informasi_lainnya');
            $table->text('bukti_pelanggaran');
            $table->enum('status', [
                '0', // proses
                '1', // ditindak
                '2' // ditolak
            ])->default('0');
            $table->enum('jenis_pengajuan', [ // jenis pengajuan yang dilaporkan
                '0', // fa
                '1', // nfa
            ])->nullable();
            $table->text('catatan')->nullable();
            $table->string('id_pengajuan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_kaintels');
    }
};
