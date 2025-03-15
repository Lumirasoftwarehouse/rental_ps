<?php

namespace App\Services\Pamsut;

use App\Repositories\Pamsut\DashboardRepository;

class DashboardService
{
    private $dashboardRepository;

    public function __construct(DashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function listDataDashboardAdminPamsut()
    {
        try {
            return $this->dashboardRepository->listDataDashboardAdminPamsut();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    // public function listDataDashboardAdminLitpers()
    // {
    //     try {
    //         return $this->dashboardRepository->listDataDashboardAdminLitpers();
    //     } catch (\Exception $e) {
    //         return [
    //             "id" => '0',
    //             "statusCode" => 401,
    //             "data" => [],
    //             "message" => $e->getMessage()
    //         ];
    //     }
    // }

    public function listAvailableYearsAdminPamsut()
    {
        try {
            return $this->dashboardRepository->listAvailableYearsAdminPamsut();
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartDailyAdminPamsut()
    {
        try {
            $response = $this->dashboardRepository->listLineChartDailyAdminPamsut();

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartMonthlyAdminPamsut()
    {
        try {
            $response = $this->dashboardRepository->listLineChartMonthlyAdminPamsut();

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartYearlyAdminPamsut()
    {
        try {
            $response = $this->dashboardRepository->listLineChartYearlyAdminPamsut();

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartJenisKasusAdminPamsut($selectedJenisKasus)
    {
        try {
            // Ambil data dari repository berdasarkan filter
            $response = $this->dashboardRepository->listLineChartJenisKasusAdminPamsut($selectedJenisKasus);

            return $response;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartTriwulanAdminPamsut($selectedTriwulan, $selectedTahun)
    {
        try {
            // Ambil data dari repository berdasarkan filter
            $response = $this->dashboardRepository->listLineChartTriwulanAdminPamsut($selectedTriwulan, $selectedTahun);

            return $response;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartWeeklyAdminPamsut()
    {
        try {
            $response = $this->dashboardRepository->listPieChartWeeklyAdminPamsut();

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartMonthlyAdminPamsut()
    {
        try {
            $response = $this->dashboardRepository->listPieChartMonthlyAdminPamsut();

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartYearlyAdminPamsut()
    {
        try {
            $response = $this->dashboardRepository->listPieChartYearlyAdminPamsut();

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartJenisKasusAdminPamsut($selectedJenisKasus)
    {
        try {
            $response = $this->dashboardRepository->listPieChartJenisKasusAdminPamsut($selectedJenisKasus);

            return $response;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartTriwulanAdminPamsut($selectedTriwulan, $selectedTahun)
    {
        try {
            $response = $this->dashboardRepository->listPieChartTriwulanAdminPamsut($selectedTriwulan, $selectedTahun);

            return $response;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listAvailableYearsAdminLanud($id)
    {
        try {
            return $this->dashboardRepository->listAvailableYearsAdminLanud($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listDataDashboardAdminLanud($id)
    {
        try {
            return $this->dashboardRepository->listDataDashboardAdminLanud($id);
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartDailyAdminLanud($id)
    {
        try {
            $response = $this->dashboardRepository->listLineChartDailyAdminLanud($id);

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartMonthlyAdminLanud($id)
    {
        try {
            $response = $this->dashboardRepository->listLineChartMonthlyAdminLanud($id);

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartYearlyAdminLanud($id)
    {
        try {
            $response = $this->dashboardRepository->listLineChartYearlyAdminLanud($id);

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartJenisKasusAdminLanud($id, $selectedJenisKasus)
    {
        try {
            // Ambil data dari repository berdasarkan filter
            $response = $this->dashboardRepository->listLineChartJenisKasusAdminLanud($id, $selectedJenisKasus);

            return $response;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listLineChartTriwulanAdminLanud($id, $selectedTriwulan, $selectedTahun)
    {
        try {
            // Ambil data dari repository berdasarkan filter
            $response = $this->dashboardRepository->listLineChartTriwulanAdminLanud($id, $selectedTriwulan, $selectedTahun);

            return $response;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartWeeklyAdminLanud($id)
    {
        try {
            $response = $this->dashboardRepository->listPieChartWeeklyAdminLanud($id);

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartMonthlyAdminLanud($id)
    {
        try {
            $response = $this->dashboardRepository->listPieChartMonthlyAdminLanud($id);

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartYearlyAdminLanud($id)
    {
        try {
            $response = $this->dashboardRepository->listPieChartYearlyAdminLanud($id);

            // Convert the JSON response to an array
            $responseArray = $response->getData(true); // getData(true) converts the response to an array

            return $responseArray;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartJenisKasusAdminLanud($id, $selectedJenisKasus)
    {
        try {
            $response = $this->dashboardRepository->listPieChartJenisKasusAdminLanud($id, $selectedJenisKasus);

            return $response;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }

    public function listPieChartTriwulanAdminLanud($id, $selectedTriwulan, $selectedTahun)
    {
        try {
            $response = $this->dashboardRepository->listPieChartTriwulanAdminLanud($id, $selectedTriwulan, $selectedTahun);

            return $response;
        } catch (\Exception $e) {
            return [
                "id" => '0',
                "statusCode" => 401,
                "data" => [],
                "message" => $e->getMessage()
            ];
        }
    }
}
