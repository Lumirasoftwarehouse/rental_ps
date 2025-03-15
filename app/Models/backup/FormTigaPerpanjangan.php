<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTigaPerpanjangan extends Model
{
    use HasFactory;

    protected $fillable = [
        "jadwal_survei",
        "status",
        "catatan_revisi",
        "form_dua_perpanjangan_id",
    ];

    // Relasi ke formDua
    public function formDua()
    {
        return $this->belongsTo(FormDuaPerpanjangan::class, 'form_dua_perpanjangan_id');
    }

    // Relasi ke formEmpat
    public function formEmpat()
    {
        return $this->hasOne(FormEmpatPerpanjangan::class, 'form_tiga_perpanjangan_id');
    }
}
