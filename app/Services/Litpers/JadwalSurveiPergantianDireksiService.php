<?php

namespace App\Services\Litpers;

use App\Repositories\Litpers\JadwalSurveiPergantianDireksiRepository;

class JadwalSurveiPergantianDireksiService
{
    private $jadwalSurveiPergantianDireksiRepository;

    public function __construct(JadwalSurveiPergantianDireksiRepository $jadwalSurveiPergantianDireksiRepository)
    {
        $this->jadwalSurveiPergantianDireksiRepository = $jadwalSurveiPergantianDireksiRepository;
    }

    public function listDataJadwalSurveiPergantianDireksi($id)
    {
        try {
            return $this->jadwalSurveiPergantianDireksiRepository->listDataJadwalSurveiPergantianDireksi($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function detailDataJadwalSurveiPergantianDireksi($id)
    {
        try {
            return $this->jadwalSurveiPergantianDireksiRepository->detailDataJadwalSurveiPergantianDireksi($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function inputDataJadwalSurveiPergantianDireksi($dataRequest)
    {
        try {
            return $this->jadwalSurveiPergantianDireksiRepository->inputDataJadwalSurveiPergantianDireksi($dataRequest);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function updateDataJadwalSurveiPergantianDireksi($dataRequest, $id)
    {
        try {
            return $this->jadwalSurveiPergantianDireksiRepository->updateDataJadwalSurveiPergantianDireksi($dataRequest, $id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }

    public function deleteDataJadwalSurveiPergantianDireksi($id)
    {
        try {
            return $this->jadwalSurveiPergantianDireksiRepository->deleteDataJadwalSurveiPergantianDireksi($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "message" => $e->getMessage()
            ];
        }
    }
}
