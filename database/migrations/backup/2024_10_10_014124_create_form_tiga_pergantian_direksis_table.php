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
        Schema::create('form_tiga_pergantian_direksis', function (Blueprint $table) {
            $table->id();
            $table->string('surat_permohonan_penerbitan')->nullable();
            $table->string('akte_pendirian_perusahaan')->nullable();
            $table->string('akte_perubahan_perusahaan')->nullable();
            $table->string('nomor_izin_berusaha')->nullable();
            $table->string('nomor_pokok_wajib_pajak')->nullable();
            $table->string('surat_pengukuhan_pengusaha_kena_pajak')->nullable();
            $table->string('surat_pernyataan_sehat')->nullable();
            $table->string('referensi_bank')->nullable();
            $table->string('neraca_perusahaan_terakhir')->nullable();
            $table->string('rekening_koran_perusahaan')->nullable();
            $table->string('cv_direktur_utama')->nullable();
            $table->string('ktp_jajaran_direksi')->nullable();
            $table->string('skck')->nullable();
            $table->string('company_profile')->nullable();
            $table->string('daftar_pengalaman_pekerjaan_di_tni_au')->nullable();
            $table->string('daftar_peralatan_fasilitas_kantor')->nullable();
            $table->string('aset_perusahaan')->nullable();
            $table->string('surat_kemampuan_principle_agent')->nullable();
            $table->string('surat_loa_poa')->nullable();
            $table->string('supporting_letter_dari_vendor')->nullable();
            $table->string('foto_direktur_4_6')->nullable();
            $table->string('kepemilikan_kantor')->nullable();
            $table->string('struktur_organisasi')->nullable();
            $table->string('foto_perusahaan')->nullable();
            $table->string('gambar_rute_denah_kantor')->nullable();
            $table->string('kk_direktur_utama')->nullable();
            $table->string('beranda_lpse')->nullable();
            $table->string('e_catalog')->nullable();
            $table->enum('status', [
                '0', // proses
                '1', // disetujui
                '2' // ditolak
            ]);
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('form_dua_pergantian_id')->constrained('form_dua_pergantian_direksis')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_tiga_pergantian_direksis');
    }
};
