<?php

namespace App\Services\Pamsut;

use App\Repositories\Pamsut\JenisKasusRepository;

class JenisKasusService
{
    private $jenisKasusRepository;

    public function __construct(JenisKasusRepository $jenisKasusRepository)
    {
        $this->jenisKasusRepository = $jenisKasusRepository;
    }

    public function listDataJenisKasus()
    {
        try {
            return $this->jenisKasusRepository->listDataJenisKasus();
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
            return $this->jenisKasusRepository->detailDataJenisKasus($id);
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
            return $this->jenisKasusRepository->inputDataJenisKasus($dataRequest);
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
        try {
            return $this->jenisKasusRepository->updateDataJenisKasus($dataRequest, $id);
        } catch (\Exception $e) {
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
            return $this->jenisKasusRepository->deleteDataJenisKasus($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
