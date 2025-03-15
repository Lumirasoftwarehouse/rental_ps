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
        Schema::create('form_satu_perpanjangans', function (Blueprint $table) {
            $table->id();
            $table->string('surat_disadaau_diskonsau');
            $table->string('skhpp_lama');
            $table->enum('status', [
                '0', // proses
                '1', // disetujui
                '2' // ditolak
            ]);
            $table->text('catatan_revisi')->nullable();
            $table->foreignId('pic_perusahaan_litpers_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('admin_litpers_id')->nullable()->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('jenis_skhpp_id')->constrained('jenis_skhpps')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_satu_perpanjangans');
    }
};
