<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormEmpatPerpanjangan extends Model
{
    use HasFactory;

    protected $fillable = [
        "skhpp",
        "tanggal_awal_berlaku",
        "tanggal_akhir_berlaku",
        "status",
        "catatan_revisi",
        "form_tiga_perpanjangan_id",
    ];

    // Relasi ke formTiga
    public function formTiga()
    {
        return $this->belongsTo(FormTigaPerpanjangan::class, 'form_tiga_perpanjangan_id');
    }
}
