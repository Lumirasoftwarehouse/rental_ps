<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catpers extends Model
{
    use HasFactory;

    protected $fillable = [
        "foto_personil",
        "nama_personil",
        "nrp_personil",
        "jabatan_personil",
        "kronologi_singkat",
        "tanggal",
        "alasan_kejadian",
        "lokasi_kejadian",
        "cara_kejadian",
        "sanksi_hukum",
        // "foto_kejadian",
        "admin_lanud_id"
    ];

    public function fotoKejadian()
    {
        return $this->hasMany(CatpersFotoKejadian::class);
    }

    public function jenisKasus()
    {
        return $this->belongsToMany(JenisKasus::class, 'catpers_jenis_kasuses');
    }
}
