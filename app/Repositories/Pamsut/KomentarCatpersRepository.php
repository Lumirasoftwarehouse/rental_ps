<?php

namespace App\Repositories\Pamsut;

use App\Models\KomentarCatpers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KomentarCatpersRepository
{
    private $komentarCatpersModel;

    public function __construct(KomentarCatpers $komentarCatpersModel)
    {
        $this->komentarCatpersModel = $komentarCatpersModel;
    }

    public function listDataKomentarCatpersByCatpers($id)
    {
        try {
            $dataKomentarCatpers = $this->komentarCatpersModel
                ->where('catpers_id', $id)
                ->get();
            if ($dataKomentarCatpers) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataKomentarCatpers,
                    "message" => 'get data komentar catpers success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'data komentar catpers not found'
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

    public function detailDataKomentarCatpers($id)
    {
        try {
            $dataKomentarCatpers = $this->komentarCatpersModel->find($id);
            if ($dataKomentarCatpers) {
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "data" => $dataKomentarCatpers,
                    "message" => 'get detail data komentar catpers success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "data" => [],
                    "message" => 'detail data komentar catpers not found'
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

    public function inputDataKomentarCatpers($dataRequest)
    {
        try {
            $result = $this->komentarCatpersModel->insert($dataRequest);
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'input data komentar catpers success'
            ];
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataKomentarCatpers($dataRequest, $id)
    {
        DB::beginTransaction();
        try {
            $dataKomentarCatpers = $this->komentarCatpersModel->find($id);
            $dataKomentarCatpers->isi_komentar = $dataRequest['isi_komentar'];
            $dataKomentarCatpers->save();

            DB::commit();
            return [
                "id" => '1',
                "statusCode" => 200,
                "message" => 'update data komentar catpers success'
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

    public function deleteDataKomentarCatpers($id)
    {
        try {
            $komentarCatpers = $this->komentarCatpersModel->find($id);
            if ($komentarCatpers) {
                $komentarCatpers->delete();
                return [
                    "id" => '1',
                    "statusCode" => 200,
                    "message" => 'delete data komentar catpers success'
                ];
            } else {
                return [
                    "id" => '0',
                    "statusCode" => 404,
                    "message" => 'data komentar catpers tidak ditemukan'
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
