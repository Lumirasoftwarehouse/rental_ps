<?php

namespace App\Repositories\Pamsut;

use App\Models\JenisKasus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class JenisKasusRepository
{
    private $jenisKasusModel;

    public function __construct(JenisKasus $jenisKasusModel)
    {
        $this->jenisKasusModel = $jenisKasusModel;
    }

    public function listDataJenisKasus()
    {
        try {
            $dataJenisKasus = $this->jenisKasusModel->get();
            if ($dataJenisKasus) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJenisKasus,
                    "message" => 'get data jenis kasus success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data jenisKasus not found'
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

    public function detailDataJenisKasus($id)
    {
        try {
            $dataJenisKasus = $this->jenisKasusModel->find($id);
            if ($dataJenisKasus) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataJenisKasus,
                    "message" => 'get detail data jenis kasus success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data jenis kasus not found'
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

    public function inputDataJenisKasus($dataRequest)
    {
        try {
            $result = $this->jenisKasusModel->insert($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data jenis kasus success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataJenisKasus($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataJenisKasus = $this->jenisKasusModel->find($id);
            $dataJenisKasus->jenis_kasus = $dataRequest['jenis_kasus'];
            $dataJenisKasus->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data jenis kasus success'
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

    public function deleteDataJenisKasus($id)
    {
        try {
            $jenisKasus = $this->jenisKasusModel->find($id);
            if ($jenisKasus) {
                $jenisKasus->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data jenis kasus success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data jenis kasus tidak ditemukan'
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
