<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormTigaPergantianDireksi extends Model
{
    use HasFactory;

    protected $fillable = [
        "surat_permohonan_penerbitan",
        "akte_pendirian_perusahaan",
        "akte_perubahan_perusahaan",
        "nomor_izin_berusaha",
        "nomor_pokok_wajib_pajak",
        "surat_pengukuhan_pengusaha_kena_pajak",
        "surat_pernyataan_sehat",
        "referensi_bank",
        "neraca_perusahaan_terakhir",
        "rekening_koran_perusahaan",
        "cv_direktur_utama",
        "ktp_jajaran_direksi",
        "skck",
        "company_profile",
        "daftar_pengalaman_pekerjaan_di_tni_au",
        "daftar_peralatan_fasilitas_kantor",
        "aset_perusahaan",
        "surat_kemampuan_principle_agent",
        "surat_loa_poa",
        "supporting_letter_dari_vendor",
        "foto_direktur_4_6",
        "kepemilikan_kantor",
        "struktur_organisasi",
        "foto_perusahaan",
        "gambar_rute_denah_kantor",
        "kk_direktur_utama",
        "beranda_lpse",
        "e_catalog",
        "status",
        "catatan_revisi",
        "form_dua_pergantian_id",
    ];

    // Relasi ke formDua
    public function formDua()
    {
        return $this->belongsTo(FormDuaPergantianDireksi::class, 'form_dua_pergantian_id');
    }

    // Relasi ke formEmpat
    public function formEmpat()
    {
        return $this->hasOne(FormEmpatPergantianDireksi::class, 'form_tiga_pergantian_id');
    }
}
