<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormEmpatPergantianDireksi extends Model
{
    use HasFactory;

    protected $fillable = [
        "jadwal_survei",
        "status",
        "catatan_revisi",
        "form_tiga_pergantian_id",
    ];

    // Relasi ke formTiga
    public function formTiga()
    {
        return $this->belongsTo(FormTigaPergantianDireksi::class, 'form_tiga_pergantian_id');
    }

    // Relasi ke formLima
    public function formLima()
    {
        return $this->hasOne(FormLimaPergantianDireksi::class, 'form_empat_pergantian_id');
    }
}
