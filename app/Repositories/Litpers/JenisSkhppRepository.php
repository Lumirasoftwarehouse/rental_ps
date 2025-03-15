<?php

namespace App\Repositories\Litpers;

use App\Models\FormSatuPenerbitanBaru;
use App\Models\FormSatuPergantianDireksi;
use App\Models\FormSatuPerpanjangan;
use App\Models\JenisSkhpp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JenisSkhppRepository
{
    private $jenisSkhppModel;

    public function __construct(JenisSkhpp $jenisSkhppModel)
    {
        $this->jenisSkhppModel = $jenisSkhppModel;
    }

    public function jumlahDataSkhpp()
    {
        try {
            $pengajuanPenerbitan = FormSatuPenerbitanBaru::with(['formDua.formTiga.formEmpat.formLima'])->get();
            $pengajuanPergantian = FormSatuPergantianDireksi::with(['formDua.formTiga.formEmpat.formLima'])->get();
            $pengajuanPerpanjangan = FormSatuPerpanjangan::with(['formDua.formTiga.formEmpat'])->get();

            $processed_skhpp = 0;
            $approved_skhpp = 0;
            $rejected_skhpp = 0;

            $allPengajuan = [$pengajuanPenerbitan, $pengajuanPergantian, $pengajuanPerpanjangan];

            foreach ($allPengajuan as $pengajuan) {
                foreach ($pengajuan as $formSatu) {
                    $this->countStatus($formSatu, $processed_skhpp, $approved_skhpp, $rejected_skhpp);
                }
            }

            return [
                "id" => '1',
                "statusCode" => 200,
                "data" => [
                    "processed_skhpp" => $processed_skhpp,
                    "approved_skhpp" => $approved_skhpp,
                    "rejected_skhpp" => $rejected_skhpp
                ],
                "message" => 'SKHPP status count retrieved successfully.'
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

    private function countStatus($form, &$processed_skhpp, &$approved_skhpp, &$rejected_skhpp)
    {
        // Ensure $form is a valid object and has a 'status' property
        if (is_object($form) && isset($form->status)) {
            if ($form->status == 0) {
                $processed_skhpp++;
            } elseif ($form->status == 1) {
                $approved_skhpp++;
            } elseif ($form->status == 2) {
                $rejected_skhpp++;
            }
        }

        // Ensure $form->formDua is not null or a boolean
        if (!empty($form->formDua) && is_array($form->formDua)) {
            foreach ($form->formDua as $formDua) {
                $this->countStatus($formDua, $processed_skhpp, $approved_skhpp, $rejected_skhpp);

                if (!empty($formDua->formTiga) && is_object($formDua->formTiga)) {
                    $this->countStatus($formDua->formTiga, $processed_skhpp, $approved_skhpp, $rejected_skhpp);

                    if (!empty($formDua->formTiga->formEmpat) && is_object($formDua->formTiga->formEmpat)) {
                        $this->countStatus($formDua->formTiga->formEmpat, $processed_skhpp, $approved_skhpp, $rejected_skhpp);

                        if (!empty($formDua->formTiga->formEmpat->formLima) && is_object($formDua->formTiga->formEmpat->formLima)) {
                            $this->countStatus($formDua->formTiga->formEmpat->formLima, $processed_skhpp, $approved_skhpp, $rejected_skhpp);
                        }
                    }
                }
            }
        }
    }

    public function listDataAllSkhpp()
    {
        try {
            $pengajuanPenerbitan = FormSatuPenerbitanBaru::with(['formDua.formTiga.formEmpat.formLima'])
                ->get();
            $pengajuanPergantian = FormSatuPergantianDireksi::with(['formDua.formTiga.formEmpat.formLima'])
                ->get();
            $pengajuanPerpanjangan = FormSatuPerpanjangan::with(['formDua.formTiga.formEmpat'])
                ->get();

            $response = [];

            foreach ($pengajuanPenerbitan as $formSatu) {
                if ($formSatu->formDua->isEmpty()) {
                    // Progress is 1 if only formSatu exists
                    $response[] = [
                        'progress' => 1,
                        'id' => $formSatu->id,
                        'status' => $formSatu->status,
                        'jenis_skhpp' => 'penerbitan'
                    ];
                } else {
                    foreach ($formSatu->formDua as $formDua) {
                        if (is_null($formDua->formTiga)) {
                            // Progress is 2 if formDua exists but formTiga is null
                            $response[] = [
                                'progress' => 2,
                                'id' => $formDua->id,
                                'status' => $formDua->status,
                                'jenis_skhpp' => 'penerbitan'
                            ];
                        } else {
                            if (is_null($formDua->formTiga->formEmpat)) {
                                // Progress is 3 if formTiga exists but formEmpat is null
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                    'jenis_skhpp' => 'penerbitan'
                                ];
                            } else {
                                if (is_null($formDua->formTiga->formEmpat->formLima)) {
                                    $response[] = [
                                        'progress' => 4,
                                        'id' => $formDua->formTiga->formEmpat->id,
                                        'status' => $formDua->formTiga->formEmpat->status,
                                        'jenis_skhpp' => 'penerbitan'
                                    ];
                                } else {
                                    if ($formDua->formTiga->formEmpat->formLima != null) {
                                        $response[] = [
                                            'progress' => 5,
                                            'id' => $formDua->formTiga->formEmpat->formLima->form_empat_penerbitan_baru_id,
                                            'status' => $formDua->formTiga->formEmpat->formLima->status,
                                            'jenis_skhpp' => 'penerbitan'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            foreach ($pengajuanPergantian as $formSatu) {
                if ($formSatu->formDua->isEmpty()) {
                    // Progress is 1 if only formSatu exists
                    $response[] = [
                        'progress' => 1,
                        'id' => $formSatu->id,
                        'status' => $formSatu->status,
                        'jenis_skhpp' => 'pergantian'
                    ];
                } else {
                    foreach ($formSatu->formDua as $formDua) {
                        if (is_null($formDua->formTiga)) {
                            // Progress is 2 if formDua exists but formTiga is null
                            $response[] = [
                                'progress' => 2,
                                'id' => $formDua->id,
                                'status' => $formDua->status,
                                'jenis_skhpp' => 'pergantian'
                            ];
                        } else {
                            if (is_null($formDua->formTiga->formEmpat)) {
                                // Progress is 3 if formTiga exists but formEmpat is null
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                    'jenis_skhpp' => 'pergantian'
                                ];
                            } else {
                                if (is_null($formDua->formTiga->formEmpat->formLima)) {
                                    $response[] = [
                                        'progress' => 4,
                                        'id' => $formDua->formTiga->formEmpat->id,
                                        'status' => $formDua->formTiga->formEmpat->status,
                                        'jenis_skhpp' => 'pergantian'
                                    ];
                                } else {
                                    if ($formDua->formTiga->formEmpat->formLima != null) {
                                        $response[] = [
                                            'progress' => 5,
                                            'id' => $formDua->formTiga->formEmpat->formLima->id,
                                            'status' => $formDua->formTiga->formEmpat->formLima->status,
                                            'jenis_skhpp' => 'pergantian'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            foreach ($pengajuanPerpanjangan as $formSatu) {
                if ($formSatu->formDua->isEmpty()) {
                    // Progress is 1 if only formSatu exists
                    $response[] = [
                        'progress' => 1,
                        'id' => $formSatu->id,
                        'status' => $formSatu->status,
                        'jenis_skhpp' => 'perpanjangan'
                    ];
                } else {
                    foreach ($formSatu->formDua as $formDua) {
                        if (is_null($formDua->formTiga)) {
                            // Progress is 2 if formDua exists but formTiga is null
                            $response[] = [
                                'progress' => 2,
                                'id' => $formDua->id,
                                'status' => $formDua->status,
                                'jenis_skhpp' => 'perpanjangan'
                            ];
                        } else {
                            if (is_null($formDua->formTiga->formEmpat)) {
                                // Progress is 3 if formTiga exists but formEmpat is null
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                    'jenis_skhpp' => 'perpanjangan'
                                ];
                            } else {
                                if ($formDua->formTiga->formEmpat != null) {
                                    $response[] = [
                                        'progress' => 4,
                                        'id' => $formDua->formTiga->formEmpat->id,
                                        'status' => $formDua->formTiga->formEmpat->status,
                                        'jenis_skhpp' => 'perpanjangan'
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

    public function listDataAllSkhppByMitra($id)
    {
        try {
            $pengajuanPenerbitan = FormSatuPenerbitanBaru::where('pic_perusahaan_litpers_id', $id)
                ->with(['formDua.formTiga.formEmpat.formLima'])
                ->get();
            $pengajuanPergantian = FormSatuPergantianDireksi::where('pic_perusahaan_litpers_id', $id)
                ->with(['formDua.formTiga.formEmpat.formLima'])
                ->get();
            $pengajuanPerpanjangan = FormSatuPerpanjangan::where('pic_perusahaan_litpers_id', $id)
                ->with(['formDua.formTiga.formEmpat'])
                ->get();

            $response = [];

            foreach ($pengajuanPenerbitan as $formSatu) {
                if ($formSatu->formDua->isEmpty()) {
                    // Progress is 1 if only formSatu exists
                    $response[] = [
                        'progress' => 1,
                        'id' => $formSatu->id,
                        'status' => $formSatu->status,
                        'jenis_skhpp' => 'penerbitan'
                    ];
                } else {
                    foreach ($formSatu->formDua as $formDua) {
                        if (is_null($formDua->formTiga)) {
                            // Progress is 2 if formDua exists but formTiga is null
                            $response[] = [
                                'progress' => 2,
                                'id' => $formDua->id,
                                'status' => $formDua->status,
                                'jenis_skhpp' => 'penerbitan'
                            ];
                        } else {
                            if (is_null($formDua->formTiga->formEmpat)) {
                                // Progress is 3 if formTiga exists but formEmpat is null
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                    'jenis_skhpp' => 'penerbitan'
                                ];
                            } else {
                                if (is_null($formDua->formTiga->formEmpat->formLima)) {
                                    $response[] = [
                                        'progress' => 4,
                                        'id' => $formDua->formTiga->formEmpat->id,
                                        'status' => $formDua->formTiga->formEmpat->status,
                                        'jenis_skhpp' => 'penerbitan'
                                    ];
                                } else {
                                    if ($formDua->formTiga->formEmpat->formLima != null) {
                                        $response[] = [
                                            'progress' => 5,
                                            'id' => $formDua->formTiga->formEmpat->formLima->form_empat_penerbitan_baru_id,
                                            'status' => $formDua->formTiga->formEmpat->formLima->status,
                                            'jenis_skhpp' => 'penerbitan'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            foreach ($pengajuanPergantian as $formSatu) {
                if ($formSatu->formDua->isEmpty()) {
                    // Progress is 1 if only formSatu exists
                    $response[] = [
                        'progress' => 1,
                        'id' => $formSatu->id,
                        'status' => $formSatu->status,
                        'jenis_skhpp' => 'pergantian'
                    ];
                } else {
                    foreach ($formSatu->formDua as $formDua) {
                        if (is_null($formDua->formTiga)) {
                            // Progress is 2 if formDua exists but formTiga is null
                            $response[] = [
                                'progress' => 2,
                                'id' => $formDua->id,
                                'status' => $formDua->status,
                                'jenis_skhpp' => 'pergantian'
                            ];
                        } else {
                            if (is_null($formDua->formTiga->formEmpat)) {
                                // Progress is 3 if formTiga exists but formEmpat is null
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                    'jenis_skhpp' => 'pergantian'
                                ];
                            } else {
                                if (is_null($formDua->formTiga->formEmpat->formLima)) {
                                    $response[] = [
                                        'progress' => 4,
                                        'id' => $formDua->formTiga->formEmpat->id,
                                        'status' => $formDua->formTiga->formEmpat->status,
                                        'jenis_skhpp' => 'pergantian'
                                    ];
                                } else {
                                    if ($formDua->formTiga->formEmpat->formLima != null) {
                                        $response[] = [
                                            'progress' => 5,
                                            'id' => $formDua->formTiga->formEmpat->formLima->form_empat_pergantian_id,
                                            'status' => $formDua->formTiga->formEmpat->formLima->status,
                                            'jenis_skhpp' => 'pergantian'
                                        ];
                                    }
                                }
                            }
                        }
                    }
                }
            }

            foreach ($pengajuanPerpanjangan as $formSatu) {
                if ($formSatu->formDua->isEmpty()) {
                    // Progress is 1 if only formSatu exists
                    $response[] = [
                        'progress' => 1,
                        'id' => $formSatu->id,
                        'status' => $formSatu->status,
                        'jenis_skhpp' => 'perpanjangan'
                    ];
                } else {
                    foreach ($formSatu->formDua as $formDua) {
                        if (is_null($formDua->formTiga)) {
                            // Progress is 2 if formDua exists but formTiga is null
                            $response[] = [
                                'progress' => 2,
                                'id' => $formDua->id,
                                'status' => $formDua->status,
                                'jenis_skhpp' => 'perpanjangan'
                            ];
                        } else {
                            if (is_null($formDua->formTiga->formEmpat)) {
                                // Progress is 3 if formTiga exists but formEmpat is null
                                $response[] = [
                                    'progress' => 3,
                                    'id' => $formDua->formTiga->id,
                                    'status' => $formDua->formTiga->status,
                                    'jenis_skhpp' => 'perpanjangan'
                                ];
                            } else {
                                if ($formDua->formTiga->formEmpat != null) {
                                    $response[] = [
                                        'progress' => 4,
                                        'id' => $formDua->formTiga->formEmpat->form_tiga_perpanjangan_id,
                                        'status' => $formDua->formTiga->formEmpat->status,
                                        'jenis_skhpp' => 'perpanjangan'
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

    public function listDataJenisSkhpp()
    {
        try {
            $dataJenisSkhpp = $this->jenisSkhppModel->get();
            if ($dataJenisSkhpp) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJenisSkhpp,
                    "message" => 'get data jenis skhpp success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data jenis skhpp not found'
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

    public function detailDataJenisSkhpp($id)
    {
        try {
            $dataJenisSkhpp = $this->jenisSkhppModel->find($id);
            if ($dataJenisSkhpp) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJenisSkhpp,
                    "message" => 'get detail data jenis skhpp success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data jenis skhpp not found'
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

    public function inputDataJenisSkhpp($dataRequest)
    {
        try {
            $result = $this->jenisSkhppModel->insert($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data jenis skhpp success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataJenisSkhpp($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataJenisSkhpp = $this->jenisSkhppModel->find($id);
            $dataJenisSkhpp->jenis_skhpp = $dataRequest['jenis_skhpp'];
            $dataJenisSkhpp->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data jenis skhpp success'
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

    public function deleteDataJenisSkhpp($id)
    {
        try {
            $jenisSkhpp = $this->jenisSkhppModel->find($id);
            if ($jenisSkhpp) {
                $jenisSkhpp->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data jenis skhpp success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data jenis skhpp tidak ditemukan'
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
}
