<?php

namespace App\Repositories\Litpers;

use App\Models\JadwalSurveiPenerbitanBaru;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JadwalSurveiPenerbitanBaruRepository
{
    private $jadwalSurveiPenerbitanBaruModel;

    public function __construct(JadwalSurveiPenerbitanBaru $jadwalSurveiPenerbitanBaruModel)
    {
        $this->jadwalSurveiPenerbitanBaruModel = $jadwalSurveiPenerbitanBaruModel;
    }

    public function listDataJadwalSurveiPenerbitanBaru($id)
    {
        try {
            $dataJadwalSurveiPenerbitanBaru = $this->jadwalSurveiPenerbitanBaruModel->where('f_tiga_penerbitan_id', $id)->get();
            if ($dataJadwalSurveiPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJadwalSurveiPenerbitanBaru,
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

    public function detailDataJadwalSurveiPenerbitanBaru($id)
    {
        try {
            $dataJadwalSurveiPenerbitanBaru = $this->jadwalSurveiPenerbitanBaruModel->where('f_tiga_penerbitan_id', $id)->first();
            if ($dataJadwalSurveiPenerbitanBaru) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJadwalSurveiPenerbitanBaru,
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

    public function inputDataJadwalSurveiPenerbitanBaru($dataRequest)
    {
        try {
            $result = $this->jadwalSurveiPenerbitanBaruModel->insert($dataRequest);
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

    public function updateDataJadwalSurveiPenerbitanBaru($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataJadwalSurveiPenerbitanBaru = $this->jadwalSurveiPenerbitanBaruModel->find($id);
            $dataJadwalSurveiPenerbitanBaru->nama_admin = $dataRequest['nama_admin'];
            $dataJadwalSurveiPenerbitanBaru->tanggal_awal = $dataRequest['tanggal_awal'];
            $dataJadwalSurveiPenerbitanBaru->tanggal_akhir = $dataRequest['tanggal_akhir'];
            $dataJadwalSurveiPenerbitanBaru->alamat_survei = $dataRequest['alamat_survei'];
            $dataJadwalSurveiPenerbitanBaru->hp_admin = $dataRequest['hp_admin'];
            $dataJadwalSurveiPenerbitanBaru->save();

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

    public function deleteDataJadwalSurveiPenerbitanBaru($id)
    {
        try {
            $jadwalSurveiPenerbitanBaru = $this->jadwalSurveiPenerbitanBaruModel->find($id);
            if ($jadwalSurveiPenerbitanBaru) {
                $jadwalSurveiPenerbitanBaru->delete();
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
