<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentNfa extends Model
{
    use HasFactory;

    protected $fillable = [
        "sertifikat_operator",
        "sertifikat_kelaludaraan",
        "sertifikat_pendaftaran",
        "izin_usaha",
        "permohonan_lanud_khusus",
        "sc_spam",
        "lain_lain",
        "rapid_antigen",
        "status",
        "catatan_revisi",
        "pengajuan_nfa_id",
    ];
}
