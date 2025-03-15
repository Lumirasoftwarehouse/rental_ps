<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormDuaPenerbitanBaru extends Model
{
    use HasFactory;

    protected $fillable = [
        "jadwal_dip",
        "status",
        "catatan_revisi",
        "form_satu_penerbitan_baru_id",
    ];

    // Relasi ke formSatu
    public function formSatu()
    {
        return $this->belongsTo(FormSatuPenerbitanBaru::class, 'form_satu_penerbitan_baru_id');
    }

    // Relasi ke formTiga
    public function formTiga()
    {
        return $this->hasOne(FormTigaPenerbitanBaru::class, 'form_dua_penerbitan_baru_id');
    }
}
