<?php

namespace App\Repositories\Litpers;

use App\Models\PicPerusahaanLitpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PicPerusahaanLitpersRepository
{
    private $picPerusahaanLitpersModel;

    public function __construct(PicPerusahaanLitpers $picPerusahaanLitpersModel)
    {
        $this->picPerusahaanLitpersModel = $picPerusahaanLitpersModel;
    }

    public function listDataPicPerusahaanLitpers()
    {
        try {
            $dataPicPerusahaanLitpers = $this->picPerusahaanLitpersModel->get();
            if ($dataPicPerusahaanLitpers) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataPicPerusahaanLitpers,
                    "message" => 'get data pic perusahaan litpers success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data pic perusahaan litpers not found'
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

    public function detailDataPicPerusahaanLitpers($id)
    {
        try {
            $dataPicPerusahaanLitpers = $this->picPerusahaanLitpersModel->find($id);
            if ($dataPicPerusahaanLitpers) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataPicPerusahaanLitpers,
                    "message" => 'get detail data pic perusahaan litpers success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data pic perusahaan litpers not found'
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

    public function inputDataPicPerusahaanLitpers($dataRequest)
    {
        try {
            $result = $this->picPerusahaanLitpersModel->insert($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data pic perusahaan litpers success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataPicPerusahaanLitpers($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataPicPerusahaanLitpers = $this->picPerusahaanLitpersModel->find($id);
            $dataPicPerusahaanLitpers->nama_perusahaan = $dataRequest['nama_perusahaan'];
            $dataPicPerusahaanLitpers->nama_pic = $dataRequest['nama_pic'];
            $dataPicPerusahaanLitpers->jabatan_pic = $dataRequest['jabatan_pic'];
            $dataPicPerusahaanLitpers->hp_pic = $dataRequest['hp_pic'];
            $dataPicPerusahaanLitpers->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data pic perusahaan litpers success'
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

    public function deleteDataPicPerusahaanLitpers($id)
    {
        try {
            $picPerusahaanLitpers = $this->picPerusahaanLitpersModel->find($id);
            if ($picPerusahaanLitpers) {
                $picPerusahaanLitpers->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data pic perusahaan litpers success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data pic perusahaan litpers tidak ditemukan'
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
