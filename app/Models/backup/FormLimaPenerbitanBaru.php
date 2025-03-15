<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormLimaPenerbitanBaru extends Model
{
    use HasFactory;

    protected $fillable = [
        "skhpp",
        "tanggal_awal_berlaku",
        "tanggal_akhir_berlaku",
        "status",
        "catatan_revisi",
        "form_empat_penerbitan_baru_id",
    ];

    // Relasi ke formEmpat
    public function formEmpat()
    {
        return $this->belongsTo(FormEmpatPenerbitanBaru::class, 'form_empat_penerbitan_baru_id');
    }
}
