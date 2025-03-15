<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormDuaPergantianDireksi extends Model
{
    use HasFactory;

    protected $fillable = [
        "jadwal_dip",
        "status",
        "catatan_revisi",
        "form_satu_pergantian_id",
    ];

    // Relasi ke formSatu
    public function formSatu()
    {
        return $this->belongsTo(FormSatuPergantianDireksi::class, 'form_satu_pergantian_id');
    }

    // Relasi ke formTiga
    public function formTiga()
    {
        return $this->hasOne(FormTigaPergantianDireksi::class, 'form_dua_pergantian_id');
    }
}
