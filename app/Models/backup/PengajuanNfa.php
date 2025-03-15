<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanNfa extends Model
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
        'pendaratan_niaga',

        'nama_kapten_pilot',
        'awak_pesawat_lain',
        'penumpang_barang',
        'jumlah_kursi',
        'catatan',
        'fk_pic_perusahaan_nfa_id',
        'fk_pic_intelud_nfa_id',
        'created_at',
    ];
}
