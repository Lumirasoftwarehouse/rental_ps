<?php

namespace App\Services\Litpers;

use App\Repositories\Litpers\JadwalSurveiPerpanjanganRepository;

class JadwalSurveiPerpanjanganService
{
    private $jadwalSurveiPerpanjanganRepository;

    public function __construct(JadwalSurveiPerpanjanganRepository $jadwalSurveiPerpanjanganRepository)
    {
        $this->jadwalSurveiPerpanjanganRepository = $jadwalSurveiPerpanjanganRepository;
    }

    public function listDataJadwalSurveiPerpanjangan($id)
    {
        try {
            return $this->jadwalSurveiPerpanjanganRepository->listDataJadwalSurveiPerpanjangan($id);
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
            return $this->jadwalSurveiPerpanjanganRepository->detailDataJadwalSurveiPerpanjangan($id);
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
            return $this->jadwalSurveiPerpanjanganRepository->inputDataJadwalSurveiPerpanjangan($dataRequest);
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
        try {
            return $this->jadwalSurveiPerpanjanganRepository->updateDataJadwalSurveiPerpanjangan($dataRequest, $id);
        } catch (\Exception $e) {
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
            return $this->jadwalSurveiPerpanjanganRepository->deleteDataJadwalSurveiPerpanjangan($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
