<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormEmpatPenerbitanBaru extends Model
{
    use HasFactory;

    protected $fillable = [
        "jadwal_survei",
        "status",
        "catatan_revisi",
        "form_tiga_penerbitan_baru_id",
    ];

    // Relasi ke formTiga
    public function formTiga()
    {
        return $this->belongsTo(FormTigaPenerbitanBaru::class, 'form_tiga_penerbitan_baru_id');
    }

    // Relasi ke formLima
    public function formLima()
    {
        return $this->hasOne(FormLimaPenerbitanBaru::class, 'form_empat_penerbitan_baru_id');
    }
}
