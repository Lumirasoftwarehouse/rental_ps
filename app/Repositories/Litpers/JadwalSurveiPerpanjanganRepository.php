<?php

namespace App\Repositories\Litpers;

use App\Models\JadwalSurveiPerpanjangan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JadwalSurveiPerpanjanganRepository
{
    private $jadwalSurveiPerpanjanganModel;

    public function __construct(JadwalSurveiPerpanjangan $jadwalSurveiPerpanjanganModel)
    {
        $this->jadwalSurveiPerpanjanganModel = $jadwalSurveiPerpanjanganModel;
    }

    public function listDataJadwalSurveiPerpanjangan($id)
    {
        try {
            $dataJadwalSurveiPerpanjangan = $this->jadwalSurveiPerpanjanganModel->where('f_dua_perpanjangan_id', $id)->get();
            if ($dataJadwalSurveiPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJadwalSurveiPerpanjangan,
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

    public function detailDataJadwalSurveiPerpanjangan($id)
    {
        try {
            $dataJadwalSurveiPerpanjangan = $this->jadwalSurveiPerpanjanganModel->where('f_dua_perpanjangan_id', $id)->first();;
            if ($dataJadwalSurveiPerpanjangan) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJadwalSurveiPerpanjangan,
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

    public function inputDataJadwalSurveiPerpanjangan($dataRequest)
    {
        try {
            $result = $this->jadwalSurveiPerpanjanganModel->insert($dataRequest);
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

    public function updateDataJadwalSurveiPerpanjangan($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataJadwalSurveiPerpanjangan = $this->jadwalSurveiPerpanjanganModel->find($id);
            $dataJadwalSurveiPerpanjangan->nama_admin = $dataRequest['nama_admin'];
            $dataJadwalSurveiPerpanjangan->tanggal_awal = $dataRequest['tanggal_awal'];
            $dataJadwalSurveiPerpanjangan->tanggal_akhir = $dataRequest['tanggal_akhir'];
            $dataJadwalSurveiPerpanjangan->alamat_survei = $dataRequest['alamat_survei'];
            $dataJadwalSurveiPerpanjangan->hp_admin = $dataRequest['hp_admin'];
            $dataJadwalSurveiPerpanjangan->save();

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

    public function deleteDataJadwalSurveiPerpanjangan($id)
    {
        try {
            $jadwalSurveiPerpanjangan = $this->jadwalSurveiPerpanjanganModel->find($id);
            if ($jadwalSurveiPerpanjangan) {
                $jadwalSurveiPerpanjangan->delete();
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
