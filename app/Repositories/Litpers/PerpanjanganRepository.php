<?php

namespace App\Repositories\Litpers;

use App\Models\FormSatuPerpanjangan;
use App\Models\FormDuaPerpanjangan;
use App\Models\FormTigaPerpanjangan;
use App\Models\FormEmpatPerpanjangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PerpanjanganRepository
{
    private $formSatuPerpanjangan;
    private $formDuaPerpanjangan;
    private $formTigaPerpanjangan;
    private $formEmpatPerpanjangan;

    public function __construct(FormSatuPerpanjangan $formSatuPerpanjangan, FormDuaPerpanjangan $formDuaPerpanjangan, FormTigaPerpanjangan $formTigaPerpanjangan, FormEmpatPerpanjangan $formEmpatPerpanjangan)
    {
        $this->formSatuPerpanjangan = $formSatuPerpanjangan;
        $this->formDuaPerpanjangan = $formDuaPerpanjangan;
        $this->formTigaPerpanjangan = $formTigaPerpanjangan;
        $this->formEmpatPerpanjangan = $formEmpatPerpanjangan;
    }

    public function listDataAllFormPerpanjanganByMitra($id)
    {
        try {
            $pengajuanBelumSelesai = $this->formSatuPerpanjangan->where('pic_perusahaan_litpers_id', $id)
                ->with(['formDua.formTiga.formEmpat'])
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
                                if ($formDua->formTiga->formEmpat != null) {
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

    public function listDataUnfinishedFormPerpanjanganByMitra($id)
    {
        try {
            $pengajuanBelumSelesai = $this->formSatuPerpanjangan->where('pic_perusahaan_litpers_id', $id)
                ->with(['formDua.formTiga.formEmpat'])
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
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                ];
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

    public function listDataUnvalidatedFormPerpanjangan()
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

    public function listDataFormSatuPerpanjangan()
    {
        try {
            $dataFormSatuPerpanjangan = $this->formSatuPerpanjangan->get();
            if ($dataFormSatuPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormSatuPerpanjangan,
                    "message" => 'get data form satu perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form satu perpanjangan not found'
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

    public function listDataFormSatuPerpanjanganByAdmin($id)
    {
        try {
            $dataFormSatuPerpanjangan = $this->formSatuPerpanjangan->where('admin_litpers_id', $id)->get();
            if ($dataFormSatuPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormSatuPerpanjangan,
                    "message" => 'get data form satu perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form satu perpanjangan not found'
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

    public function listDataFormSatuPerpanjanganByMitra($id)
    {
        try {
            $dataFormSatuPerpanjangan = $this->formSatuPerpanjangan->where('pic_perusahaan_litpers_id', $id)->get();
            if ($dataFormSatuPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormSatuPerpanjangan,
                    "message" => 'get data form satu perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form satu perpanjangan not found'
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

    public function detailDataFormSatuPerpanjangan($id)
    {
        try {
            $dataFormSatuPerpanjangan = $this->formSatuPerpanjangan->find($id);
            if ($dataFormSatuPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'surat_disadaau_diskonsau' => $dataFormSatuPerpanjangan->surat_disadaau_diskonsau,
                        'skhpp_lama' => $dataFormSatuPerpanjangan->skhpp_lama,
                        'status' => $dataFormSatuPerpanjangan->status,
                        'catatan_revisi' => $dataFormSatuPerpanjangan->catatan_revisi,
                        'pic_perusahaan_litpers_id' => $dataFormSatuPerpanjangan->pic_perusahaan_litpers_id,
                        'admin_litpers_id' => $dataFormSatuPerpanjangan->admin_litpers_id,
                        'jenis_skhpp_id' => $dataFormSatuPerpanjangan->jenis_skhpp_id,
                        'created_at' => $dataFormSatuPerpanjangan->created_at,
                        'updated_at' => $dataFormSatuPerpanjangan->updated_at,
                        'files' => [
                            'surat_disadaau_diskonsau' => $dataFormSatuPerpanjangan->surat_disadaau_diskonsau,
                            'skhpp_lama' => $dataFormSatuPerpanjangan->skhpp_lama,
                        ]
                    ],
                    "message" => 'get detail data form satu perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form satu perpanjangan not found'
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

    public function inputDataFormSatuPerpanjangan(array $dataRequest)
    {
        try {
            $result = $this->formSatuPerpanjangan->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form satu perpanjangan success'
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

    public function updateDataFormSatuPerpanjangan($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormSatuPerpanjangan = $this->formSatuPerpanjangan->find($id);
            $dataFormSatuPerpanjangan->surat_disadaau_diskonsau = $dataRequest['surat_disadaau_diskonsau'];
            $dataFormSatuPerpanjangan->skhpp_lama = $dataRequest['skhpp_lama'];
            $dataFormSatuPerpanjangan->status = $dataRequest['status'];
            $dataFormSatuPerpanjangan->jenis_skhpp_id = $dataRequest['jenis_skhpp_id'];
            $dataFormSatuPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form satu perpanjangan success'
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

    public function deleteDataFormSatuPerpanjangan($id)
    {
        try {
            $dataFormSatuPerpanjangan = $this->formSatuPerpanjangan->find($id);
            if ($dataFormSatuPerpanjangan) {
                $dataFormSatuPerpanjangan->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form satu perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form satu perpanjangan tidak ditemukan'
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

    public function listDataFormDuaPerpanjangan()
    {
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->get();
            if ($dataFormDuaPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormDuaPerpanjangan,
                    "message" => 'get data form dua perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form dua perpanjangan not found'
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

    public function listDataFormDuaPerpanjanganByFormSatu($id)
    {
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->where('form_satu_perpanjangan_id', $id)->get();
            if ($dataFormDuaPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormDuaPerpanjangan,
                    "message" => 'get data form dua perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form dua perpanjangan not found'
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

    public function detailDataFormDuaPerpanjangan($id)
    {
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            if ($dataFormDuaPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'surat_permohonan_penerbitan' => $dataFormDuaPerpanjangan->surat_permohonan_penerbitan,
                        'akte_pendirian_perusahaan' => $dataFormDuaPerpanjangan->akte_pendirian_perusahaan,
                        'akte_perubahan_perusahaan' => $dataFormDuaPerpanjangan->akte_perubahan_perusahaan,
                        'nomor_izin_berusaha' => $dataFormDuaPerpanjangan->nomor_izin_berusaha,
                        'nomor_pokok_wajib_pajak' => $dataFormDuaPerpanjangan->nomor_pokok_wajib_pajak,
                        'surat_pengukuhan_pengusaha_kena_pajak' => $dataFormDuaPerpanjangan->surat_pengukuhan_pengusaha_kena_pajak,
                        'surat_pernyataan_sehat' => $dataFormDuaPerpanjangan->surat_pernyataan_sehat,
                        'referensi_bank' => $dataFormDuaPerpanjangan->referensi_bank,
                        'neraca_perusahaan_terakhir' => $dataFormDuaPerpanjangan->neraca_perusahaan_terakhir,
                        'rekening_koran_perusahaan' => $dataFormDuaPerpanjangan->rekening_koran_perusahaan,
                        'cv_direktur_utama' => $dataFormDuaPerpanjangan->cv_direktur_utama,
                        'ktp_jajaran_direksi' => $dataFormDuaPerpanjangan->ktp_jajaran_direksi,
                        'skck' => $dataFormDuaPerpanjangan->skck,
                        'company_profile' => $dataFormDuaPerpanjangan->company_profile,
                        'daftar_pengalaman_pekerjaan_di_tni_au' => $dataFormDuaPerpanjangan->daftar_pengalaman_pekerjaan_di_tni_au,
                        'daftar_peralatan_fasilitas_kantor' => $dataFormDuaPerpanjangan->daftar_peralatan_fasilitas_kantor,
                        'aset_perusahaan' => $dataFormDuaPerpanjangan->aset_perusahaan,
                        'surat_kemampuan_principle_agent' => $dataFormDuaPerpanjangan->surat_kemampuan_principle_agent,
                        'surat_loa_poa' => $dataFormDuaPerpanjangan->surat_loa_poa,
                        'supporting_letter_dari_vendor' => $dataFormDuaPerpanjangan->supporting_letter_dari_vendor,
                        'foto_direktur_4_6' => $dataFormDuaPerpanjangan->foto_direktur_4_6,
                        'kepemilikan_kantor' => $dataFormDuaPerpanjangan->kepemilikan_kantor,
                        'struktur_organisasi' => $dataFormDuaPerpanjangan->struktur_organisasi,
                        'foto_perusahaan' => $dataFormDuaPerpanjangan->foto_perusahaan,
                        'gambar_rute_denah_kantor' => $dataFormDuaPerpanjangan->gambar_rute_denah_kantor,
                        'kk_direktur_utama' => $dataFormDuaPerpanjangan->kk_direktur_utama,
                        'beranda_lpse' => $dataFormDuaPerpanjangan->beranda_lpse,
                        'e_catalog' => $dataFormDuaPerpanjangan->e_catalog,
                        'status' => $dataFormDuaPerpanjangan->status,
                        'catatan_revisi' => $dataFormDuaPerpanjangan->catatan_revisi,
                        'form_satu_perpanjangan_id' => $dataFormDuaPerpanjangan->form_satu_perpanjangan_id,
                        'created_at' => $dataFormDuaPerpanjangan->created_at,
                        'updated_at' => $dataFormDuaPerpanjangan->updated_at,
                        'files_pt1' => [
                            'surat_permohonan_penerbitan' => $dataFormDuaPerpanjangan->surat_permohonan_penerbitan,
                            'akte_pendirian_perusahaan' => $dataFormDuaPerpanjangan->akte_pendirian_perusahaan,
                            'akte_perubahan_perusahaan' => $dataFormDuaPerpanjangan->akte_perubahan_perusahaan,
                            'nomor_izin_berusaha' => $dataFormDuaPerpanjangan->nomor_izin_berusaha,
                            'nomor_pokok_wajib_pajak' => $dataFormDuaPerpanjangan->nomor_pokok_wajib_pajak,
                        ],
                        'files_pt2' => [
                            'surat_pengukuhan_pengusaha_kena_pajak' => $dataFormDuaPerpanjangan->surat_pengukuhan_pengusaha_kena_pajak,
                            'surat_pernyataan_sehat' => $dataFormDuaPerpanjangan->surat_pernyataan_sehat,
                            'referensi_bank' => $dataFormDuaPerpanjangan->referensi_bank,
                            'neraca_perusahaan_terakhir' => $dataFormDuaPerpanjangan->neraca_perusahaan_terakhir,
                            'rekening_koran_perusahaan' => $dataFormDuaPerpanjangan->rekening_koran_perusahaan,
                        ],
                        'files_pt3' => [
                            'cv_direktur_utama' => $dataFormDuaPerpanjangan->cv_direktur_utama,
                            'ktp_jajaran_direksi' => $dataFormDuaPerpanjangan->ktp_jajaran_direksi,
                            'skck' => $dataFormDuaPerpanjangan->skck,
                            'company_profile' => $dataFormDuaPerpanjangan->company_profile,
                            'daftar_pengalaman_pekerjaan_di_tni_au' => $dataFormDuaPerpanjangan->daftar_pengalaman_pekerjaan_di_tni_au,
                        ],
                        'files_pt4' => [
                            'daftar_peralatan_fasilitas_kantor' => $dataFormDuaPerpanjangan->daftar_peralatan_fasilitas_kantor,
                            'aset_perusahaan' => $dataFormDuaPerpanjangan->aset_perusahaan,
                            'surat_kemampuan_principle_agent' => $dataFormDuaPerpanjangan->surat_kemampuan_principle_agent,
                            'surat_loa_poa' => $dataFormDuaPerpanjangan->surat_loa_poa,
                            'supporting_letter_dari_vendor' => $dataFormDuaPerpanjangan->supporting_letter_dari_vendor,
                        ],
                        'files_pt5' => [
                            'foto_direktur_4_6' => $dataFormDuaPerpanjangan->foto_direktur_4_6,
                            'kepemilikan_kantor' => $dataFormDuaPerpanjangan->kepemilikan_kantor,
                            'struktur_organisasi' => $dataFormDuaPerpanjangan->struktur_organisasi,
                            'foto_perusahaan' => $dataFormDuaPerpanjangan->foto_perusahaan,
                            'gambar_rute_denah_kantor' => $dataFormDuaPerpanjangan->gambar_rute_denah_kantor,
                        ],
                        'files_pt6' => [
                            'kk_direktur_utama' => $dataFormDuaPerpanjangan->kk_direktur_utama,
                            'beranda_lpse' => $dataFormDuaPerpanjangan->beranda_lpse,
                            'e_catalog' => $dataFormDuaPerpanjangan->e_catalog,
                        ]
                    ],
                    "message" => 'get detail data form dua perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form dua perpanjangan not found'
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

    public function inputDataFormDuaPerpanjanganPartSatu(array $dataRequest)
    {
        try {
            $result = $this->formDuaPerpanjangan->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form dua perpanjangan part satu success'
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

    public function inputDataFormDuaPerpanjanganPartDua(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->surat_pengukuhan_pengusaha_kena_pajak = $dataRequest['surat_pengukuhan_pengusaha_kena_pajak'];
            $dataFormDuaPerpanjangan->surat_pernyataan_sehat = $dataRequest['surat_pernyataan_sehat'];
            $dataFormDuaPerpanjangan->referensi_bank = $dataRequest['referensi_bank'];
            $dataFormDuaPerpanjangan->neraca_perusahaan_terakhir = $dataRequest['neraca_perusahaan_terakhir'];
            $dataFormDuaPerpanjangan->rekening_koran_perusahaan = $dataRequest['rekening_koran_perusahaan'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form dua perpanjangan part dua success'
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

    public function inputDataFormDuaPerpanjanganPartTiga(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->cv_direktur_utama = $dataRequest['cv_direktur_utama'];
            $dataFormDuaPerpanjangan->ktp_jajaran_direksi = $dataRequest['ktp_jajaran_direksi'];
            $dataFormDuaPerpanjangan->skck = $dataRequest['skck'];
            $dataFormDuaPerpanjangan->company_profile = $dataRequest['company_profile'];
            $dataFormDuaPerpanjangan->daftar_pengalaman_pekerjaan_di_tni_au = $dataRequest['daftar_pengalaman_pekerjaan_di_tni_au'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form dua perpanjangan part tiga success'
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

    public function inputDataFormDuaPerpanjanganPartEmpat(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->daftar_peralatan_fasilitas_kantor = $dataRequest['daftar_peralatan_fasilitas_kantor'];
            $dataFormDuaPerpanjangan->aset_perusahaan = $dataRequest['aset_perusahaan'];
            $dataFormDuaPerpanjangan->surat_kemampuan_principle_agent = $dataRequest['surat_kemampuan_principle_agent'];
            $dataFormDuaPerpanjangan->surat_loa_poa = $dataRequest['surat_loa_poa'];
            $dataFormDuaPerpanjangan->supporting_letter_dari_vendor = $dataRequest['supporting_letter_dari_vendor'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form dua perpanjangan part empat success'
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

    public function inputDataFormDuaPerpanjanganPartLima(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->foto_direktur_4_6 = $dataRequest['foto_direktur_4_6'];
            $dataFormDuaPerpanjangan->kepemilikan_kantor = $dataRequest['kepemilikan_kantor'];
            $dataFormDuaPerpanjangan->struktur_organisasi = $dataRequest['struktur_organisasi'];
            $dataFormDuaPerpanjangan->foto_perusahaan = $dataRequest['foto_perusahaan'];
            $dataFormDuaPerpanjangan->gambar_rute_denah_kantor = $dataRequest['gambar_rute_denah_kantor'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form dua perpanjangan part lima success'
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

    public function inputDataFormDuaPerpanjanganPartEnam(array $dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->kk_direktur_utama = $dataRequest['kk_direktur_utama'];
            $dataFormDuaPerpanjangan->beranda_lpse = $dataRequest['beranda_lpse'];
            $dataFormDuaPerpanjangan->e_catalog = $dataRequest['e_catalog'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data form dua perpanjangan part lima success'
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

    public function updateDataFormDuaPerpanjanganPartSatu($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->surat_permohonan_penerbitan = $dataRequest['surat_permohonan_penerbitan'];
            $dataFormDuaPerpanjangan->akte_pendirian_perusahaan = $dataRequest['akte_pendirian_perusahaan'];
            $dataFormDuaPerpanjangan->akte_perubahan_perusahaan = $dataRequest['akte_perubahan_perusahaan'];
            $dataFormDuaPerpanjangan->nomor_izin_berusaha = $dataRequest['nomor_izin_berusaha'];
            $dataFormDuaPerpanjangan->nomor_pokok_wajib_pajak = $dataRequest['nomor_pokok_wajib_pajak'];
            $dataFormDuaPerpanjangan->status = $dataRequest['status'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form dua perpanjangan part satu success'
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

    public function updateDataFormDuaPerpanjanganPartDua($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->surat_pengukuhan_pengusaha_kena_pajak = $dataRequest['surat_pengukuhan_pengusaha_kena_pajak'];
            $dataFormDuaPerpanjangan->surat_pernyataan_sehat = $dataRequest['surat_pernyataan_sehat'];
            $dataFormDuaPerpanjangan->referensi_bank = $dataRequest['referensi_bank'];
            $dataFormDuaPerpanjangan->neraca_perusahaan_terakhir = $dataRequest['neraca_perusahaan_terakhir'];
            $dataFormDuaPerpanjangan->rekening_koran_perusahaan = $dataRequest['rekening_koran_perusahaan'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form dua perpanjangan part dua success'
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

    public function updateDataFormDuaPerpanjanganPartTiga($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->cv_direktur_utama = $dataRequest['cv_direktur_utama'];
            $dataFormDuaPerpanjangan->ktp_jajaran_direksi = $dataRequest['ktp_jajaran_direksi'];
            $dataFormDuaPerpanjangan->skck = $dataRequest['skck'];
            $dataFormDuaPerpanjangan->company_profile = $dataRequest['company_profile'];
            $dataFormDuaPerpanjangan->daftar_pengalaman_pekerjaan_di_tni_au = $dataRequest['daftar_pengalaman_pekerjaan_di_tni_au'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form dua perpanjangan part tiga success'
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

    public function updateDataFormDuaPerpanjanganPartEmpat($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->daftar_peralatan_fasilitas_kantor = $dataRequest['daftar_peralatan_fasilitas_kantor'];
            $dataFormDuaPerpanjangan->aset_perusahaan = $dataRequest['aset_perusahaan'];
            $dataFormDuaPerpanjangan->surat_kemampuan_principle_agent = $dataRequest['surat_kemampuan_principle_agent'];
            $dataFormDuaPerpanjangan->surat_loa_poa = $dataRequest['surat_loa_poa'];
            $dataFormDuaPerpanjangan->supporting_letter_dari_vendor = $dataRequest['supporting_letter_dari_vendor'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form dua perpanjangan part empat success'
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

    public function updateDataFormDuaPerpanjanganPartLima($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->foto_direktur_4_6 = $dataRequest['foto_direktur_4_6'];
            $dataFormDuaPerpanjangan->kepemilikan_kantor = $dataRequest['kepemilikan_kantor'];
            $dataFormDuaPerpanjangan->struktur_organisasi = $dataRequest['struktur_organisasi'];
            $dataFormDuaPerpanjangan->foto_perusahaan = $dataRequest['foto_perusahaan'];
            $dataFormDuaPerpanjangan->gambar_rute_denah_kantor = $dataRequest['gambar_rute_denah_kantor'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form dua perpanjangan part lima success'
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

    public function updateDataFormDuaPerpanjanganPartEnam($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            $dataFormDuaPerpanjangan->kk_direktur_utama = $dataRequest['kk_direktur_utama'];
            $dataFormDuaPerpanjangan->beranda_lpse = $dataRequest['beranda_lpse'];
            $dataFormDuaPerpanjangan->e_catalog = $dataRequest['e_catalog'];
            $dataFormDuaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form dua perpanjangan part enam success'
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

    public function deleteDataFormDuaPerpanjangan($id)
    {
        try {
            $dataFormDuaPerpanjangan = $this->formDuaPerpanjangan->find($id);
            if ($dataFormDuaPerpanjangan) {
                $dataFormDuaPerpanjangan->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form dua perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form dua perpanjangan tidak ditemukan'
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

    public function listDataFormTigaPerpanjangan()
    {
        try {
            $dataFormTigaPerpanjangan = $this->formTigaPerpanjangan->get();
            if ($dataFormTigaPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormTigaPerpanjangan,
                    "message" => 'get data form tiga perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form tiga perpanjangan not found'
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

    public function listDataFormTigaPerpanjanganByFormDua($id)
    {
        try {
            $dataFormTigaPerpanjangan = $this->formTigaPerpanjangan->where('form_dua_perpanjangan_id', $id)->get();
            if ($dataFormTigaPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormTigaPerpanjangan,
                    "message" => 'get data form tiga perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form tiga perpanjangan not found'
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

    public function detailDataFormTigaPerpanjangan($id)
    {
        try {
            $dataFormTigaPerpanjangan = $this->formTigaPerpanjangan->find($id);
            if ($dataFormTigaPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormTigaPerpanjangan,
                    "message" => 'get detail data form tiga perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form tiga perpanjangan not found'
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

    public function inputDataFormTigaPerpanjangan($dataRequest)
    {
        try {
            $result = $this->formTigaPerpanjangan->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form tiga perpanjangan success'
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

    public function updateDataFormTigaPerpanjangan($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormTigaPerpanjangan = $this->formTigaPerpanjangan->find($id);
            $dataFormTigaPerpanjangan->jadwal_survei = $dataRequest['jadwal_survei'];
            $dataFormTigaPerpanjangan->status = $dataRequest['status'];
            $dataFormTigaPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form tiga perpanjangan success'
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

    public function deleteDataFormTigaPerpanjangan($id)
    {
        try {
            $dataFormTigaPerpanjangan = $this->formTigaPerpanjangan->find($id);
            if ($dataFormTigaPerpanjangan) {
                $dataFormTigaPerpanjangan->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form tiga perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form tiga perpanjangan tidak ditemukan'
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

    public function listDataFormEmpatPerpanjangan()
    {
        try {
            $dataFormEmpatPerpanjangan = $this->formEmpatPerpanjangan->get();
            if ($dataFormEmpatPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormEmpatPerpanjangan,
                    "message" => 'get data form empat perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form empat perpanjangan not found'
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

    public function listDataFormEmpatPerpanjanganByFormTiga($id)
    {
        try {
            $dataFormEmpatPerpanjangan = $this->formEmpatPerpanjangan->where('form_tiga_perpanjangan_id', $id)->get();
            if ($dataFormEmpatPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataFormEmpatPerpanjangan,
                    "message" => 'get data form empat perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data form empat perpanjangan not found'
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

    public function detailDataFormEmpatPerpanjanganByFormTiga($id)
    {
        try {
            $dataFormEmpatPerpanjangan = $this->formEmpatPerpanjangan->where('form_tiga_perpanjangan_id', $id)->first();
            if ($dataFormEmpatPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'skhpp' => $dataFormEmpatPerpanjangan->skhpp,
                        'tanggal_awal_berlaku' => $dataFormEmpatPerpanjangan->tanggal_awal_berlaku,
                        'tanggal_akhir_berlaku' => $dataFormEmpatPerpanjangan->tanggal_akhir_berlaku,
                        'status' => $dataFormEmpatPerpanjangan->status,
                        'catatan_revisi' => $dataFormEmpatPerpanjangan->catatan_revisi,
                        'form_tiga_perpanjangan_id' => $dataFormEmpatPerpanjangan->form_tiga_perpanjangan_id,
                        'created_at' => $dataFormEmpatPerpanjangan->created_at,
                        'updated_at' => $dataFormEmpatPerpanjangan->updated_at,
                        'files' => [
                            'skhpp' => $dataFormEmpatPerpanjangan->skhpp,
                        ]
                    ],
                    "message" => 'get detail data form empat perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form empat perpanjangan not found'
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

    public function detailDataFormEmpatPerpanjangan($id)
    {
        try {
            $dataFormEmpatPerpanjangan = $this->formEmpatPerpanjangan->find($id);
            if ($dataFormEmpatPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => [
                        'skhpp' => $dataFormEmpatPerpanjangan->skhpp,
                        'tanggal_awal_berlaku' => $dataFormEmpatPerpanjangan->tanggal_awal_berlaku,
                        'tanggal_akhir_berlaku' => $dataFormEmpatPerpanjangan->tanggal_akhir_berlaku,
                        'status' => $dataFormEmpatPerpanjangan->status,
                        'catatan_revisi' => $dataFormEmpatPerpanjangan->catatan_revisi,
                        'form_tiga_perpanjangan_id' => $dataFormEmpatPerpanjangan->form_tiga_perpanjangan_id,
                        'created_at' => $dataFormEmpatPerpanjangan->created_at,
                        'updated_at' => $dataFormEmpatPerpanjangan->updated_at,
                        'files' => [
                            'skhpp' => $dataFormEmpatPerpanjangan->skhpp,
                        ]
                    ],
                    "message" => 'get detail data form empat perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data form empat perpanjangan not found'
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

    public function inputDataFormEmpatPerpanjangan(array $dataRequest)
    {
        try {
            $result = $this->formEmpatPerpanjangan->insertGetId($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "id" => $result
                ],
                "message" => 'input data form empat perpanjangan success'
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

    public function updateDataFormEmpatPerpanjangan($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataFormEmpatPerpanjangan = $this->formEmpatPerpanjangan->find($id);
            if (isset($dataRequest['skhpp'])) {
                $dataFormEmpatPerpanjangan->skhpp = $dataRequest['skhpp'];
            }
            $dataFormEmpatPerpanjangan->tanggal_awal_berlaku = $dataRequest['tanggal_awal_berlaku'];
            $dataFormEmpatPerpanjangan->tanggal_akhir_berlaku = $dataRequest['tanggal_akhir_berlaku'];
            $dataFormEmpatPerpanjangan->status = $dataRequest['status'];
            $dataFormEmpatPerpanjangan->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data form empat perpanjangan success'
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

    public function deleteDataFormEmpatPerpanjangan($id)
    {
        try {
            $dataFormEmpatPerpanjangan = $this->formEmpatPerpanjangan->where('form_tiga_perpanjangan_id', $id)->first();
            if ($dataFormEmpatPerpanjangan) {
                $dataFormEmpatPerpanjangan->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data form empat perpanjangan success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data form empat perpanjangan tidak ditemukan'
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

    public function verifyFormSatuPerpanjangan(array $dataRequest, $id)
    {
        try {
            $dataFormSatu = $this->formSatuPerpanjangan->find($id);
            if ($dataFormSatu) {
                $dataFormSatu->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormSatu->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormSatu->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form satu perpanjangan berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form satu perpanjangan gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormDuaPerpanjangan(array $dataRequest, $id)
    {
        try {
            $dataFormDua = $this->formDuaPerpanjangan->find($id);
            if ($dataFormDua) {
                $dataFormDua->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormDua->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormDua->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form dua perpanjangan berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form dua perpanjangan gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormTigaPerpanjangan(array $dataRequest, $id)
    {
        try {
            $dataFormTiga = $this->formTigaPerpanjangan->find($id);
            if ($dataFormTiga) {
                $dataFormTiga->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormTiga->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormTiga->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form tiga perpanjangan berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form tiga perpanjangan gagal'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function verifyFormEmpatPerpanjangan(array $dataRequest, $id)
    {
        try {
            $dataFormEmpat = $this->formEmpatPerpanjangan->find($id);
            if ($dataFormEmpat) {
                $dataFormEmpat->status = $dataRequest['status'];
                if (isset($dataRequest['catatan_revisi'])) {
                    $dataFormEmpat->catatan_revisi = $dataRequest['catatan_revisi'];
                }
                $dataFormEmpat->save();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'verifikasi form empat perpanjangan berhasil'
                ];
            }
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => 'verifikasi form empat perpanjangan gagal'
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
