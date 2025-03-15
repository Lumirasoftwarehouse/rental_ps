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
        Schema::create('jadwal_survei_perpanjangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_admin');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->string('alamat_survei');
            $table->string('hp_admin');
            $table->foreignId('admin_litpers_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('f_dua_perpanjangan_id')->constrained('form_dua_perpanjangans')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_survei_perpanjangans');
    }
};
