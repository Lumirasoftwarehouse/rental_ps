<?php

namespace App\Services\Litpers;

use App\Repositories\Litpers\JadwalSurveiPenerbitanBaruRepository;

class JadwalSurveiPenerbitanBaruService
{
    private $jadwalSurveiPenerbitanBaruRepository;

    public function __construct(JadwalSurveiPenerbitanBaruRepository $jadwalSurveiPenerbitanBaruRepository)
    {
        $this->jadwalSurveiPenerbitanBaruRepository = $jadwalSurveiPenerbitanBaruRepository;
    }

    public function listDataJadwalSurveiPenerbitanBaru($id)
    {
        try {
            return $this->jadwalSurveiPenerbitanBaruRepository->listDataJadwalSurveiPenerbitanBaru($id);
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
            return $this->jadwalSurveiPenerbitanBaruRepository->detailDataJadwalSurveiPenerbitanBaru($id);
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
            return $this->jadwalSurveiPenerbitanBaruRepository->inputDataJadwalSurveiPenerbitanBaru($dataRequest);
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
        try {
            return $this->jadwalSurveiPenerbitanBaruRepository->updateDataJadwalSurveiPenerbitanBaru($dataRequest, $id);
        } catch (\Exception $e) {
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
            return $this->jadwalSurveiPenerbitanBaruRepository->deleteDataJadwalSurveiPenerbitanBaru($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
