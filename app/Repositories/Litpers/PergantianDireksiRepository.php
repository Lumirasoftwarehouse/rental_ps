<?php

namespace App\Repositories\Litpers;

use App\Models\FormSatuPergantianDireksi;
use App\Models\FormDuaPergantianDireksi;
use App\Models\FormTigaPergantianDireksi;
use App\Models\FormEmpatPergantianDireksi;
use App\Models\FormLimaPergantianDireksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PergantianDireksiRepository
{
    private $formSatuPergantianDireksi;
    private $formDuaPergantianDireksi;
    private $formTigaPergantianDireksi;
    private $formEmpatPergantianDireksi;
    private $formLimaPergantianDireksi;

    public function __construct(FormSatuPergantianDireksi $formSatuPergantianDireksi, FormDuaPergantianDireksi $formDuaPergantianDireksi, FormTigaPergantianDireksi $formTigaPergantianDireksi, FormEmpatPergantianDireksi $formEmpatPergantianDireksi, FormLimaPergantianDireksi $formLimaPergantianDireksi)
    {
        $this->formSatuPergantianDireksi = $formSatuPergantianDireksi;
        $this->formDuaPergantianDireksi = $formDuaPergantianDireksi;
        $this->formTigaPergantianDireksi = $formTigaPergantianDireksi;
        $this->formEmpatPergantianDireksi = $formEmpatPergantianDireksi;
        $this->formLimaPergantianDireksi = $formLimaPergantianDireksi;
    }

    public function listDataAllFormPergantianDireksiByMitra($id)
    {
        try {
            $pengajuanBelumSelesai = $this->formSatuPergantianDireksi->where('pic_perusahaan_litpers_id', $id)
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
                                            'id' => $formDua->formTiga->formEmpat->formLima->id,
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

    public function listDataUnfinishedFormPergantianDireksiByMitra($id)
    {
        try {
            $pengajuanBelumSelesai = $this->formSatuPergantianDireksi->where('pic_perusahaan_litpers_id', $id)
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

    public function listDataUnvalidatedFormPergantianDireksi()
    {
        try {
            $pengajuanBelumTervalidasi = $this->formSatuPergantianDireksi
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

    public function listDataFormSatuPergantianDireksi()
    {
        try {
            $dataFormSatuPergantianDireksi = $this->formSatuPergantianDireksi->get();
            if ($dataFormSatuPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormSatuPergantianDireksi,
                    "message" => 'get data form satu pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form satu pergantian direksi not found'
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

    public function listDataFormSatuPergantianDireksiByAdmin($id)
    {
        try {
            $dataFormSatuPergantianDireksi = $this->formSatuPergantianDireksi->where('admin_litpers_id', $id)->get();
            if ($dataFormSatuPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormSatuPergantianDireksi,
                    "message" => 'get data form satu pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form satu pergantian direksi not found'
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

    public function listDataFormSatuPergantianDireksiByMitra($id)
    {
        try {
            $dataFormSatuPergantianDireksi = $this->formSatuPergantianDireksi->where('pic_perusahaan_litpers_id', $id)->get();
            if ($dataFormSatuPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormSatuPergantianDireksi,
                    "message" => 'get data form satu pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form satu pergantian direksi not found'
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

    public function detailDataFormSatuPergantianDireksi($id)
    {
        try {
            $dataFormSatuPergantianDireksi = $this->formSatuPergantianDireksi->find($id);
            if ($dataFormSatuPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'surat_disadaau_diskonsau' => $dataFormSatuPergantianDireksi->surat_disadaau_diskonsau,
                        'skhpp_lama' => $dataFormSatuPergantianDireksi->skhpp_lama,
                        'status' => $dataFormSatuPergantianDireksi->status,
                        'catatan_revisi' => $dataFormSatuPergantianDireksi->catatan_revisi,
                        'pic_perusahaan_litpers_id' => $dataFormSatuPergantianDireksi->pic_perusahaan_litpers_id,
                        'admin_litpers_id' => $dataFormSatuPergantianDireksi->admin_litpers_id,
                        'jenis_skhpp_id' => $dataFormSatuPergantianDireksi->jenis_skhpp_id,
                        'created_at' => $dataFormSatuPergantianDireksi->created_at,
                        'updated_at' => $dataFormSatuPergantianDireksi->updated_at,
                        'files' => [
                            'surat_disadaau_diskonsau' => $dataFormSatuPergantianDireksi->surat_disadaau_diskonsau,
                            'skhpp_lama' => $dataFormSatuPergantianDireksi->skhpp_lama,
                        ]
                    ],
                    "message" => 'get detail data form satu pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form satu pergantian direksi not found'
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

    public function inputDataFormSatuPergantianDireksi(array $dataRequest)
    {
        try {
            $result = $this->formSatuPergantianDireksi->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form satu pergantian direksi success'
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

    public function updateDataFormSatuPergantianDireksi($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormSatuPergantianDireksi = $this->formSatuPergantianDireksi->find($id);
            $dataFormSatuPergantianDireksi->surat_disadaau_diskonsau = $dataRequest['surat_disadaau_diskonsau'];
            $dataFormSatuPergantianDireksi->skhpp_lama = $dataRequest['skhpp_lama'];
            $dataFormSatuPergantianDireksi->status = $dataRequest['status'];
            $dataFormSatuPergantianDireksi->jenis_skhpp_id = $dataRequest['jenis_skhpp_id'];
            $dataFormSatuPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form satu pergantian direksi success'
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

    public function deleteDataFormSatuPergantianDireksi($id)
    {
        try {
            $dataFormSatuPergantianDireksi = $this->formSatuPergantianDireksi->find($id);
            if ($dataFormSatuPergantianDireksi) {
                $dataFormSatuPergantianDireksi->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form satu pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form satu pergantian direksi tidak ditemukan'
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

    public function listDataFormDuaPergantianDireksi()
    {
        try {
            $dataFormDuaPergantianDireksi = $this->formDuaPergantianDireksi->get();
            if ($dataFormDuaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormDuaPergantianDireksi,
                    "message" => 'get data form dua pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form dua pergantian direksi not found'
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

    public function listDataFormDuaPergantianDireksiByFormSatu($id)
    {
        try {
            $dataFormDuaPergantianDireksi = $this->formDuaPergantianDireksi->where('form_satu_pergantian_id', $id)->get();
            if ($dataFormDuaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormDuaPergantianDireksi,
                    "message" => 'get data form dua pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form dua pergantian direksi not found'
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

    public function detailDataFormDuaPergantianDireksi($id)
    {
        try {
            $dataFormDuaPergantianDireksi = $this->formDuaPergantianDireksi->find($id);
            if ($dataFormDuaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormDuaPergantianDireksi,
                    "message" => 'get detail data form dua pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form dua pergantian direksi not found'
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

    public function inputDataFormDuaPergantianDireksi($dataRequest)
    {
        try {
            $result = $this->formDuaPergantianDireksi->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form dua pergantian direksi success'
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

    public function updateDataFormDuaPergantianDireksi($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPergantianDireksi = $this->formDuaPergantianDireksi->find($id);
            $dataFormDuaPergantianDireksi->jadwal_dip = $dataRequest['jadwal_dip'];
            $dataFormDuaPergantianDireksi->status = $dataRequest['status'];
            $dataFormDuaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form dua pergantian direksi success'
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

    public function deleteDataFormDuaPergantianDireksi($id)
    {
        try {
            $dataFormDuaPergantianDireksi = $this->formDuaPergantianDireksi->find($id);
            if ($dataFormDuaPergantianDireksi) {
                $dataFormDuaPergantianDireksi->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form dua pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form dua pergantian direksi tidak ditemukan'
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

    public function listDataFormTigaPergantianDireksi()
    {
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->get();
            if ($dataFormTigaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormTigaPergantianDireksi,
                    "message" => 'get data form tiga pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form tiga pergantian direksi not found'
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

    public function listDataFormTigaPergantianDireksiByFormDua($id)
    {
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->where('form_dua_pergantian_id', $id)->get();
            if ($dataFormTigaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormTigaPergantianDireksi,
                    "message" => 'get data form tiga pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form tiga pergantian direksi not found'
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

    public function detailDataFormTigaPergantianDireksi($id)
    {
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            if ($dataFormTigaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'surat_permohonan_penerbitan' => $dataFormTigaPergantianDireksi->surat_permohonan_penerbitan,
                        'akte_pendirian_perusahaan' => $dataFormTigaPergantianDireksi->akte_pendirian_perusahaan,
                        'akte_perubahan_perusahaan' => $dataFormTigaPergantianDireksi->akte_perubahan_perusahaan,
                        'nomor_izin_berusaha' => $dataFormTigaPergantianDireksi->nomor_izin_berusaha,
                        'nomor_pokok_wajib_pajak' => $dataFormTigaPergantianDireksi->nomor_pokok_wajib_pajak,
                        'surat_pengukuhan_pengusaha_kena_pajak' => $dataFormTigaPergantianDireksi->surat_pengukuhan_pengusaha_kena_pajak,
                        'surat_pernyataan_sehat' => $dataFormTigaPergantianDireksi->surat_pernyataan_sehat,
                        'referensi_bank' => $dataFormTigaPergantianDireksi->referensi_bank,
                        'neraca_perusahaan_terakhir' => $dataFormTigaPergantianDireksi->neraca_perusahaan_terakhir,
                        'rekening_koran_perusahaan' => $dataFormTigaPergantianDireksi->rekening_koran_perusahaan,
                        'cv_direktur_utama' => $dataFormTigaPergantianDireksi->cv_direktur_utama,
                        'ktp_jajaran_direksi' => $dataFormTigaPergantianDireksi->ktp_jajaran_direksi,
                        'skck' => $dataFormTigaPergantianDireksi->skck,
                        'company_profile' => $dataFormTigaPergantianDireksi->company_profile,
                        'daftar_pengalaman_pekerjaan_di_tni_au' => $dataFormTigaPergantianDireksi->daftar_pengalaman_pekerjaan_di_tni_au,
                        'daftar_peralatan_fasilitas_kantor' => $dataFormTigaPergantianDireksi->daftar_peralatan_fasilitas_kantor,
                        'aset_perusahaan' => $dataFormTigaPergantianDireksi->aset_perusahaan,
                        'surat_kemampuan_principle_agent' => $dataFormTigaPergantianDireksi->surat_kemampuan_principle_agent,
                        'surat_loa_poa' => $dataFormTigaPergantianDireksi->surat_loa_poa,
                        'supporting_letter_dari_vendor' => $dataFormTigaPergantianDireksi->supporting_letter_dari_vendor,
                        'foto_direktur_4_6' => $dataFormTigaPergantianDireksi->foto_direktur_4_6,
                        'kepemilikan_kantor' => $dataFormTigaPergantianDireksi->kepemilikan_kantor,
                        'struktur_organisasi' => $dataFormTigaPergantianDireksi->struktur_organisasi,
                        'foto_perusahaan' => $dataFormTigaPergantianDireksi->foto_perusahaan,
                        'gambar_rute_denah_kantor' => $dataFormTigaPergantianDireksi->gambar_rute_denah_kantor,
                        'kk_direktur_utama' => $dataFormTigaPergantianDireksi->kk_direktur_utama,
                        'beranda_lpse' => $dataFormTigaPergantianDireksi->beranda_lpse,
                        'e_catalog' => $dataFormTigaPergantianDireksi->e_catalog,
                        'status' => $dataFormTigaPergantianDireksi->status,
                        'catatan_revisi' => $dataFormTigaPergantianDireksi->catatan_revisi,
                        'form_dua_pergantian_id' => $dataFormTigaPergantianDireksi->form_dua_pergantian_id,
                        'created_at' => $dataFormTigaPergantianDireksi->created_at,
                        'updated_at' => $dataFormTigaPergantianDireksi->updated_at,
                        'files_pt1' => [
                            'surat_permohonan_penerbitan' => $dataFormTigaPergantianDireksi->surat_permohonan_penerbitan,
                            'akte_pendirian_perusahaan' => $dataFormTigaPergantianDireksi->akte_pendirian_perusahaan,
                            'akte_perubahan_perusahaan' => $dataFormTigaPergantianDireksi->akte_perubahan_perusahaan,
                            'nomor_izin_berusaha' => $dataFormTigaPergantianDireksi->nomor_izin_berusaha,
                            'nomor_pokok_wajib_pajak' => $dataFormTigaPergantianDireksi->nomor_pokok_wajib_pajak,
                        ],
                        'files_pt2' => [
                            'surat_pengukuhan_pengusaha_kena_pajak' => $dataFormTigaPergantianDireksi->surat_pengukuhan_pengusaha_kena_pajak,
                            'surat_pernyataan_sehat' => $dataFormTigaPergantianDireksi->surat_pernyataan_sehat,
                            'referensi_bank' => $dataFormTigaPergantianDireksi->referensi_bank,
                            'neraca_perusahaan_terakhir' => $dataFormTigaPergantianDireksi->neraca_perusahaan_terakhir,
                            'rekening_koran_perusahaan' => $dataFormTigaPergantianDireksi->rekening_koran_perusahaan,
                        ],
                        'files_pt3' => [
                            'cv_direktur_utama' => $dataFormTigaPergantianDireksi->cv_direktur_utama,
                            'ktp_jajaran_direksi' => $dataFormTigaPergantianDireksi->ktp_jajaran_direksi,
                            'skck' => $dataFormTigaPergantianDireksi->skck,
                            'company_profile' => $dataFormTigaPergantianDireksi->company_profile,
                            'daftar_pengalaman_pekerjaan_di_tni_au' => $dataFormTigaPergantianDireksi->daftar_pengalaman_pekerjaan_di_tni_au,
                        ],
                        'files_pt4' => [
                            'daftar_peralatan_fasilitas_kantor' => $dataFormTigaPergantianDireksi->daftar_peralatan_fasilitas_kantor,
                            'aset_perusahaan' => $dataFormTigaPergantianDireksi->aset_perusahaan,
                            'surat_kemampuan_principle_agent' => $dataFormTigaPergantianDireksi->surat_kemampuan_principle_agent,
                            'surat_loa_poa' => $dataFormTigaPergantianDireksi->surat_loa_poa,
                            'supporting_letter_dari_vendor' => $dataFormTigaPergantianDireksi->supporting_letter_dari_vendor,
                        ],
                        'files_pt5' => [
                            'foto_direktur_4_6' => $dataFormTigaPergantianDireksi->foto_direktur_4_6,
                            'kepemilikan_kantor' => $dataFormTigaPergantianDireksi->kepemilikan_kantor,
                            'struktur_organisasi' => $dataFormTigaPergantianDireksi->struktur_organisasi,
                            'foto_perusahaan' => $dataFormTigaPergantianDireksi->foto_perusahaan,
                            'gambar_rute_denah_kantor' => $dataFormTigaPergantianDireksi->gambar_rute_denah_kantor,
                        ],
                        'files_pt6' => [
                            'kk_direktur_utama' => $dataFormTigaPergantianDireksi->kk_direktur_utama,
                            'beranda_lpse' => $dataFormTigaPergantianDireksi->beranda_lpse,
                            'e_catalog' => $dataFormTigaPergantianDireksi->e_catalog,
                        ]
                    ],
                    "message" => 'get detail data form tiga pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form dua pergantian direksi not found'
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

    public function inputDataFormTigaPergantianDireksiPartSatu(array $dataRequest)
    {
        try {
            $result = $this->formTigaPergantianDireksi->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form tiga pergantian direksi part satu success'
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

    public function inputDataFormTigaPergantianDireksiPartDua(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->surat_pengukuhan_pengusaha_kena_pajak = $dataRequest['surat_pengukuhan_pengusaha_kena_pajak'];
            $dataFormTigaPergantianDireksi->surat_pernyataan_sehat = $dataRequest['surat_pernyataan_sehat'];
            $dataFormTigaPergantianDireksi->referensi_bank = $dataRequest['referensi_bank'];
            $dataFormTigaPergantianDireksi->neraca_perusahaan_terakhir = $dataRequest['neraca_perusahaan_terakhir'];
            $dataFormTigaPergantianDireksi->rekening_koran_perusahaan = $dataRequest['rekening_koran_perusahaan'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga pergantian direksi part dua success'
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

    public function inputDataFormTigaPergantianDireksiPartTiga(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->cv_direktur_utama = $dataRequest['cv_direktur_utama'];
            $dataFormTigaPergantianDireksi->ktp_jajaran_direksi = $dataRequest['ktp_jajaran_direksi'];
            $dataFormTigaPergantianDireksi->skck = $dataRequest['skck'];
            $dataFormTigaPergantianDireksi->company_profile = $dataRequest['company_profile'];
            $dataFormTigaPergantianDireksi->daftar_pengalaman_pekerjaan_di_tni_au = $dataRequest['daftar_pengalaman_pekerjaan_di_tni_au'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga pergantian direksi part tiga success'
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

    public function inputDataFormTigaPergantianDireksiPartEmpat(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->daftar_peralatan_fasilitas_kantor = $dataRequest['daftar_peralatan_fasilitas_kantor'];
            $dataFormTigaPergantianDireksi->aset_perusahaan = $dataRequest['aset_perusahaan'];
            $dataFormTigaPergantianDireksi->surat_kemampuan_principle_agent = $dataRequest['surat_kemampuan_principle_agent'];
            $dataFormTigaPergantianDireksi->surat_loa_poa = $dataRequest['surat_loa_poa'];
            $dataFormTigaPergantianDireksi->supporting_letter_dari_vendor = $dataRequest['supporting_letter_dari_vendor'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga pergantian direksi part empat success'
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

    public function inputDataFormTigaPergantianDireksiPartLima(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->foto_direktur_4_6 = $dataRequest['foto_direktur_4_6'];
            $dataFormTigaPergantianDireksi->kepemilikan_kantor = $dataRequest['kepemilikan_kantor'];
            $dataFormTigaPergantianDireksi->struktur_organisasi = $dataRequest['struktur_organisasi'];
            $dataFormTigaPergantianDireksi->foto_perusahaan = $dataRequest['foto_perusahaan'];
            $dataFormTigaPergantianDireksi->gambar_rute_denah_kantor = $dataRequest['gambar_rute_denah_kantor'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga pergantian direksi part lima success'
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

    public function inputDataFormTigaPergantianDireksiPartEnam(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->kk_direktur_utama = $dataRequest['kk_direktur_utama'];
            $dataFormTigaPergantianDireksi->beranda_lpse = $dataRequest['beranda_lpse'];
            $dataFormTigaPergantianDireksi->e_catalog = $dataRequest['e_catalog'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form tiga pergantian direksi part lima success'
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

    public function updateDataFormTigaPergantianDireksiPartSatu($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->surat_permohonan_penerbitan = $dataRequest['surat_permohonan_penerbitan'];
            $dataFormTigaPergantianDireksi->akte_pendirian_perusahaan = $dataRequest['akte_pendirian_perusahaan'];
            $dataFormTigaPergantianDireksi->akte_perubahan_perusahaan = $dataRequest['akte_perubahan_perusahaan'];
            $dataFormTigaPergantianDireksi->nomor_izin_berusaha = $dataRequest['nomor_izin_berusaha'];
            $dataFormTigaPergantianDireksi->nomor_pokok_wajib_pajak = $dataRequest['nomor_pokok_wajib_pajak'];
            $dataFormTigaPergantianDireksi->status = $dataRequest['status'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga pergantian direksi part satu success'
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

    public function updateDataFormTigaPergantianDireksiPartDua($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->surat_pengukuhan_pengusaha_kena_pajak = $dataRequest['surat_pengukuhan_pengusaha_kena_pajak'];
            $dataFormTigaPergantianDireksi->surat_pernyataan_sehat = $dataRequest['surat_pernyataan_sehat'];
            $dataFormTigaPergantianDireksi->referensi_bank = $dataRequest['referensi_bank'];
            $dataFormTigaPergantianDireksi->neraca_perusahaan_terakhir = $dataRequest['neraca_perusahaan_terakhir'];
            $dataFormTigaPergantianDireksi->rekening_koran_perusahaan = $dataRequest['rekening_koran_perusahaan'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga pergantian direksi part dua success'
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

    public function updateDataFormTigaPergantianDireksiPartTiga($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->cv_direktur_utama = $dataRequest['cv_direktur_utama'];
            $dataFormTigaPergantianDireksi->ktp_jajaran_direksi = $dataRequest['ktp_jajaran_direksi'];
            $dataFormTigaPergantianDireksi->skck = $dataRequest['skck'];
            $dataFormTigaPergantianDireksi->company_profile = $dataRequest['company_profile'];
            $dataFormTigaPergantianDireksi->daftar_pengalaman_pekerjaan_di_tni_au = $dataRequest['daftar_pengalaman_pekerjaan_di_tni_au'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga pergantian direksi part tiga success'
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

    public function updateDataFormTigaPergantianDireksiPartEmpat($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->daftar_peralatan_fasilitas_kantor = $dataRequest['daftar_peralatan_fasilitas_kantor'];
            $dataFormTigaPergantianDireksi->aset_perusahaan = $dataRequest['aset_perusahaan'];
            $dataFormTigaPergantianDireksi->surat_kemampuan_principle_agent = $dataRequest['surat_kemampuan_principle_agent'];
            $dataFormTigaPergantianDireksi->surat_loa_poa = $dataRequest['surat_loa_poa'];
            $dataFormTigaPergantianDireksi->supporting_letter_dari_vendor = $dataRequest['supporting_letter_dari_vendor'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga pergantian direksi part empat success'
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

    public function updateDataFormTigaPergantianDireksiPartLima($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->foto_direktur_4_6 = $dataRequest['foto_direktur_4_6'];
            $dataFormTigaPergantianDireksi->kepemilikan_kantor = $dataRequest['kepemilikan_kantor'];
            $dataFormTigaPergantianDireksi->struktur_organisasi = $dataRequest['struktur_organisasi'];
            $dataFormTigaPergantianDireksi->foto_perusahaan = $dataRequest['foto_perusahaan'];
            $dataFormTigaPergantianDireksi->gambar_rute_denah_kantor = $dataRequest['gambar_rute_denah_kantor'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga pergantian direksi part lima success'
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

    public function updateDataFormTigaPergantianDireksiPartEnam($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            $dataFormTigaPergantianDireksi->kk_direktur_utama = $dataRequest['kk_direktur_utama'];
            $dataFormTigaPergantianDireksi->beranda_lpse = $dataRequest['beranda_lpse'];
            $dataFormTigaPergantianDireksi->e_catalog = $dataRequest['e_catalog'];
            $dataFormTigaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga pergantian direksi part enam success'
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

    public function deleteDataFormTigaPergantianDireksi($id)
    {
        try {
            $dataFormTigaPergantianDireksi = $this->formTigaPergantianDireksi->find($id);
            if ($dataFormTigaPergantianDireksi) {
                $dataFormTigaPergantianDireksi->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form tiga pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form tiga pergantian direksi tidak ditemukan'
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

    public function listDataFormEmpatPergantianDireksi()
    {
        try {
            $dataFormEmpatPergantianDireksi = $this->formEmpatPergantianDireksi->get();
            if ($dataFormEmpatPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormEmpatPergantianDireksi,
                    "message" => 'get data form empat pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form empat pergantian direksi not found'
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

    public function listDataFormEmpatPergantianDireksiByFormTiga($id)
    {
        try {
            $dataFormEmpatPergantianDireksi = $this->formEmpatPergantianDireksi->where('form_tiga_pergantian_id', $id)->get();
            if ($dataFormEmpatPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormEmpatPergantianDireksi,
                    "message" => 'get data form empat pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form empat pergantian direksi not found'
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

    public function detailDataFormEmpatPergantianDireksi($id)
    {
        try {
            $dataFormEmpatPergantianDireksi = $this->formEmpatPergantianDireksi->find($id);
            if ($dataFormEmpatPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormEmpatPergantianDireksi,
                    "message" => 'get detail data form empat pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form empat pergantian direksi not found'
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

    public function inputDataFormEmpatPergantianDireksi($dataRequest)
    {
        try {
            $result = $this->formEmpatPergantianDireksi->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form empat pergantian direksi success'
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

    public function updateDataFormEmpatPergantianDireksi($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormEmpatPergantianDireksi = $this->formEmpatPergantianDireksi->find($id);
            $dataFormEmpatPergantianDireksi->jadwal_survei = $dataRequest['jadwal_survei'];
            $dataFormEmpatPergantianDireksi->status = $dataRequest['status'];
            $dataFormEmpatPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form empat pergantian direksi success'
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

    public function deleteDataFormEmpatPergantianDireksi($id)
    {
        try {
            $dataFormEmpatPergantianDireksi = $this->formEmpatPergantianDireksi->find($id);
            if ($dataFormEmpatPergantianDireksi) {
                $dataFormEmpatPergantianDireksi->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form empat pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form empat pergantian direksi tidak ditemukan'
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

    public function listDataFormLimaPergantianDireksi()
    {
        try {
            $dataFormLimaPergantianDireksi = $this->formLimaPergantianDireksi->get();
            if ($dataFormLimaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormLimaPergantianDireksi,
                    "message" => 'get data form lima pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form lima pergantian direksi not found'
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

    public function listDataFormLimaPergantianDireksiByFormEmpat($id)
    {
        try {
            $dataFormLimaPergantianDireksi = $this->formLimaPergantianDireksi->where('form_empat_pergantian_id', $id)->get();
            if ($dataFormLimaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormLimaPergantianDireksi,
                    "message" => 'get data form lima pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form lima pergantian direksi not found'
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

    public function detailDataFormLimaPergantianDireksiByFormEmpat($id)
    {
        try {
            $dataFormLimaPergantianDireksi = $this->formLimaPergantianDireksi->where('form_empat_pergantian_id', $id)->first();
            if ($dataFormLimaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'skhpp' => $dataFormLimaPergantianDireksi->skhpp,
                        'tanggal_awal_berlaku' => $dataFormLimaPergantianDireksi->tanggal_awal_berlaku,
                        'tanggal_akhir_berlaku' => $dataFormLimaPergantianDireksi->tanggal_akhir_berlaku,
                        'status' => $dataFormLimaPergantianDireksi->status,
                        'catatan_revisi' => $dataFormLimaPergantianDireksi->catatan_revisi,
                        'form_empat_pergantian_id' => $dataFormLimaPergantianDireksi->form_empat_penerbitan_baru_id,
                        'created_at' => $dataFormLimaPergantianDireksi->created_at,
                        'updated_at' => $dataFormLimaPergantianDireksi->updated_at,
                        'files' => [
                            'skhpp' => $dataFormLimaPergantianDireksi->skhpp,
                        ]
                    ],
                    "message" => 'get detail data form lima pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form lima pergantian direksi not found'
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

    public function detailDataFormLimaPergantianDireksi($id)
    {
        try {
            $dataFormLimaPergantianDireksi = $this->formLimaPergantianDireksi->find($id);
            if ($dataFormLimaPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'skhpp' => $dataFormLimaPergantianDireksi->skhpp,
                        'tanggal_awal_berlaku' => $dataFormLimaPergantianDireksi->tanggal_awal_berlaku,
                        'tanggal_akhir_berlaku' => $dataFormLimaPergantianDireksi->tanggal_akhir_berlaku,
                        'status' => $dataFormLimaPergantianDireksi->status,
                        'catatan_revisi' => $dataFormLimaPergantianDireksi->catatan_revisi,
                        'form_empat_pergantian_id' => $dataFormLimaPergantianDireksi->form_empat_pergantian_id,
                        'created_at' => $dataFormLimaPergantianDireksi->created_at,
                        'updated_at' => $dataFormLimaPergantianDireksi->updated_at,
                        'files' => [
                            'skhpp' => $dataFormLimaPergantianDireksi->skhpp,
                        ]
                    ],
                    "message" => 'get detail data form lima pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form lima pergantian direksi not found'
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

    public function inputDataFormLimaPergantianDireksi(array $dataRequest)
    {
        try {
            $result = $this->formLimaPergantianDireksi->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form lima pergantian direksi success'
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

    public function updateDataFormLimaPergantianDireksi($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormLimaPergantianDireksi = $this->formLimaPergantianDireksi->find($id);
            if (isset($dataRequest['skhpp'])) {
                $dataFormLimaPergantianDireksi->skhpp = $dataRequest['skhpp'];
            }
            $dataFormLimaPergantianDireksi->tanggal_awal_berlaku = $dataRequest['tanggal_awal_berlaku'];
            $dataFormLimaPergantianDireksi->tanggal_akhir_berlaku = $dataRequest['tanggal_akhir_berlaku'];
            $dataFormLimaPergantianDireksi->status = $dataRequest['status'];
            $dataFormLimaPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form lima pergantian direksi success'
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

    public function deleteDataFormLimaPergantianDireksi($id)
    {
        try {
            $dataFormLimaPergantianDireksi = $this->formLimaPergantianDireksi->where('form_empat_pergantian_id', $id)->first();
            if ($dataFormLimaPergantianDireksi) {
                $dataFormLimaPergantianDireksi->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form lima pergantian direksi success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form lima pergantian direksi tidak ditemukan'
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

    public function verifyFormSatuPergantianDireksi(array $dataRequest, $id)
    {
        try {
            $dataFormSatu = $this->formSatuPergantianDireksi->find($id);
            if ($dataFormSatu) {
                $dataFormSatu->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormSatu->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormSatu->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form satu pergantian direksi berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form satu pergantian direksi gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormDuaPergantianDireksi(array $dataRequest, $id)
    {
        try {
            $dataFormDua = $this->formDuaPergantianDireksi->find($id);
            if ($dataFormDua) {
                $dataFormDua->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormDua->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormDua->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form dua pergantian direksi berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form dua pergantian direksi gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormTigaPergantianDireksi(array $dataRequest, $id)
    {
        try {
            $dataFormTiga = $this->formTigaPergantianDireksi->find($id);
            if ($dataFormTiga) {
                $dataFormTiga->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormTiga->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormTiga->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form tiga pergantian direksi berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form tiga pergantian direksi gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormEmpatPergantianDireksi(array $dataRequest, $id)
    {
        try {
            $dataFormEmpat = $this->formEmpatPergantianDireksi->find($id);
            if ($dataFormEmpat) {
                $dataFormEmpat->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormEmpat->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormEmpat->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form empat pergantian direksi berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form empat pergantian direksi gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormLimaPergantianDireksi(array $dataRequest, $id)
    {
        try {
            $dataFormLima = $this->formLimaPergantianDireksi->find($id);
            if ($dataFormLima) {
                $dataFormLima->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormLima->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormLima->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form lima pergantian direksi berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form lima pergantian direksi gagal'
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
