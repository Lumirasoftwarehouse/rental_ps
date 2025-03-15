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
        Schema::create('pengajuan_fscs', function (Blueprint $table) {
            $table->id();
            $table->text('nama_perusahaan');
            $table->text('operator');
            $table->text('jenis');
            $table->text('no_registrasi');

            $table->text('tanggal_terbang');
            $table->text('tanggal_mendarat');
            $table->text('rute_penerbangan');
            $table->text('lanud');
            $table->text('pendaratan_teknik');
            $table->text('pendaratan_niaga');

            $table->text('nama_kapten_pilot');
            $table->text('awak_pesawat_lain');
            $table->text('penumpang_barang');
            $table->text('jumlah_kursi');
            $table->text('fa');
            $table->text('catatan');

            $table->enum('status', [
                '0', // proses
                '1', // disetujui
                '2' // ditolak
            ])->default('0');
            $table->string('file')->nullable();
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('fk_pic_perusahaan_nfc_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('fk_pic_intelud_nfc_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_fscs');
    }
};
