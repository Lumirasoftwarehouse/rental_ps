<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentUser extends Model
{
    use HasFactory;

    protected $fillable = [
        "sertifikat_operator",
        "flight_approval",
        "sertifikat_kelaludaraan",
        "sertifikat_pendaftaran",
        "izin_usaha",
        "permohonan_lanud_khusus",
        "fc_crew",
        "sertifikat_vaksin",
        "rapid_antigen",
        "user_id",
    ];
}
