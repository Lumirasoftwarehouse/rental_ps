<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSatuPergantianDireksi extends Model
{
    use HasFactory;

    protected $fillable = [
        "surat_disadaau_diskonsau",
        "skhpp_lama",
        "status",
        "catatan_revisi",
        "pic_perusahaan_litpers_id",
        "admin_litpers_id",
        "jenis_skhpp_id"
    ];

    // Relasi ke formDua
    public function formDua()
    {
        return $this->hasMany(FormDuaPergantianDireksi::class, 'form_satu_pergantian_id');
    }
}
