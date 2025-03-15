<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanFsc extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_perusahaan',
        'operator',
        'jenis',
        'no_registrasi',

        'tanggal_terbang',
        'tanggal_mendarat',
        'rute_penerbangan',
        'lanud',
        'pendaratan_teknik',
        'pendaratan_niaga',
        
        'nama_kapten_pilot',
        'awak_pesawat_lain',
        'penumpang_barang',
        'jumlah_kursi',
        'fa',
        'catatan',
        
        'status',
        'catatan_revisi',
        'file',
        'fk_pic_perusahaan_nfc_id',
        'fk_pic_intelud_nfc_id',
        'created_at',
    ];
}
