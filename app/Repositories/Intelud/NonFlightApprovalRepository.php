<?php

namespace App\Repositories\Intelud;

use App\Models\PengajuanNfa;
use App\Models\DocumentNfa;
use App\Models\User;

class NonFlightApprovalRepository
{
    private $pengajuanNfaModel;

    public function __construct(PengajuanNfa $pengajuanNfaModel)
    {
        $this->pengajuanNfaModel = $pengajuanNfaModel;
    }

    public function listPengajuanApprovated()
    {
        return $this->pengajuanNfaModel::where('fk_pic_intelud_nfa_id', auth()->user()->id)->where('status', '1')->get();
    }

    public function listPengajuanRejected()
    {
        return $this->pengajuanNfaModel::where('fk_pic_intelud_nfa_id', auth()->user()->id)->where('status', '2')->get();
    }

    public function detailPengajuan($id)
    {
        return $this->pengajuanNfaModel::join('users AS perusahaan_users', 'pengajuan_nfas.fk_pic_perusahaan_nfa_id', '=', 'perusahaan_users.id')
            ->join('pic_perusahaan_fscs', 'perusahaan_users.id', '=', 'pic_perusahaan_fscs.user_id')
            ->leftJoin('users AS intelud_users', 'pengajuan_nfas.fk_pic_intelud_nfa_id', '=', 'intelud_users.id')
            ->leftJoin('pic_intelud_fscs', 'intelud_users.id', '=', 'pic_intelud_fscs.user_id')
            ->where('pengajuan_nfas.id', $id)
            ->select('pengajuan_nfas.*', 'pic_perusahaan_fscs.nama_pic', 'pic_perusahaan_fscs.hp_pic', 'pic_intelud_fscs.nama_pic AS pic_intelud')
            ->first();
    }

    public function postPengajuan(array $dataRequest)
    {
        try {
            $this->pengajuanNfaModel->create($dataRequest);
            return [
                'id' => '1',
                'data' => 'input data pengajuan nfa berhasil',
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'input data pengajuan nfa gagal: ' . $th->getMessage(),
            ];
        }
    }

    public function listPengajuanNfa()
    {
        return $this->pengajuanNfaModel::all();
    }

    public function listPengajuanNfaWhereNoPic()
    {
        return $this->pengajuanNfaModel::where('fk_pic_intelud_nfa_id', null)->get();
    }

    public function listPengajuanNfaByPicIntelud()
    {
        return $this->pengajuanNfaModel::where('fk_pic_intelud_nfa_id', auth()->user()->id)->where('status', '0')->get();
    }

    public function listPengajuanNfaByIdPic()
    {
        return $this->pengajuanNfaModel::where('fk_pic_perusahaan_nfa_id', auth()->user()->id)->get();
    }

    public function postFormEmpat(array $dataRequest)
    {
        try {
            // Simpan ke database, misalkan ke tabel `form_empats`
            $formEmpat = DocumentNfa::create([
                'sertifikat_operator' => $dataRequest['sertifikat_operator'],
                'sertifikat_kelaludaraan' => $dataRequest['sertifikat_kelaludaraan'],
                'sertifikat_pendaftaran' => $dataRequest['sertifikat_pendaftaran'],
                'izin_usaha' => $dataRequest['izin_usaha'],
                'permohonan_lanud_khusus' => $dataRequest['permohonan_lanud_khusus'],
                'sc_spam' => $dataRequest['sc_spam'],
                'lain_lain' => $dataRequest['lain_lain'],
                'rapid_antigen' => $dataRequest['rapid_antigen'],
                'pengajuan_nfa_id' => $dataRequest['pengajuan_nfa_id'],
            ]);

            return [
                'id' => '1',
                'data' => 'Penyimpanan dokumen berhasil',
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'Penyimpanan dokumen gagal: ' . $th->getMessage(),
            ];
        }
    }

    public function verifyFormSatu(array $dataRequest, $id)
    {
        try {
            $dataFormSatu = FormSatuNfa::find($id);
            if ($dataFormSatu) {
                $dataFormSatu->statusVerify = $dataRequest['statusVerify'];
                $dataFormSatu->catatan_revisi = $dataRequest['catatan_revisi'];
                $dataFormSatu->save();
                return [
                    'id' => '1',
                    'data' => 'verifikasi form satu berhasil'
                ];
            }
            return [
                'id' => '0',
                'data' => 'verifikasi form satu gagal'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'verifikasi form satu gagal'
            ];
        }
    }
    public function verifyFormDua(array $dataRequest, $id)
    {
        try {
            $dataFormDua = FormDuaNfa::find($id);
            if ($dataFormDua) {
                $dataFormDua->statusVerify = $dataRequest['statusVerify'];
                $dataFormDua->catatan_revisi = $dataRequest['catatan_revisi'];
                $dataFormDua->save();
                return [
                    'id' => '1',
                    'data' => 'verifikasi form dua berhasil'
                ];
            }
            return [
                'id' => '0',
                'data' => 'verifikasi form dua gagal'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'verifikasi form dua gagal'
            ];
        }
    }
    public function verifyFormTiga(array $dataRequest, $id)
    {
        try {
            $dataFormTiga = FormTigaNfa::find($id);
            if ($dataFormTiga) {
                $dataFormTiga->statusVerify = $dataRequest['statusVerify'];
                $dataFormTiga->catatan_revisi = $dataRequest['catatan_revisi'];
                $dataFormTiga->save();
                return [
                    'id' => '1',
                    'data' => 'verifikasi form tiga berhasil'
                ];
            }
            return [
                'id' => '0',
                'data' => 'verifikasi form tiga gagal'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'verifikasi form tiga gagal'
            ];
        }
    }

    public function sendToPic(array $dataRequest)
    {
        try {
            $data = $this->pengajuanNfaModel->find($dataRequest['id_pengajuan']);
            $data->fk_pic_intelud_nfa_id = $dataRequest['id_pic'];
            $data->save();
            return [
                'id' => '1',
                'data' => $data,
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'terjadi kesalahan dalam mengambil detail pengajuan',
            ];
        }
    }

    public function verifyDocumentNfa($dataRequest, $id)
    {
        try {
            $dataDocumentNfa = DocumentNfa::find($id);
            if ($dataDocumentNfa) {
                $dataDocumentNfa->statusVerify = $dataRequest['statusVerify'];
                $dataDocumentNfa->catatan_revisi = $dataRequest['catatan_revisi'];
                $dataDocumentNfa->save();
                return [
                    'id' => '1',
                    'data' => 'verifikasi document fsc berhasil'
                ];
            }
            return [
                'id' => '0',
                'data' => 'verifikasi document fsc gagal'
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'verifikasi document fsc gagal'
            ];
        }
    }

    public function cekLevelUser()
    {
        return User::where('id', auth()->user()->id)->value('level');
    }
}
