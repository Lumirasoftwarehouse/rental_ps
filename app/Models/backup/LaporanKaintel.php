<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanKaintel extends Model
{
    use HasFactory;

    protected $fillable = [
        'jenis_pelanggaran',
        'pelanggaran_lainnya',
        'pelapor',
        'kontak_pelapor',
        'tanggal_pelanggaran',
        'lokasi_pelanggaran',
        'informasi_lainnya',
        'bukti_pelanggaran',
        'status',
        'catatan',
        'jenis_pengajuan',
        'id_pengajuan',
    ];
}
