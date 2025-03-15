<?php

namespace App\Services\Pamsut;

use App\Repositories\Pamsut\AdminLanudRepository;

class AdminLanudService
{
    private $adminLanudRepository;

    public function __construct(AdminLanudRepository $adminLanudRepository)
    {
        $this->adminLanudRepository = $adminLanudRepository;
    }

    public function listDataAdminLanud()
    {
        try {
            return $this->adminLanudRepository->listDataAdminLanud();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataAdminLanud($id)
    {
        try {
            return $this->adminLanudRepository->detailDataAdminLanud($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataAdminLanud($dataRequest)
    {
        try {
            return $this->adminLanudRepository->inputDataAdminLanud($dataRequest);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataAdminLanud($dataRequest, $id)
    {
        try {
            return $this->adminLanudRepository->updateDataAdminLanud($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataAdminLanud($id)
    {
        try {
            return $this->adminLanudRepository->deleteDataAdminLanud($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
