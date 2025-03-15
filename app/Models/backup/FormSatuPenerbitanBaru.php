<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSatuPenerbitanBaru extends Model
{
    use HasFactory;

    protected $fillable = [
        "surat_disadaau_diskonsau",
        "status",
        "catatan_revisi",
        "pic_perusahaan_litpers_id",
        "admin_litpers_id",
        "jenis_skhpp_id"
    ];

    // Relasi ke formDua
    public function formDua()
    {
        return $this->hasMany(FormDuaPenerbitanBaru::class, 'form_satu_penerbitan_baru_id');
    }
}
