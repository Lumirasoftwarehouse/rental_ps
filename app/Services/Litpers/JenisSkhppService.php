<?php

namespace App\Services\Litpers;

use App\Repositories\Litpers\JenisSkhppRepository;

class JenisSkhppService
{
    private $jenisSkhppRepository;

    public function __construct(JenisSkhppRepository $jenisSkhppRepository)
    {
        $this->jenisSkhppRepository = $jenisSkhppRepository;
    }

    public function jumlahDataSkhpp()
    {
        try {
            return $this->jenisSkhppRepository->jumlahDataSkhpp();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataAllSkhpp()
    {
        try {
            return $this->jenisSkhppRepository->listDataAllSkhpp();
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
            return $this->jenisSkhppRepository->listDataAllSkhppByMitra($id);
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
            return $this->jenisSkhppRepository->listDataJenisSkhpp();
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
            return $this->jenisSkhppRepository->detailDataJenisSkhpp($id);
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
            return $this->jenisSkhppRepository->inputDataJenisSkhpp($dataRequest);
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
        try {
            return $this->jenisSkhppRepository->updateDataJenisSkhpp($dataRequest, $id);
        } catch (\Exception $e) {
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
            return $this->jenisSkhppRepository->deleteDataJenisSkhpp($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
