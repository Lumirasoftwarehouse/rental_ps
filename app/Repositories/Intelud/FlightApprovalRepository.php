<?php

namespace App\Repositories\Intelud;

use App\Models\PengajuanFsc;
use App\Models\PengajuanNfa;
use App\Models\DocumentFsc;
use App\Models\User;

class FlightApprovalRepository
{
    private $pengajuanFscModel;

    public function __construct(PengajuanFsc $pengajuanFscModel)
    {
        $this->pengajuanFscModel = $pengajuanFscModel;
    }

    public function listPengajuanApprovated()
    {
        return $this->pengajuanFscModel::where('fk_pic_intelud_nfa_id', auth()->user()->id)->where('status', '1')->get();
    }

    public function listPengajuanRejected()
    {
        return $this->pengajuanFscModel::where('fk_pic_intelud_nfa_id', auth()->user()->id)->where('status', '2')->get();
    }

    public function listPengajuan()
    {
        return $this->pengajuanFscModel::all();
    }

    public function listPengajuanWhereNoPic()
    {
        return $this->pengajuanFscModel::where('fk_pic_intelud_nfc_id', null)->get();
    }

    public function listPengajuanFaByPicIntelud()
    {
        return $this->pengajuanFscModel::where('fk_pic_intelud_nfc_id', auth()->user()->id)->where('status', '0')->get();
    }

    public function listPengajuanFaByIdPic()
    {
        return $this->pengajuanFscModel::where('fk_pic_perusahaan_nfc_id', auth()->user()->id)->get();
    }

    public function detailPengajuan($id)
    {
        return $this->pengajuanFscModel::join('users AS perusahaan_users', 'pengajuan_fscs.fk_pic_perusahaan_nfc_id', '=', 'perusahaan_users.id')
            ->join('pic_perusahaan_fscs', 'perusahaan_users.id', '=', 'pic_perusahaan_fscs.user_id')
            ->leftJoin('users AS intelud_users', 'pengajuan_fscs.fk_pic_intelud_nfc_id', '=', 'intelud_users.id')
            ->leftJoin('pic_intelud_fscs', 'intelud_users.id', '=', 'pic_intelud_fscs.user_id')
            ->where('pengajuan_fscs.id', $id)
            ->select('pengajuan_fscs.*', 'pic_perusahaan_fscs.nama_pic', 'pic_perusahaan_fscs.hp_pic', 'pic_intelud_fscs.nama_pic AS pic_intelud')
            ->first();
    }

    public function detailDocumentPengajuan($id)
    {
        return DocumentFsc::find($id);
    }

    public function postPengajuan(array $dataRequest)
    {
        try {
            $this->pengajuanFscModel->create($dataRequest);
            return [
                'id' => '1',
                'data' => 'Input data form 1 berhasil',
            ];
        } catch (\Throwable $th) {
            return [
                'id' => '0',
                'data' => 'Input data form 1 gagal: ' . $th->getMessage(),
            ];
        }
    }

    public function postFormEmpat(array $dataRequest)
    {
        try {
            // Simpan ke database, misalkan ke tabel `form_empats`
            $formEmpat = DocumentFsc::create([
                'sertifikat_operator' => $dataRequest['sertifikat_operator'],
                'flight_approval' => $dataRequest['flight_approval'],
                'sertifikat_kelaludaraan' => $dataRequest['sertifikat_kelaludaraan'],
                'sertifikat_pendaftaran' => $dataRequest['sertifikat_pendaftaran'],
                'izin_usaha' => $dataRequest['izin_usaha'],
                'permohonan_lanud_khusus' => $dataRequest['permohonan_lanud_khusus'],
                'fc_crew' => $dataRequest['fc_crew'],
                'sertifikat_vaksin' => $dataRequest['sertifikat_vaksin'],
                'rapid_antigen' => $dataRequest['rapid_antigen'],
                'pengajuan_fsc_id' => $dataRequest['pengajuan_fsc_id'],
            ]);

            return [
                'id' => '1', // Kembalikan ID dari data yang baru disimpan
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
            $dataFormSatu = FormSatuFsc::find($id);
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
            $dataFormDua = FormDuaFsc::find($id);
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

    public function sendToPic(array $dataRequest)
    {
        try {
            $data = $this->pengajuanFscModel->find($dataRequest['id_pengajuan']);
            $data->fk_pic_intelud_nfc_id = $dataRequest['id_pic'];
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


    public function verifyFormTiga(array $dataRequest, $id)
    {
        try {
            $dataFormTiga = FormTigaFsc::find($id);
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

    public function verifyDocumentFsc($dataRequest, $id)
    {
        try {
            $dataDocumentFsc = DocumentFsc::find($id);
            if ($dataDocumentFsc) {
                $dataDocumentFsc->statusVerify = $dataRequest['statusVerify'];
                $dataDocumentFsc->catatan_revisi = $dataRequest['catatan_revisi'];
                $dataDocumentFsc->save();
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

    public function rejectPengajuan($dataRequest, $id)
    {
        if ($dataRequest['jenis_pengajuan'] == "FA") {
            $data = $this->pengajuanFscModel->find($id);
            $data->status = '2';
            $data->save();
            return [
                'id' => '1',
                'data' => 'decline pengajuan berhasil',
            ];
        } else if ($dataRequest['jenis_pengajuan'] == "NFA") {
            $data = PengajuanNfa::find($id);
            $data->status = '2';
            $data->save();
            return [
                'id' => '1',
                'data' => 'decline pengajuan berhasil',
            ];
        }
        return [
            'id' => '0',
            'data' => 'decline pengajuan berhasil',
        ];
    }
}
