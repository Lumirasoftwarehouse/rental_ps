<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalSurveiPenerbitanBaru extends Model
{
    use HasFactory;

    protected $fillable = [
        "nama_admin",
        "tanggal_awal",
        "tanggal_akhir",
        "alamat_survei",
        "hp_admin",
        "admin_litpers_id",
        "f_tiga_penerbitan_id"
    ];
}
