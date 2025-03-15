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
        Schema::create('jadwal_survei_penerbitan_barus', function (Blueprint $table) {
            $table->id();
            $table->string('nama_admin');
            $table->date('tanggal_awal');
            $table->date('tanggal_akhir');
            $table->string('alamat_survei');
            $table->string('hp_admin');
            $table->foreignId('admin_litpers_id')->constrained('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('f_tiga_penerbitan_id')->constrained('form_tiga_penerbitan_barus')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_survei_penerbitan_barus');
    }
};
