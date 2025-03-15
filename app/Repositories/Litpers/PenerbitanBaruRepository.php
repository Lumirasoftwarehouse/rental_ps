<?php

namespace App\Repositories\Litpers;

use App\Models\FormSatuPenerbitanBaru;
use App\Models\FormDuaPenerbitanBaru;
use App\Models\FormTigaPenerbitanBaru;
use App\Models\FormEmpatPenerbitanBaru;
use App\Models\FormLimaPenerbitanBaru;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PenerbitanBaruRepository
{
    private $formSatuPenerbitanBaruModel;
    private $formDuaPenerbitanBaruModel;
    private $formTigaPenerbitanBaruModel;
    private $formEmpatPenerbitanBaruModel;
    private $formLimaPenerbitanBaruModel;

    public function __construct(FormSatuPenerbitanBaru $formSatuPenerbitanBaruModel, FormDuaPenerbitanBaru $formDuaPenerbitanBaruModel, FormTigaPenerbitanBaru $formTigaPenerbitanBaruModel, FormEmpatPenerbitanBaru $formEmpatPenerbitanBaruModel, FormLimaPenerbitanBaru $formLimaPenerbitanBaruModel)
    {
        $this->formSatuPenerbitanBaruModel = $formSatuPenerbitanBaruModel;
        $this->formDuaPenerbitanBaruModel = $formDuaPenerbitanBaruModel;
        $this->formTigaPenerbitanBaruModel = $formTigaPenerbitanBaruModel;
        $this->formEmpatPenerbitanBaruModel = $formEmpatPenerbitanBaruModel;
        $this->formLimaPenerbitanBaruModel = $formLimaPenerbitanBaruModel;
    }

    public function listDataAllFormPenerbitanBaruByMitra($id)
    {
        try {
            $pengajuanBelumSelesai = $this->formSatuPenerbitanBaruModel->where('pic_perusahaan_litpers_id', $id)
                ->with(['formDua.formTiga.formEmpat.formLima'])
                ->get();

            $response = [];

            foreach ($pengajuanBelumSelesai as $formSatu) {
                if ($formSatu->formDua->isEmpty()) {
                    // Progress is 1 if only formSatu exists
                    $response[] = [
                        'progress' => 1,
                        'id' => $formSatu->id,
                        'status' => $formSatu->status,
                    ];
                } else {
                    foreach ($formSatu->formDua as $formDua) {
                        if (is_null($formDua->formTiga)) {
                            // Progress is 2 if formDua exists but formTiga is null
                            $response[] = [
                                'progress' => 2,
                                'id' => $formDua->id,
                                'status' => $formDua->status,
                            ];
                        } else {
                            if (is_null($formDua->formTiga->formEmpat)) {
                                // Progress is 3 if formTiga exists but formEmpat is null
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                ];
                            } else {
                                if (is_null($formDua->formTiga->formEmpat->formLima)) {
                                    $response[] = [
                                        'progress' => 4,
                                        'id' => $formDua->formTiga->formEmpat->id,
                                        'status' => $formDua->formTiga->formEmpat->status,
                                    ];
                                } else {
                                    if ($formDua->formTiga->formEmpat->formLima != null) {
                                        $response[] = [
                                            'progress' => 5,
                                            'id' => $formDua->formTiga->formEmpat->formLima->form_empat_penerbitan_baru_id,
                                            'status' => $formDua->formTiga->formEmpat->formLima->status,
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($response)) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $response,
                    "message" => 'Progress and related IDs for all form submissions retrieved successfully.'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'No form submissions found.'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataUnfinishedFormPenerbitanBaruByMitra($id)
    {
        try {
            $pengajuanBelumSelesai = $this->formSatuPenerbitanBaruModel->where('pic_perusahaan_litpers_id', $id)
                ->with(['formDua.formTiga.formEmpat.formLima'])
                ->get();

            $response = [];

            foreach ($pengajuanBelumSelesai as $formSatu) {
                if ($formSatu->formDua->isEmpty()) {
                    // Progress is 1 if only formSatu exists
                    $response[] = [
                        'progress' => 1,
                        'id' => $formSatu->id,
                        'status' => $formSatu->status,
                    ];
                } else {
                    foreach ($formSatu->formDua as $formDua) {
                        if (is_null($formDua->formTiga)) {
                            // Progress is 2 if formDua exists but formTiga is null
                            $response[] = [
                                'progress' => 2,
                                'id' => $formDua->id,
                                'status' => $formDua->status,
                            ];
                        } else {
                            if (is_null($formDua->formTiga->formEmpat)) {
                                // Progress is 3 if formTiga exists but formEmpat is null
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                ];
                            } else {
                                if (is_null($formDua->formTiga->formEmpat->formLima)) {
                                    $response[] = [
                                        'progress' => 4,
                                        'id' => $formDua->formTiga->formEmpat->id,
                                        'status' => $formDua->formTiga->formEmpat->status,
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($response)) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $response,
                    "message" => 'Progress and related IDs for incomplete form submissions retrieved successfully.'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'No incomplete form submissions found.'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataUnvalidatedFormPenerbitanBaru()
    {
        try {
            $pengajuanBelumTervalidasi = $this->formSatuPenerbitanBaruModel
                ->with(['formDua.formTiga.formEmpat.formLima'])
                ->get();

            $response = [];

            foreach ($pengajuanBelumTervalidasi as $formSatu) {
                if ($formSatu->formDua->isEmpty() && $formSatu->status == 0) {
                    // Progress is 1 if only formSatu exists
                    $response[] = [
                        'progress' => 1,
                        'id' => $formSatu->id,
                        'status' => $formSatu->status,
                    ];
                } else {
                    foreach ($formSatu->formDua as $formDua) {
                        if (is_null($formDua->formTiga) && $formDua->status == 0) {
                            // Progress is 2 if formDua exists but formTiga is null
                            $response[] = [
                                'progress' => 2,
                                'id' => $formDua->id,
                                'status' => $formDua->status,
                            ];
                        } else {
                            if (is_null($formDua->formTiga->formEmpat) && $formDua->formTiga->status == 0) {
                                // Progress is 3 if formTiga exists but formEmpat is null
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                ];
                            } else {
                                if (is_null($formDua->formTiga->formEmpat->formLima) && $formDua->formTiga->formEmpat->status == 0) {
                                    $response[] = [
                                        'progress' => 4,
                                        'id' => $formDua->formTiga->formEmpat->id,
                                        'status' => $formDua->formTiga->formEmpat->status,
                                    ];
                                }
                            }
                        }
                    }
                }
            }

            if (!empty($response)) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $response,
                    "message" => 'Progress and related IDs for unvalidated form submissions retrieved successfully.'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'No unvalidated form submissions found.'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Satu

    public function listDataFormSatuPenerbitanBaru()
    {
        try {
            $dataFormSatuPenerbitanBaru = $this->formSatuPenerbitanBaruModel->get();
            if ($dataFormSatuPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormSatuPenerbitanBaru,
                    "message" => 'get data form satu penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form satu penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataFormSatuPenerbitanBaruByAdmin($id)
    {
        try {
            $dataFormSatuPenerbitanBaru = $this->formSatuPenerbitanBaruModel->where('admin_litpers_id', $id)->get();
            if ($dataFormSatuPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormSatuPenerbitanBaru,
                    "message" => 'get data form satu penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form satu penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataFormSatuPenerbitanBaruByMitra($id)
    {
        try {
            $dataFormSatuPenerbitanBaru = $this->formSatuPenerbitanBaruModel->where('pic_perusahaan_litpers_id', $id)->get();
            if ($dataFormSatuPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormSatuPenerbitanBaru,
                    "message" => 'get data form satu penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form satu penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataFormSatuPenerbitanBaru($id)
    {
        try {
            $dataFormSatuPenerbitanBaru = $this->formSatuPenerbitanBaruModel->find($id);
            if ($dataFormSatuPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'surat_disadaau_diskonsau' => $dataFormSatuPenerbitanBaru->surat_disadaau_diskonsau,
                        'status' => $dataFormSatuPenerbitanBaru->status,
                        'catatan_revisi' => $dataFormSatuPenerbitanBaru->catatan_revisi,
                        'pic_perusahaan_litpers_id' => $dataFormSatuPenerbitanBaru->pic_perusahaan_litpers_id,
                        'admin_litpers_id' => $dataFormSatuPenerbitanBaru->admin_litpers_id,
                        'jenis_skhpp_id' => $dataFormSatuPenerbitanBaru->jenis_skhpp_id,
                        'created_at' => $dataFormSatuPenerbitanBaru->created_at,
                        'updated_at' => $dataFormSatuPenerbitanBaru->updated_at,
                        'files' => [
                            'surat_disadaau_diskonsau' => $dataFormSatuPenerbitanBaru->surat_disadaau_diskonsau,
                        ]
                    ],
                    "message" => 'get detail data form satu penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form satu penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormSatuPenerbitanBaru(array $dataRequest)
    {
        try {
            $result = $this->formSatuPenerbitanBaruModel->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form satu penerbitan baru success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormSatuPenerbitanBaru($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormSatuPenerbitanBaru = $this->formSatuPenerbitanBaruModel->find($id);
            $dataFormSatuPenerbitanBaru->surat_disadaau_diskonsau = $dataRequest['surat_disadaau_diskonsau'];
            $dataFormSatuPenerbitanBaru->status = $dataRequest['status'];
            $dataFormSatuPenerbitanBaru->jenis_skhpp_id = $dataRequest['jenis_skhpp_id'];
            $dataFormSatuPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form satu penerbitan baru success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormSatuPenerbitanBaru($id)
    {
        try {
            $dataFormSatuPenerbitanBaru = $this->formSatuPenerbitanBaruModel->find($id);
            if ($dataFormSatuPenerbitanBaru) {
                $dataFormSatuPenerbitanBaru->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form satu penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form satu penerbitan baru tidak ditemukan'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Dua

    public function listDataFormDuaPenerbitanBaru()
    {
        try {
            $dataFormDuaPenerbitanBaru = $this->formDuaPenerbitanBaruModel->get();
            if ($dataFormDuaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormDuaPenerbitanBaru,
                    "message" => 'get data form dua penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form dua penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataFormDuaPenerbitanBaruByFormSatu($id)
    {
        try {
            $dataFormDuaPenerbitanBaru = $this->formDuaPenerbitanBaruModel->where('form_satu_penerbitan_baru_id', $id)->get();
            if ($dataFormDuaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormDuaPenerbitanBaru,
                    "message" => 'get data form dua penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form dua penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataFormDuaPenerbitanBaru($id)
    {
        try {
            $dataFormDuaPenerbitanBaru = $this->formDuaPenerbitanBaruModel->find($id);
            if ($dataFormDuaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormDuaPenerbitanBaru,
                    "message" => 'get detail data form dua penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form dua penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormDuaPenerbitanBaru($dataRequest)
    {
        try {
            $result = $this->formDuaPenerbitanBaruModel->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form dua penerbitan baru success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormDuaPenerbitanBaru($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPenerbitanBaru = $this->formDuaPenerbitanBaruModel->find($id);
            $dataFormDuaPenerbitanBaru->jadwal_dip = $dataRequest['jadwal_dip'];
            $dataFormDuaPenerbitanBaru->status = $dataRequest['status'];
            $dataFormDuaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form dua penerbitan baru success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormDuaPenerbitanBaru($id)
    {
        try {
            $dataFormDuaPenerbitanBaru = $this->formDuaPenerbitanBaruModel->find($id);
            if ($dataFormDuaPenerbitanBaru) {
                $dataFormDuaPenerbitanBaru->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form dua penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form dua penerbitan baru tidak ditemukan'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Tiga

    public function listDataFormTigaPenerbitanBaru()
    {
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->get();
            if ($dataFormTigaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormTigaPenerbitanBaru,
                    "message" => 'get data form tiga penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form tiga penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataFormTigaPenerbitanBaruByFormDua($id)
    {
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->where('form_dua_penerbitan_baru_id', $id)->get();
            if ($dataFormTigaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormTigaPenerbitanBaru,
                    "message" => 'get data form tiga penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form tiga penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataFormTigaPenerbitanBaru($id)
    {
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            if ($dataFormTigaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'surat_permohonan_penerbitan' => $dataFormTigaPenerbitanBaru->surat_permohonan_penerbitan,
                        'akte_pendirian_perusahaan' => $dataFormTigaPenerbitanBaru->akte_pendirian_perusahaan,
                        'akte_perubahan_perusahaan' => $dataFormTigaPenerbitanBaru->akte_perubahan_perusahaan,
                        'nomor_izin_berusaha' => $dataFormTigaPenerbitanBaru->nomor_izin_berusaha,
                        'nomor_pokok_wajib_pajak' => $dataFormTigaPenerbitanBaru->nomor_pokok_wajib_pajak,
                        'surat_pengukuhan_pengusaha_kena_pajak' => $dataFormTigaPenerbitanBaru->surat_pengukuhan_pengusaha_kena_pajak,
                        'surat_pernyataan_sehat' => $dataFormTigaPenerbitanBaru->surat_pernyataan_sehat,
                        'referensi_bank' => $dataFormTigaPenerbitanBaru->referensi_bank,
                        'neraca_perusahaan_terakhir' => $dataFormTigaPenerbitanBaru->neraca_perusahaan_terakhir,
                        'rekening_koran_perusahaan' => $dataFormTigaPenerbitanBaru->rekening_koran_perusahaan,
                        'cv_direktur_utama' => $dataFormTigaPenerbitanBaru->cv_direktur_utama,
                        'ktp_jajaran_direksi' => $dataFormTigaPenerbitanBaru->ktp_jajaran_direksi,
                        'skck' => $dataFormTigaPenerbitanBaru->skck,
                        'company_profile' => $dataFormTigaPenerbitanBaru->company_profile,
                        'daftar_pengalaman_pekerjaan_di_tni_au' => $dataFormTigaPenerbitanBaru->daftar_pengalaman_pekerjaan_di_tni_au,
                        'daftar_peralatan_fasilitas_kantor' => $dataFormTigaPenerbitanBaru->daftar_peralatan_fasilitas_kantor,
                        'aset_perusahaan' => $dataFormTigaPenerbitanBaru->aset_perusahaan,
                        'surat_kemampuan_principle_agent' => $dataFormTigaPenerbitanBaru->surat_kemampuan_principle_agent,
                        'surat_loa_poa' => $dataFormTigaPenerbitanBaru->surat_loa_poa,
                        'supporting_letter_dari_vendor' => $dataFormTigaPenerbitanBaru->supporting_letter_dari_vendor,
                        'foto_direktur_4_6' => $dataFormTigaPenerbitanBaru->foto_direktur_4_6,
                        'kepemilikan_kantor' => $dataFormTigaPenerbitanBaru->kepemilikan_kantor,
                        'struktur_organisasi' => $dataFormTigaPenerbitanBaru->struktur_organisasi,
                        'foto_perusahaan' => $dataFormTigaPenerbitanBaru->foto_perusahaan,
                        'gambar_rute_denah_kantor' => $dataFormTigaPenerbitanBaru->gambar_rute_denah_kantor,
                        'kk_direktur_utama' => $dataFormTigaPenerbitanBaru->kk_direktur_utama,
                        'beranda_lpse' => $dataFormTigaPenerbitanBaru->beranda_lpse,
                        'e_catalog' => $dataFormTigaPenerbitanBaru->e_catalog,
                        'status' => $dataFormTigaPenerbitanBaru->status,
                        'catatan_revisi' => $dataFormTigaPenerbitanBaru->catatan_revisi,
                        'form_dua_penerbitan_baru_id' => $dataFormTigaPenerbitanBaru->form_dua_penerbitan_baru_id,
                        'created_at' => $dataFormTigaPenerbitanBaru->created_at,
                        'updated_at' => $dataFormTigaPenerbitanBaru->updated_at,
                        'files_pt1' => [
                            'surat_permohonan_penerbitan' => $dataFormTigaPenerbitanBaru->surat_permohonan_penerbitan,
                            'akte_pendirian_perusahaan' => $dataFormTigaPenerbitanBaru->akte_pendirian_perusahaan,
                            'akte_perubahan_perusahaan' => $dataFormTigaPenerbitanBaru->akte_perubahan_perusahaan,
                            'nomor_izin_berusaha' => $dataFormTigaPenerbitanBaru->nomor_izin_berusaha,
                            'nomor_pokok_wajib_pajak' => $dataFormTigaPenerbitanBaru->nomor_pokok_wajib_pajak,
                        ],
                        'files_pt2' => [
                            'surat_pengukuhan_pengusaha_kena_pajak' => $dataFormTigaPenerbitanBaru->surat_pengukuhan_pengusaha_kena_pajak,
                            'surat_pernyataan_sehat' => $dataFormTigaPenerbitanBaru->surat_pernyataan_sehat,
                            'referensi_bank' => $dataFormTigaPenerbitanBaru->referensi_bank,
                            'neraca_perusahaan_terakhir' => $dataFormTigaPenerbitanBaru->neraca_perusahaan_terakhir,
                            'rekening_koran_perusahaan' => $dataFormTigaPenerbitanBaru->rekening_koran_perusahaan,
                        ],
                        'files_pt3' => [
                            'cv_direktur_utama' => $dataFormTigaPenerbitanBaru->cv_direktur_utama,
                            'ktp_jajaran_direksi' => $dataFormTigaPenerbitanBaru->ktp_jajaran_direksi,
                            'skck' => $dataFormTigaPenerbitanBaru->skck,
                            'company_profile' => $dataFormTigaPenerbitanBaru->company_profile,
                            'daftar_pengalaman_pekerjaan_di_tni_au' => $dataFormTigaPenerbitanBaru->daftar_pengalaman_pekerjaan_di_tni_au,
                        ],
                        'files_pt4' => [
                            'daftar_peralatan_fasilitas_kantor' => $dataFormTigaPenerbitanBaru->daftar_peralatan_fasilitas_kantor,
                            'aset_perusahaan' => $dataFormTigaPenerbitanBaru->aset_perusahaan,
                            'surat_kemampuan_principle_agent' => $dataFormTigaPenerbitanBaru->surat_kemampuan_principle_agent,
                            'surat_loa_poa' => $dataFormTigaPenerbitanBaru->surat_loa_poa,
                            'supporting_letter_dari_vendor' => $dataFormTigaPenerbitanBaru->supporting_letter_dari_vendor,
                        ],
                        'files_pt5' => [
                            'foto_direktur_4_6' => $dataFormTigaPenerbitanBaru->foto_direktur_4_6,
                            'kepemilikan_kantor' => $dataFormTigaPenerbitanBaru->kepemilikan_kantor,
                            'struktur_organisasi' => $dataFormTigaPenerbitanBaru->struktur_organisasi,
                            'foto_perusahaan' => $dataFormTigaPenerbitanBaru->foto_perusahaan,
                            'gambar_rute_denah_kantor' => $dataFormTigaPenerbitanBaru->gambar_rute_denah_kantor,
                        ],
                        'files_pt6' => [
                            'kk_direktur_utama' => $dataFormTigaPenerbitanBaru->kk_direktur_utama,
                            'beranda_lpse' => $dataFormTigaPenerbitanBaru->beranda_lpse,
                            'e_catalog' => $dataFormTigaPenerbitanBaru->e_catalog,
                        ]
                    ],
                    "message" => 'get detail data form tiga penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form dua penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartSatu(array $dataRequest)
    {
        try {
            $result = $this->formTigaPenerbitanBaruModel->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form tiga penerbitan baru part satu success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartDua(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->surat_pengukuhan_pengusaha_kena_pajak = $dataRequest['surat_pengukuhan_pengusaha_kena_pajak'];
            $dataFormTigaPenerbitanBaru->surat_pernyataan_sehat = $dataRequest['surat_pernyataan_sehat'];
            $dataFormTigaPenerbitanBaru->referensi_bank = $dataRequest['referensi_bank'];
            $dataFormTigaPenerbitanBaru->neraca_perusahaan_terakhir = $dataRequest['neraca_perusahaan_terakhir'];
            $dataFormTigaPenerbitanBaru->rekening_koran_perusahaan = $dataRequest['rekening_koran_perusahaan'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga penerbitan baru part dua success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartTiga(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->cv_direktur_utama = $dataRequest['cv_direktur_utama'];
            $dataFormTigaPenerbitanBaru->ktp_jajaran_direksi = $dataRequest['ktp_jajaran_direksi'];
            $dataFormTigaPenerbitanBaru->skck = $dataRequest['skck'];
            $dataFormTigaPenerbitanBaru->company_profile = $dataRequest['company_profile'];
            $dataFormTigaPenerbitanBaru->daftar_pengalaman_pekerjaan_di_tni_au = $dataRequest['daftar_pengalaman_pekerjaan_di_tni_au'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga penerbitan baru part tiga success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartEmpat(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->daftar_peralatan_fasilitas_kantor = $dataRequest['daftar_peralatan_fasilitas_kantor'];
            $dataFormTigaPenerbitanBaru->aset_perusahaan = $dataRequest['aset_perusahaan'];
            $dataFormTigaPenerbitanBaru->surat_kemampuan_principle_agent = $dataRequest['surat_kemampuan_principle_agent'];
            $dataFormTigaPenerbitanBaru->surat_loa_poa = $dataRequest['surat_loa_poa'];
            $dataFormTigaPenerbitanBaru->supporting_letter_dari_vendor = $dataRequest['supporting_letter_dari_vendor'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga penerbitan baru part empat success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartLima(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->foto_direktur_4_6 = $dataRequest['foto_direktur_4_6'];
            $dataFormTigaPenerbitanBaru->kepemilikan_kantor = $dataRequest['kepemilikan_kantor'];
            $dataFormTigaPenerbitanBaru->struktur_organisasi = $dataRequest['struktur_organisasi'];
            $dataFormTigaPenerbitanBaru->foto_perusahaan = $dataRequest['foto_perusahaan'];
            $dataFormTigaPenerbitanBaru->gambar_rute_denah_kantor = $dataRequest['gambar_rute_denah_kantor'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga penerbitan baru part lima success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormTigaPenerbitanBaruPartEnam(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->kk_direktur_utama = $dataRequest['kk_direktur_utama'];
            $dataFormTigaPenerbitanBaru->beranda_lpse = $dataRequest['beranda_lpse'];
            $dataFormTigaPenerbitanBaru->e_catalog = $dataRequest['e_catalog'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga penerbitan baru part lima success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartSatu($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->surat_permohonan_penerbitan = $dataRequest['surat_permohonan_penerbitan'];
            $dataFormTigaPenerbitanBaru->akte_pendirian_perusahaan = $dataRequest['akte_pendirian_perusahaan'];
            $dataFormTigaPenerbitanBaru->akte_perubahan_perusahaan = $dataRequest['akte_perubahan_perusahaan'];
            $dataFormTigaPenerbitanBaru->nomor_izin_berusaha = $dataRequest['nomor_izin_berusaha'];
            $dataFormTigaPenerbitanBaru->nomor_pokok_wajib_pajak = $dataRequest['nomor_pokok_wajib_pajak'];
            $dataFormTigaPenerbitanBaru->status = $dataRequest['status'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga penerbitan baru part satu success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartDua($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->surat_pengukuhan_pengusaha_kena_pajak = $dataRequest['surat_pengukuhan_pengusaha_kena_pajak'];
            $dataFormTigaPenerbitanBaru->surat_pernyataan_sehat = $dataRequest['surat_pernyataan_sehat'];
            $dataFormTigaPenerbitanBaru->referensi_bank = $dataRequest['referensi_bank'];
            $dataFormTigaPenerbitanBaru->neraca_perusahaan_terakhir = $dataRequest['neraca_perusahaan_terakhir'];
            $dataFormTigaPenerbitanBaru->rekening_koran_perusahaan = $dataRequest['rekening_koran_perusahaan'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga penerbitan baru part dua success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartTiga($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->cv_direktur_utama = $dataRequest['cv_direktur_utama'];
            $dataFormTigaPenerbitanBaru->ktp_jajaran_direksi = $dataRequest['ktp_jajaran_direksi'];
            $dataFormTigaPenerbitanBaru->skck = $dataRequest['skck'];
            $dataFormTigaPenerbitanBaru->company_profile = $dataRequest['company_profile'];
            $dataFormTigaPenerbitanBaru->daftar_pengalaman_pekerjaan_di_tni_au = $dataRequest['daftar_pengalaman_pekerjaan_di_tni_au'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga penerbitan baru part tiga success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartEmpat($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->daftar_peralatan_fasilitas_kantor = $dataRequest['daftar_peralatan_fasilitas_kantor'];
            $dataFormTigaPenerbitanBaru->aset_perusahaan = $dataRequest['aset_perusahaan'];
            $dataFormTigaPenerbitanBaru->surat_kemampuan_principle_agent = $dataRequest['surat_kemampuan_principle_agent'];
            $dataFormTigaPenerbitanBaru->surat_loa_poa = $dataRequest['surat_loa_poa'];
            $dataFormTigaPenerbitanBaru->supporting_letter_dari_vendor = $dataRequest['supporting_letter_dari_vendor'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga penerbitan baru part empat success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartLima($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->foto_direktur_4_6 = $dataRequest['foto_direktur_4_6'];
            $dataFormTigaPenerbitanBaru->kepemilikan_kantor = $dataRequest['kepemilikan_kantor'];
            $dataFormTigaPenerbitanBaru->struktur_organisasi = $dataRequest['struktur_organisasi'];
            $dataFormTigaPenerbitanBaru->foto_perusahaan = $dataRequest['foto_perusahaan'];
            $dataFormTigaPenerbitanBaru->gambar_rute_denah_kantor = $dataRequest['gambar_rute_denah_kantor'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga penerbitan baru part lima success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormTigaPenerbitanBaruPartEnam($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            $dataFormTigaPenerbitanBaru->kk_direktur_utama = $dataRequest['kk_direktur_utama'];
            $dataFormTigaPenerbitanBaru->beranda_lpse = $dataRequest['beranda_lpse'];
            $dataFormTigaPenerbitanBaru->e_catalog = $dataRequest['e_catalog'];
            $dataFormTigaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga penerbitan baru part enam success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormTigaPenerbitanBaru($id)
    {
        try {
            $dataFormTigaPenerbitanBaru = $this->formTigaPenerbitanBaruModel->find($id);
            if ($dataFormTigaPenerbitanBaru) {
                $dataFormTigaPenerbitanBaru->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form tiga penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form tiga penerbitan baru tidak ditemukan'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Empat

    public function listDataFormEmpatPenerbitanBaru()
    {
        try {
            $dataFormEmpatPenerbitanBaru = $this->formEmpatPenerbitanBaruModel->get();
            if ($dataFormEmpatPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormEmpatPenerbitanBaru,
                    "message" => 'get data form empat penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form empat penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataFormEmpatPenerbitanBaruByFormTiga($id)
    {
        try {
            $dataFormEmpatPenerbitanBaru = $this->formEmpatPenerbitanBaruModel->where('form_tiga_penerbitan_baru_id', $id)->get();
            if ($dataFormEmpatPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormEmpatPenerbitanBaru,
                    "message" => 'get data form empat penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form empat penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataFormEmpatPenerbitanBaru($id)
    {
        try {
            $dataFormEmpatPenerbitanBaru = $this->formEmpatPenerbitanBaruModel->find($id);
            if ($dataFormEmpatPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormEmpatPenerbitanBaru,
                    "message" => 'get detail data form empat penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form empat penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormEmpatPenerbitanBaru($dataRequest)
    {
        try {
            $result = $this->formEmpatPenerbitanBaruModel->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form empat penerbitan baru success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormEmpatPenerbitanBaru($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormEmpatPenerbitanBaru = $this->formEmpatPenerbitanBaruModel->find($id);
            $dataFormEmpatPenerbitanBaru->jadwal_survei = $dataRequest['jadwal_survei'];
            $dataFormEmpatPenerbitanBaru->status = $dataRequest['status'];
            $dataFormEmpatPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form empat penerbitan baru success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormEmpatPenerbitanBaru($id)
    {
        try {
            $dataFormEmpatPenerbitanBaru = $this->formEmpatPenerbitanBaruModel->find($id);
            if ($dataFormEmpatPenerbitanBaru) {
                $dataFormEmpatPenerbitanBaru->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form empat penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form empat penerbitan baru tidak ditemukan'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Form Lima

    public function listDataFormLimaPenerbitanBaru()
    {
        try {
            $dataFormLimaPenerbitanBaru = $this->formLimaPenerbitanBaruModel->get();
            if ($dataFormLimaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormLimaPenerbitanBaru,
                    "message" => 'get data form lima penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form lima penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataFormLimaPenerbitanBaruByFormEmpat($id)
    {
        try {
            $dataFormLimaPenerbitanBaru = $this->formLimaPenerbitanBaruModel->where('form_empat_penerbitan_baru_id', $id)->get();
            if ($dataFormLimaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormLimaPenerbitanBaru,
                    "message" => 'get data form lima penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form lima penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataFormLimaPenerbitanBaruByFormEmpat($id)
    {
        try {
            $dataFormLimaPenerbitanBaru = $this->formLimaPenerbitanBaruModel->where('form_empat_penerbitan_baru_id', $id)->first();
            if ($dataFormLimaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'skhpp' => $dataFormLimaPenerbitanBaru->skhpp,
                        'tanggal_awal_berlaku' => $dataFormLimaPenerbitanBaru->tanggal_awal_berlaku,
                        'tanggal_akhir_berlaku' => $dataFormLimaPenerbitanBaru->tanggal_akhir_berlaku,
                        'status' => $dataFormLimaPenerbitanBaru->status,
                        'catatan_revisi' => $dataFormLimaPenerbitanBaru->catatan_revisi,
                        'form_empat_penerbitan_baru_id' => $dataFormLimaPenerbitanBaru->form_empat_penerbitan_baru_id,
                        'created_at' => $dataFormLimaPenerbitanBaru->created_at,
                        'updated_at' => $dataFormLimaPenerbitanBaru->updated_at,
                        'files' => [
                            'skhpp' => $dataFormLimaPenerbitanBaru->skhpp,
                        ]
                    ],
                    "message" => 'get detail data form lima penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form lima penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataFormLimaPenerbitanBaru($id)
    {
        try {
            $dataFormLimaPenerbitanBaru = $this->formLimaPenerbitanBaruModel->find($id);
            if ($dataFormLimaPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'skhpp' => $dataFormLimaPenerbitanBaru->skhpp,
                        'tanggal_awal_berlaku' => $dataFormLimaPenerbitanBaru->tanggal_awal_berlaku,
                        'tanggal_akhir_berlaku' => $dataFormLimaPenerbitanBaru->tanggal_akhir_berlaku,
                        'status' => $dataFormLimaPenerbitanBaru->status,
                        'catatan_revisi' => $dataFormLimaPenerbitanBaru->catatan_revisi,
                        'form_empat_penerbitan_baru_id' => $dataFormLimaPenerbitanBaru->form_empat_penerbitan_baru_id,
                        'created_at' => $dataFormLimaPenerbitanBaru->created_at,
                        'updated_at' => $dataFormLimaPenerbitanBaru->updated_at,
                        'files' => [
                            'skhpp' => $dataFormLimaPenerbitanBaru->skhpp,
                        ]
                    ],
                    "message" => 'get detail data form lima penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form lima penerbitan baru not found'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataFormLimaPenerbitanBaru(array $dataRequest)
    {
        try {
            $result = $this->formLimaPenerbitanBaruModel->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form lima penerbitan baru success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataFormLimaPenerbitanBaru($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormLimaPenerbitanBaru = $this->formLimaPenerbitanBaruModel->find($id);
            if (isset($dataRequest['skhpp'])) {
                $dataFormLimaPenerbitanBaru->skhpp = $dataRequest['skhpp'];
            }
            $dataFormLimaPenerbitanBaru->tanggal_awal_berlaku = $dataRequest['tanggal_awal_berlaku'];
            $dataFormLimaPenerbitanBaru->tanggal_akhir_berlaku = $dataRequest['tanggal_akhir_berlaku'];
            $dataFormLimaPenerbitanBaru->status = $dataRequest['status'];
            $dataFormLimaPenerbitanBaru->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form lima penerbitan baru success'
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataFormLimaPenerbitanBaru($id)
    {
        try {
            $dataFormLimaPenerbitanBaru = $this->formLimaPenerbitanBaruModel->where('form_empat_penerbitan_baru_id', $id)->first();
            if ($dataFormLimaPenerbitanBaru) {
                $dataFormLimaPenerbitanBaru->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form lima penerbitan baru success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form lima penerbitan baru tidak ditemukan'
                ];
            }
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    // Dll

    public function verifyFormSatuPenerbitanBaru(array $dataRequest, $id)
    {
        try {
            $dataFormSatu = $this->formSatuPenerbitanBaruModel->find($id);
            if ($dataFormSatu) {
                $dataFormSatu->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormSatu->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormSatu->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form satu penerbitan baru berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form satu penerbitan baru gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormDuaPenerbitanBaru(array $dataRequest, $id)
    {
        try {
            $dataFormDua = $this->formDuaPenerbitanBaruModel->find($id);
            if ($dataFormDua) {
                $dataFormDua->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormDua->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormDua->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form dua penerbitan baru berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form dua penerbitan baru gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormTigaPenerbitanBaru(array $dataRequest, $id)
    {
        try {
            $dataFormTiga = $this->formTigaPenerbitanBaruModel->find($id);
            if ($dataFormTiga) {
                $dataFormTiga->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormTiga->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormTiga->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form tiga penerbitan baru berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form tiga penerbitan baru gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormEmpatPenerbitanBaru(array $dataRequest, $id)
    {
        try {
            $dataFormEmpat = $this->formEmpatPenerbitanBaruModel->find($id);
            if ($dataFormEmpat) {
                $dataFormEmpat->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormEmpat->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormEmpat->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form empat penerbitan baru berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form empat penerbitan baru gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormLimaPenerbitanBaru(array $dataRequest, $id)
    {
        try {
            $dataFormLima = $this->formLimaPenerbitanBaruModel->find($id);
            if ($dataFormLima) {
                $dataFormLima->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormLima->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormLima->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form lima penerbitan baru berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form lima penerbitan baru gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
