<?php

namespace App\Repositories\Litpers;

use App\Models\JadwalSurveiPergantianDireksi;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JadwalSurveiPergantianDireksiRepository
{
    private $jadwalSurveiPergantianDireksiModel;

    public function __construct(JadwalSurveiPergantianDireksi $jadwalSurveiPergantianDireksiModel)
    {
        $this->jadwalSurveiPergantianDireksiModel = $jadwalSurveiPergantianDireksiModel;
    }

    public function listDataJadwalSurveiPergantianDireksi($id)
    {
        try {
            $dataJadwalSurveiPergantianDireksi = $this->jadwalSurveiPergantianDireksiModel->where('f_tiga_pergantian_id', $id)->get();
            if ($dataJadwalSurveiPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJadwalSurveiPergantianDireksi,
                    "message" => 'get data jadwal survei success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data jadwal survei not found'
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

    public function detailDataJadwalSurveiPergantianDireksi($id)
    {
        try {
            $dataJadwalSurveiPergantianDireksi = $this->jadwalSurveiPergantianDireksiModel->where('f_tiga_pergantian_id', $id)->first();
            if ($dataJadwalSurveiPergantianDireksi) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJadwalSurveiPergantianDireksi,
                    "message" => 'get detail data jadwal survei success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data jadwal survei not found'
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

    public function inputDataJadwalSurveiPergantianDireksi($dataRequest)
    {
        try {
            $result = $this->jadwalSurveiPergantianDireksiModel->insert($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data jadwal survei success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataJadwalSurveiPergantianDireksi($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataJadwalSurveiPergantianDireksi = $this->jadwalSurveiPergantianDireksiModel->find($id);
            $dataJadwalSurveiPergantianDireksi->nama_admin = $dataRequest['nama_admin'];
            $dataJadwalSurveiPergantianDireksi->tanggal_awal = $dataRequest['tanggal_awal'];
            $dataJadwalSurveiPergantianDireksi->tanggal_akhir = $dataRequest['tanggal_akhir'];
            $dataJadwalSurveiPergantianDireksi->alamat_survei = $dataRequest['alamat_survei'];
            $dataJadwalSurveiPergantianDireksi->hp_admin = $dataRequest['hp_admin'];
            $dataJadwalSurveiPergantianDireksi->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data jadwal survei success'
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

    public function deleteDataJadwalSurveiPergantianDireksi($id)
    {
        try {
            $jadwalSurveiPergantianDireksi = $this->jadwalSurveiPergantianDireksiModel->find($id);
            if ($jadwalSurveiPergantianDireksi) {
                $jadwalSurveiPergantianDireksi->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data jadwal survei success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data jadwal survei tidak ditemukan'
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
