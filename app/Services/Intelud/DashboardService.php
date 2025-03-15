<?php

namespace App\Services\Intelud;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use App\Repositories\Intelud\DashboardRepository;

class DashboardService
{
    private $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function dashboardAdmin()
    {
        try {
            return $this->dashboardRepository->dashboardAdmin();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function dashboardPic()
    {
        try {
            return $this->dashboardRepository->dashboardPic();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function dashboardMitra()
    {
        try {
            return $this->dashboardRepository->dashboardMitra();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function dashboardKaintel()
    {
        try {
            return $this->dashboardRepository->dashboardKaintel();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    private function cekLevelUser($levelTarget)
    {
        $levelPembanding = $this->dashboardRepository->cekLevelUser();
        if ($levelTarget == $levelPembanding) {
            return true;
        }
        return false;
    }
}
