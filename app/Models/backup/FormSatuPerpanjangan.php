<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormSatuPerpanjangan extends Model
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
        return $this->hasMany(FormDuaPerpanjangan::class, 'form_satu_perpanjangan_id');
    }
}
