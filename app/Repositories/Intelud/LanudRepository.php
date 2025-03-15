<?php

namespace App\Repositories\Intelud;

use App\Models\Lanud;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class LanudRepository
{
    private $lanudModel;

    public function __construct(Lanud $lanudModel)
    {
        $this->lanudModel = $lanudModel;
    }

    public function listDataLanud()
    {
        try {
            $dataLanud = $this->lanudModel->get();
            if ($dataLanud) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataLanud,
                    "message" => 'get data lanud success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data lanud not found'
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

    public function detailDataLanud($id)
    {
        try {
            $dataLanud = $this->lanudModel->find($id);
            if ($dataLanud) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataLanud,
                    "message" => 'get detail data lanud success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data lanud not found'
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

    public function inputDataLanud($dataRequest)
    {
        DB::beginTransaction();
        try {
            $this->lanudModel->insert($dataRequest);

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data lanud success'
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

    public function updateDataLanud($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataLanud = $this->lanudModel->find($id);
            $dataLanud->nama_lanud = $dataRequest['nama_lanud'];
            $dataLanud->alamat = $dataRequest['alamat'];
            $dataLanud->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data lanud success'
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

    public function deleteDataLanud($id)
    {
        try {
            $lanud = $this->lanudModel->find($id);
            if ($lanud) {
                $lanud->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data lanud success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data lanud tidak ditemukan'
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
